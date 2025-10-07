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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relationship columns
            $table->morphs('addressable');
            
            // Address type (billing, shipping, mailing, etc.)
            $table->string('type')->default('general')->index();
            
            // Address fields
            $table->string('label')->nullable(); // e.g., "Home", "Office", "Warehouse"
            $table->string('street_address_1');
            $table->string('street_address_2')->nullable();
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Philippines');
            
            // Additional fields
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['addressable_type', 'addressable_id', 'type']);
            $table->index(['addressable_type', 'addressable_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
