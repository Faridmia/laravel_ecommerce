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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Seed initial sliders
        \Illuminate\Support\Facades\DB::table('sliders')->insert([
            [
                'title' => 'Living Room<br>Furniture',
                'subtitle' => 'Topsale Collection',
                'button_text' => 'SHOP NOW',
                'button_link' => 'category.html',
                'image' => 'slide-1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'New Arrivals',
                'subtitle' => 'News and Inspiration',
                'button_text' => 'SHOP NOW',
                'button_link' => 'category.html',
                'image' => 'slide-2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Outdoor Dining <br>Furniture',
                'subtitle' => 'Outdoor Furniture',
                'button_text' => 'SHOP NOW',
                'button_link' => 'category.html',
                'image' => 'slide-3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
