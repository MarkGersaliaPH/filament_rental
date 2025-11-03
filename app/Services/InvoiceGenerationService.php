<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Seller;

class InvoiceGenerationService
{
    /**
     * Generate an invoice from any billable model.
     * 
     * @param Model $billableModel The model to generate invoice for
     * @param User $billTo The user to bill (customer)
     * @param User $billFrom The user billing from (seller/landlord)
     * @param array $invoiceItems Array of invoice items
     * @param array $options Additional options for invoice generation
     * @return Invoice The created invoice
     * @throws \Exception If validation fails or invoice cannot be created
     */
    public static function generate(
        Model $billableModel,
        User $billTo,
        User $billFrom,
        array $invoiceItems,
        array $options = []
    ): Invoice {
        // Validate input data
        self::validateInputData($billableModel, $billTo, $billFrom, $invoiceItems);


        // Prepare invoice data
        $invoiceData = self::prepareInvoiceData(
            $billableModel,
            $billTo,
            $billFrom,
            $invoiceItems,
            $options
        );

        // Create the invoice
        $invoice = Invoice::create($invoiceData);


        // Generate PDF if requested (default: true)
        if ($options['generate_pdf'] ?? true) {
            self::generateInvoicePdf($invoice);
        }

        return $invoice;
    }

    /**
     * Generate invoice PDF for an existing invoice.
     * 
     * @param Invoice $invoice The invoice to generate PDF for
     * @return string The file path of the generated PDF
     * @throws \Exception If PDF generation fails
     */
    public static function generateInvoicePdf(Invoice $invoice): string
    {
        // Get buyer and seller information
        $buyer = self::createBuyerFromInvoice($invoice);
        $seller = self::createSellerFromInvoice($invoice);

        // Generate the PDF
        $filePath = $invoice->generateInvoice($buyer, $seller);


        // Update invoice with file path
        $invoice->update(['file_path' => $filePath]);

        return $filePath;
    }

    /**
     * Validate input data before invoice generation.
     * 
     * @param Model $billableModel
     * @param User $billTo
     * @param User $billFrom
     * @param array $invoiceItems
     * @throws \Exception
     */
    protected static function validateInputData(
        Model $billableModel,
        User $billTo,
        User $billFrom,
        array $invoiceItems
    ): void {
        if (!$billableModel->exists) {
            throw new \Exception('Billable model must be saved before generating an invoice.');
        }

        if (!$billTo->exists) {
            throw new \Exception('Bill-to user must exist.');
        }

        if (!$billFrom->exists) {
            throw new \Exception('Bill-from user must exist.');
        }

        if (empty($invoiceItems)) {
            throw new \Exception('Invoice must have at least one item.');
        }

        // Validate invoice items structure
        foreach ($invoiceItems as $item) {
            if (!isset($item['name']) || !isset($item['price_per_unit'])) {
                throw new \Exception('Each invoice item must have name and price_per_unit.');
            }

            if ($item['price_per_unit'] < 0) {
                throw new \Exception('Invoice item price cannot be negative.');
            }
        }
    }

    /**
     * Prepare invoice data from inputs.
     * 
     * @param Model $billableModel
     * @param User $billTo
     * @param User $billFrom
     * @param array $invoiceItems
     * @param array $options
     * @return array
     */
    protected static function prepareInvoiceData(
        Model $billableModel,
        User $billTo,
        User $billFrom,
        array $invoiceItems,
        array $options = []
    ): array {
        // Generate unique invoice number
        $invoiceNumber = $options['invoice_number'] ?? 'INV-' . strtoupper(uniqid());

        // Calculate total amount
        $totalAmount = collect($invoiceItems)->sum(function ($item) {
            return ($item['price_per_unit'] ?? 0) * ($item['quantity'] ?? 1);
        });

        return [
            'billable_type' => get_class($billableModel),
            'billable_id' => $billableModel->id,
            'bill_to_id' => $billTo->id,
            'bill_from_id' => $billFrom->id,
            'invoice_number' => $invoiceNumber,
            'name' => $options['invoice_name'] ?? sprintf('Invoice for %s #%d', class_basename($billableModel), $billableModel->id),
            'invoice_date' => $options['invoice_date'] ?? now(),
            'due_date' => $options['due_date'] ?? now()->addDays(30),
            'status' => $options['status'] ?? 'pending',
            'payment_status' => $options['payment_status'] ?? PaymentStatus::Pending,
            'invoice_items' => $invoiceItems,
            'amount' => $totalAmount,
        ];
    }

