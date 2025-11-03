# Invoice Generation Service

A reusable service for generating invoices from any billable model in the application.

## Features

- ✅ **Generic**: Works with any Eloquent model, not just rentals
- ✅ **Reusable**: Can be used in Filament components, controllers, jobs, etc.
- ✅ **Flexible**: Customizable invoice items, dates, and options
- ✅ **PDF Generation**: Automatically generates and saves PDF invoices
- ✅ **Validation**: Built-in validation for all inputs
- ✅ **Address Integration**: Uses the role-based address helper methods

## Usage

### 1. Generic Invoice Generation

```php
use App\Services\InvoiceGenerationService;

// For any model
$invoiceItems = [
    [
        'name' => 'Service Fee',
        'quantity' => 1,
        'price_per_unit' => 100.00,
    ],
    [
        'name' => 'Additional Charges',
        'quantity' => 2,
        'price_per_unit' => 25.00,
    ]
];

$invoice = InvoiceGenerationService::generate(
    $billableModel,  // Any Eloquent model
    $customerUser,   // User to bill (customer)
    $sellerUser,     // User billing from (seller/landlord)
    $invoiceItems,   // Array of invoice items
    [
        'invoice_name' => 'Custom Invoice Name',
        'due_date' => now()->addDays(15),
        'status' => 'pending',
        'generate_pdf' => true, // default
    ]
);
```

### 2. Rental-Specific Generation (Recommended)

```php
use App\Services\InvoiceGenerationService;

// Simple rental invoice
$invoice = InvoiceGenerationService::generateForRental($rental);

// With custom options
$invoice = InvoiceGenerationService::generateForRental($rental, [
    'include_security_deposit' => true,
    'invoice_name' => 'Monthly Rent Invoice',
    'due_date' => now()->addDays(7),
    'status' => 'sent',
]);

// With custom invoice items
$customItems = [
    [
        'name' => 'Rent for January 2024',
        'quantity' => 1,
        'price_per_unit' => 1500.00,
    ],
    [
        'name' => 'Utilities',
        'quantity' => 1,
        'price_per_unit' => 150.00,
    ]
];

$invoice = InvoiceGenerationService::generateForRental($rental, [
    'invoice_items' => $customItems,
]);
```

### 3. Using Helper Methods in Rental Model

```php
// Direct from rental model
$rental = Rental::find(1);

// Generate invoice
$invoice = $rental->generateInvoice();

// With options
$invoice = $rental->generateInvoice([
    'include_security_deposit' => true,
    'due_date' => now()->addDays(15),
]);

// Check if rental has invoices
if ($rental->hasInvoices()) {
    $invoices = $rental->getInvoices();
}
```

### 4. In Filament Components

The service is already integrated in the `EditRental` page as a header action.

#### Custom Filament Action Example:

```php
Action::make('generateInvoice')
    ->label('Generate Invoice')
    ->icon('heroicon-o-document-plus')
    ->action(function ($record) {
        try {
            $invoice = InvoiceGenerationService::generateForRental($record);
            
            Notification::make()
                ->title('Invoice Generated')
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    })
```

### 5. PDF Generation Only

```php
// For existing invoice
$invoice = Invoice::find(1);
$pdfPath = InvoiceGenerationService::generateInvoicePdf($invoice);
```

### 6. Utility Methods

```php
// Check if any model has invoices
$hasInvoices = InvoiceGenerationService::modelHasInvoices($anyModel);

// Get all invoices for a model
$invoices = InvoiceGenerationService::getModelInvoices($anyModel);
```

## Available Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `invoice_name` | string | Auto-generated | Custom invoice name |
| `invoice_number` | string | Auto-generated | Custom invoice number |
| `invoice_date` | Carbon | now() | Invoice date |
| `due_date` | Carbon | now()->addDays(30) | Due date |
| `status` | string | 'pending' | Invoice status |
| `invoice_items` | array | Auto-generated | Custom invoice items |
| `generate_pdf` | bool | true | Generate PDF automatically |
| `include_security_deposit` | bool | false | Include security deposit (rental only) |

## Invoice Item Structure

```php
[
    'name' => 'Item Name',           // Required
    'quantity' => 1,                 // Optional, default: 1
    'price_per_unit' => 100.00,      // Required, must be >= 0
]
```

## Error Handling

The service includes comprehensive validation and will throw exceptions for:
- Missing or unsaved models
- Invalid invoice items
- Missing user relationships
- Negative prices
- Empty invoice items

Always wrap service calls in try-catch blocks for proper error handling.

## Integration Points

1. **Header Action**: Available in EditRental page
2. **Relation Manager**: Access via rental invoices tab
3. **Model Methods**: Direct methods on Rental model
4. **Service Class**: Direct service usage anywhere in the application

## Example Workflow

1. User edits a rental in Filament
2. Clicks "Generate Invoice" in header
3. Service validates rental data
4. Creates invoice with rental details
5. Generates PDF automatically
6. Shows success notification with view link
7. Invoice appears in rental's invoices relation manager