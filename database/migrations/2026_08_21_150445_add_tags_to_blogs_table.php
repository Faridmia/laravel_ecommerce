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
            $table->string('tags')->nullable()->after('description');
        });

        // Set default tags for the pre-seeded posts
        \DB::table('blogs')->where('id', 1)->update(['tags' => 'Decor, Furniture']);
        \DB::table('blogs')->where('id', 2)->update(['tags' => 'Style Guide, Interior']);
        \DB::table('blogs')->where('id', 3)->update(['tags' => 'Furniture, Decor, Lighting']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }
};