    /**
     * Create buyer object from invoice.
     * 
     * @param Invoice $invoice
     * @return Buyer
     */
    protected static function createBuyerFromInvoice(Invoice $invoice): Buyer
    {
        $buyerUser = User::find($invoice->bill_to_id);

        return new Buyer([
            'name' => $buyerUser->name,
            'custom_fields' => [
                'email' => $buyerUser->email,
            ],
            'address' => $buyerUser->getRoleBasedFormattedAddress() ?? 'Address not available'
        ]);
    }

    /**
     * Create seller object from invoice.
     * 
     * @param Invoice $invoice
     * @return Seller
     */
    protected static function createSellerFromInvoice(Invoice $invoice): Seller
    {
        $sellerUser = User::find($invoice->bill_from_id);

        return new Seller([
            'name' => $sellerUser->name,
            'custom_fields' => [
                'email' => $sellerUser->email,
            ],
            'address' => $sellerUser->getRoleBasedFormattedAddress() ?? 'Address not available'
        ]);
    }

    /**
     * Check if a model already has invoices.
     * 
     * @param Model $model
     * @return bool
     */
    public static function modelHasInvoices(Model $model): bool
    {
        return Invoice::where('billable_type', get_class($model))
            ->where('billable_id', $model->id)
            ->exists();
    }

    /**
     * Get all invoices for a model.
     * 
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getModelInvoices(Model $model)
    {
        return Invoice::where('billable_type', get_class($model))
            ->where('billable_id', $model->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Helper method specifically for rental invoices.
     * 
     * @param \App\Models\Rental $rental
     * @param array $options
     * @return Invoice
     */
    public static function generateForRental(\App\Models\Rental $rental, array $options = []): Invoice
    {
        // Validate rental-specific data
        if (!$rental->customer || !$rental->customer->user) {
            throw new \Exception('Customer or customer user not found for this rental.');
        }

        if (!$rental->landlord || !$rental->landlord->user) {
            throw new \Exception('Property landlord or landlord user not found for this rental.');
        }

        if (!$rental->rent_amount || $rental->rent_amount <= 0) {
            throw new \Exception('Rental amount must be greater than zero.');
        }

        // Prepare rental-specific invoice items
        $startDate = $rental->start_date;
        $endDate = $rental->end_date ?? $startDate->copy()->addMonth();


        $invoiceItems = $options['invoice_items'] ?? [
            [
                'name' => sprintf(
                    'Rent for %s (%s - %s)',
                    $rental->property->title,
                    $startDate->format('M d, Y'),
                    $endDate->format('M d, Y')
                ),
                'quantity' => 1,
                'price_per_unit' => (float) $rental->rent_amount,
            ]
        ];

        // Add security deposit if specified
        if (($options['include_security_deposit'] ?? false) && $rental->security_deposit > 0) {
            $invoiceItems[] = [
                'name' => 'Security Deposit',
                'quantity' => 1,
                'price_per_unit' => (float) $rental->security_deposit,
            ];
        }
        $invoice_name = $rental->payment_status == PaymentStatus::Paid
            ? sprintf('Payment Receipt- %s', $rental->property->title)
            : sprintf('Rental Invoice - %s', $rental->property->title);
 
        // Set default invoice name for rentals
        $options['invoice_name'] = $options['invoice_name'] ?? $invoice_name;


        return self::generate(
            $rental,
            $rental->customer->user,
            $rental->landlord->user,
            $invoiceItems,
            $options
        );
    }
}
