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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->text('comment');
            $table->tinyInteger('status')->default(0); // 0: Pending, 1: Approved, 2: Rejected
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamps();

            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
        });

        // Insert default approved comments for testing
        \DB::table('blog_comments')->insert([
            [
                'blog_id' => 1,
                'user_id' => null,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'comment' => 'This is a fantastic article! Very informative.',
                'status' => 1,
                'is_delete' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'blog_id' => 1,
                'user_id' => null,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'comment' => 'Great insights. Loved the decoration tips!',
                'status' => 1,
                'is_delete' => 0,
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
        Schema::dropIfExists('blog_comments');
    }
};
