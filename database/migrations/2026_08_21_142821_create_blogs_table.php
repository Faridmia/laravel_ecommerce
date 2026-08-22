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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_delete')->default(0);
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });

        // Insert seed records
        \DB::table('blogs')->insert([
            [
                'title' => 'Sed adipiscing ornare.',
                'slug' => 'sed-adipiscing-ornare',
                'image' => null,
                'short_description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pelletesque aliquet nibh necurna.',
                'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pelletesque aliquet nibh necurna.',
                'status' => 0,
                'is_delete' => 0,
                'created_by' => 1,
                'created_at' => '2018-11-22 10:00:00',
                'updated_at' => '2018-11-22 10:00:00',
            ],
            [
                'title' => 'Fusce lacinia arcuet nulla.',
                'slug' => 'fusce-lacinia-arcuet-nulla',
                'image' => null,
                'short_description' => 'Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis justo.',
                'description' => 'Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis justo.',
                'status' => 0,
                'is_delete' => 0,
                'created_by' => 1,
                'created_at' => '2018-12-12 10:00:00',
                'updated_at' => '2018-12-12 10:00:00',
            ],
            [
                'title' => 'Quisque volutpat mattis eros.',
                'slug' => 'quisque-volutpat-mattis-eros',
                'image' => null,
                'short_description' => 'Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue.',
                'description' => 'Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue.',
                'status' => 0,
                'is_delete' => 0,
                'created_by' => 1,
                'created_at' => '2018-12-19 10:00:00',
                'updated_at' => '2018-12-19 10:00:00',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
