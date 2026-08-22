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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });

        // Seed initial partner logos
        \Illuminate\Support\Facades\DB::table('partners')->insert([
            [
                'name' => 'Brand 1',
                'image' => '1.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Brand 2',
                'image' => '2.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Brand 3',
                'image' => '3.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Brand 4',
                'image' => '4.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Brand 5',
                'image' => '5.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Brand 6',
                'image' => '6.png',
                'link' => '#',
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
        Schema::dropIfExists('partners');
    }
};
