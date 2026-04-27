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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->string('file_path');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->foreignId('publisher_id')->constrained()->onDelete('cascade');
            $table->integer('price_points')->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
