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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Totals
            $table->double('subtotal')->default(0);
            $table->double('discount')->default(0);
            $table->double('shipping_charge')->default(0);
            $table->double('total')->default(0);
            
            // Payment & Status
            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending');
            $table->string('status')->default('pending');
            $table->string('coupon_code')->nullable();
            $table->text('order_notes')->nullable();
            
            // Billing Details
            $table->string('billing_first_name');
            $table->string('billing_last_name');
            $table->string('billing_company')->nullable();
            $table->foreignId('billing_country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('billing_division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->foreignId('billing_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('billing_area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->string('billing_address_1');
            $table->string('billing_address_2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postcode');
            $table->string('billing_phone');
            $table->string('billing_email');

            // Shipping Details
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_company')->nullable();
            $table->foreignId('shipping_country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('shipping_division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->foreignId('shipping_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('shipping_area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->string('shipping_address_1')->nullable();
            $table->string('shipping_address_2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_phone')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
