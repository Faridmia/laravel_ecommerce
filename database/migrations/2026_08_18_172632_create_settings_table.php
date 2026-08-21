<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed some default settings
        DB::table('settings')->insert([
            ['key' => 'guest_checkout', 'value' => 'yes', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'account_creation', 'value' => 'yes', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shipping_destination', 'value' => 'billing_default', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
