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
        Schema::table('users', function (Blueprint $table) {
            // Profile details
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('display_name')->nullable()->after('last_name');

            // Billing Address details
            $table->string('billing_company')->nullable()->after('display_name');
            $table->foreignId('billing_country_id')->nullable()->after('billing_company')->constrained('countries')->nullOnDelete();
            $table->foreignId('billing_division_id')->nullable()->after('billing_country_id')->constrained('divisions')->nullOnDelete();
            $table->foreignId('billing_district_id')->nullable()->after('billing_division_id')->constrained('districts')->nullOnDelete();
            $table->foreignId('billing_area_id')->nullable()->after('billing_district_id')->constrained('areas')->nullOnDelete();
            $table->string('billing_address_1')->nullable()->after('billing_area_id');
            $table->string('billing_address_2')->nullable()->after('billing_address_1');
            $table->string('billing_city')->nullable()->after('billing_address_2');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_postcode')->nullable()->after('billing_state');
            $table->string('billing_phone')->nullable()->after('billing_postcode');

            // Shipping Address details
            $table->string('shipping_first_name')->nullable()->after('billing_phone');
            $table->string('shipping_last_name')->nullable()->after('shipping_first_name');
            $table->string('shipping_company')->nullable()->after('shipping_last_name');
            $table->foreignId('shipping_country_id')->nullable()->after('shipping_company')->constrained('countries')->nullOnDelete();
            $table->foreignId('shipping_division_id')->nullable()->after('shipping_country_id')->constrained('divisions')->nullOnDelete();
            $table->foreignId('shipping_district_id')->nullable()->after('shipping_division_id')->constrained('districts')->nullOnDelete();
            $table->foreignId('shipping_area_id')->nullable()->after('shipping_district_id')->constrained('areas')->nullOnDelete();
            $table->string('shipping_address_1')->nullable()->after('shipping_area_id');
            $table->string('shipping_address_2')->nullable()->after('shipping_address_1');
            $table->string('shipping_city')->nullable()->after('shipping_address_2');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_postcode')->nullable()->after('shipping_state');
            $table->string('shipping_phone')->nullable()->after('shipping_postcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['billing_country_id']);
            $table->dropForeign(['billing_division_id']);
            $table->dropForeign(['billing_district_id']);
            $table->dropForeign(['billing_area_id']);
            $table->dropForeign(['shipping_country_id']);
            $table->dropForeign(['shipping_division_id']);
            $table->dropForeign(['shipping_district_id']);
            $table->dropForeign(['shipping_area_id']);

            $table->dropColumn([
                'first_name', 'last_name', 'display_name',
                'billing_company', 'billing_country_id', 'billing_division_id', 'billing_district_id', 'billing_area_id',
                'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_phone',
                'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_country_id', 'shipping_division_id', 'shipping_district_id', 'shipping_area_id',
                'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_postcode', 'shipping_phone'
            ]);
        });
    }
};
