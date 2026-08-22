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
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_category_id')->nullable()->default(1)->after('id');
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('set null');
        });

        // Update any existing blogs to have category ID 1
        \DB::table('blogs')->update(['blog_category_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['blog_category_id']);
            $table->dropColumn('blog_category_id');
        });
    }
};
