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
            $table->id('book_id');
            $table->string('title');
            $table->string('isbn');
            $table->enum('book_status', ['available', 'unavailable']);
            
            // Ensure foreign key columns are defined
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('floor_id');
            $table->unsignedBigInteger('shelf_id');
            
            // Foreign Keys with cascade on delete
            $table->foreign('author_id')->references('author_id')->on('authors')->onDelete('cascade');
            $table->foreign('genre_id')->references('genre_id')->on('genres')->onDelete('cascade');
            $table->foreign('floor_id')->references('floor_id')->on('floors')->onDelete('cascade');
            $table->foreign('shelf_id')->references('shelf_id')->on('shelves')->onDelete('cascade');
            
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
