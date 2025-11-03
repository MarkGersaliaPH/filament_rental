<?php

use App\Enums\PaymentStatus;
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
        Schema::table('rentals', function (Blueprint $table) {
            $table->enum('payment_status', [
                PaymentStatus::Pending->value,
                PaymentStatus::Paid->value, 
                PaymentStatus::Cancelled->value,
                PaymentStatus::UnPaid->value,
                PaymentStatus::Partial->value,
                PaymentStatus::Overdue->value
            ])->default(PaymentStatus::UnPaid->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'overdue'])
                  ->default('unpaid')
                  ->change();
        });
    }
};
