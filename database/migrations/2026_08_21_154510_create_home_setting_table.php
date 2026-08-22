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
        Schema::create('home_setting', function (Blueprint $table) {
            $table->id();
            $table->string('trendy_product_title')->nullable();
            $table->string('shop_category_title')->nullable();
            $table->string('recent_arrival_title')->nullable();
            $table->string('blog_title')->nullable();
            
            // Payment and Delivery
            $table->string('payment_delivery_title')->nullable();
            $table->string('payment_delivery_description')->nullable();
            $table->string('payment_delivery_image')->nullable();
            
            // Refund
            $table->string('refund_title')->nullable();
            $table->string('refund_description')->nullable();
            $table->string('refund_image')->nullable();
            
            // Support
            $table->string('support_title')->nullable();
            $table->string('support_description')->nullable();
            $table->string('support_image')->nullable();
            
            // Signup (note: spelling singup from DB screenshot)
            $table->string('singup_title')->nullable();
            $table->string('singup_description')->nullable();
            $table->string('singup_image')->nullable();
            
            $table->timestamps();
        });

        // Seed a default record
        \DB::table('home_setting')->insert([
            'trendy_product_title' => 'Trendy Products',
            'shop_category_title' => 'Shop by Categories',
            'recent_arrival_title' => 'Recent Arrivals',
            'blog_title' => 'From Our Blog',
            'payment_delivery_title' => 'Payment & Delivery',
            'payment_delivery_description' => 'Free shipping for orders over $50',
            'refund_title' => 'Money Back Guarantee',
            'refund_description' => '100% money back guarantee',
            'support_title' => 'Quality Support',
            'support_description' => 'Always online feedback 24/7',
            'singup_title' => 'Get The Latest Deals',
            'singup_description' => 'and receive $20 coupon for first shopping',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_setting');
    }
};
