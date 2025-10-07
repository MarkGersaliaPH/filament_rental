<?php

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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable'); // This creates billable_id and billable_type 
            $table->string('invoice_number')->unique();
            $table->foreignId('bill_to_id');  
            $table->foreignId('bill_from_id'); 
            $table->decimal('amount', 10, 2);
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('file_path')->nullable(); // for PDF file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
