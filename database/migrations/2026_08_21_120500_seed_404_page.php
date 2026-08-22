<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Page;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Page::updateOrCreate(['slug' => '404'], [
            'title' => 'Page Not Found',
            'description' => '<p class="lead text-muted">We are sorry, the page you\'ve requested is not available.</p>',
            'meta_title' => '404 Page Not Found - Molla eCommerce',
            'meta_description' => 'The page you requested was not found.',
            'meta_keywords' => '404, page not found, error',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Page::where('slug', '404')->delete();
    }
};
