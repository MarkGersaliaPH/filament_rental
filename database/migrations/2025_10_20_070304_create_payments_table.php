<?php

use App\Enums\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id'); 
            $table->foreignId('payer_id')->nullable()->constrained('users')->nullOnDelete();   // usually the customer
            $table->foreignId('receiver_id')->nullable()->constrained('users')->nullOnDelete(); // usually the landlord


            $table->enum('payment_method',[ 
                PaymentMethod::BankTransfer->value, 
                PaymentMethod::Gcash->value,
                PaymentMethod::Paypal->value,
                PaymentMethod::PayMaya->value, 
            ])->default(PaymentMethod::BankTransfer->value); 
  
            $table->string('reference_number')->nullable(); // e.g. transaction ID or receipt number
            $table->dateTime('paid_at')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed', 'refunded'])->default('pending');

            $table->decimal('amount', 10, 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
