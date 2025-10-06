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
        Schema::create('landlords', function (Blueprint $table) {
            $table->id();
            
            // User relationship - links to User account
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Basic Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            
            // Contact Address
            $table->string('address');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('US');
            
            // Business Information (optional)
            $table->string('company_name')->nullable();
            $table->enum('business_type', ['individual', 'company'])->default('individual');
            $table->string('tax_id')->nullable();
            
            // Banking Information for payments
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            
            // Verification & Status
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();

            // Basic Stats
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_properties')->default(0);
            $table->integer('total_reviews')->default(0);
            
            // Profile
            $table->string('profile_photo')->nullable();
            $table->text('bio')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Essential indexes
            $table->index(['is_active', 'is_verified']);
            $table->index(['city', 'state_province']);
            $table->index(['average_rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landlords');
    }
};
