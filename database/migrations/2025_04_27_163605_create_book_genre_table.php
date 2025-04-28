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
        Schema::create('book_genre', function (Blueprint $table) {
            // Use unsignedBigInteger for foreign keys
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('genre_id');
            
            // Define foreign keys with cascading delete
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('genre_id')->references('genre_id')->on('genres')->onDelete('cascade');
            
            // Define a composite primary key
            $table->primary(['book_id', 'genre_id']);
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_genre');
    }
};
