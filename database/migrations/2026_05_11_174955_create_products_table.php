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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string('product_title', 255)->nullable();;

            $table->string('slug', 255)->unique()->nullable();;

            $table->unsignedBigInteger('category_id')->nullable();;

            $table->unsignedBigInteger('sub_category_id')->nullable();

            $table->unsignedBigInteger('brand_id')->nullable();

            $table->double('price')->default(0);

            $table->double('sale_price')->default(0);

            $table->text('short_description')->nullable();

            $table->longText('description')->nullable();

            $table->text('additional_information')->nullable();

            $table->text('shipping_returns')->nullable();

            // 0 = Active, 1 = Inactive
            $table->tinyInteger('status')->default(0);

            // 0 = Not Deleted, 1 = Deleted
            $table->tinyInteger('is_delete')->default(0);

            $table->integer('created_by')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
