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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['apartment', 'house', 'condo', 'studio', 'villa', 'townhouse', 'duplex', 'penthouse']);
            $table->enum('status', ['available', 'rented', 'maintenance', 'inactive'])->default('available');
            $table->string('slug')->unique();
            
            // Property Details
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(1);
            $table->decimal('area_sqft', 8, 2)->nullable();
            $table->decimal('area_sqm', 8, 2)->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('total_floors')->nullable();
            $table->year('built_year')->nullable();
            $table->boolean('furnished')->default(false);
            $table->boolean('pet_friendly')->default(false);
            $table->boolean('smoking_allowed')->default(false);
            
            // Location
            $table->string('address');
            $table->string('city');
            $table->string('state_province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('US');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('neighborhood')->nullable();
            
            // Pricing
            $table->decimal('rent_amount', 10, 2);
            $table->enum('rent_period', ['monthly', 'weekly', 'daily'])->default('monthly');
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->decimal('cleaning_fee', 8, 2)->nullable();
            $table->json('additional_fees')->nullable(); // utilities, parking, etc.
            
            // Availability
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->integer('minimum_stay_days')->default(30);
            $table->integer('maximum_stay_days')->nullable();
            
            // Features & Amenities
            $table->json('amenities')->nullable(); // parking, gym, pool, etc.
            $table->json('appliances')->nullable(); // washer, dryer, dishwasher, etc.
            $table->json('utilities_included')->nullable(); // electricity, water, internet, etc.
            
            // Media
            $table->json('images')->nullable();
            $table->string('video_url')->nullable();
            $table->string('virtual_tour_url')->nullable();
            
            // Contact & Management
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // SEO & Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('tags')->nullable();
            
            // System Fields
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->timestamp('last_updated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'type']);
            $table->index(['city', 'status']);
            $table->index(['rent_amount', 'status']);
            $table->index(['bedrooms', 'bathrooms']);
            $table->index(['available_from', 'available_until']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
