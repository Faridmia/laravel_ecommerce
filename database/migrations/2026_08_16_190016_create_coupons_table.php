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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('discount_type'); // 'fixed' or 'percentage'
            $table->double('discount_value');
            $table->double('minimum_order_amount')->default(0);
            $table->double('maximum_discount')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_limit_per_user')->nullable();
            $table->integer('usage_count')->default(0);
            $table->tinyInteger('first_order_only')->default(0);
            $table->tinyInteger('free_shipping')->default(0);
            $table->tinyInteger('status')->default(0); // 0 = Active, 1 = Inactive
            $table->tinyInteger('is_delete')->default(0); // 0 = Not deleted, 1 = Deleted
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
