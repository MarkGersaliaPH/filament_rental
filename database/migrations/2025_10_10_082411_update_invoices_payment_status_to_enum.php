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
        Schema::table('invoices', function (Blueprint $table) {
            // Add payment_status column using PaymentStatus enum
            $table->enum('payment_status', [
                PaymentStatus::Pending->value,
                PaymentStatus::Paid->value, 
                PaymentStatus::Cancelled->value,
                PaymentStatus::UnPaid->value,
                PaymentStatus::Partial->value,
                PaymentStatus::Overdue->value
            ])->default(PaymentStatus::Pending->value)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
