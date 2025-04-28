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
        Schema::create('book_author', function (Blueprint $table) {
            // Define foreign key columns in the junction table
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('author_id');
            
            // Foreign keys with cascade on delete
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('author_id')->references('author_id')->on('authors')->onDelete('cascade');
            
            // Primary key for the junction table
            $table->primary(['book_id', 'author_id']);
            
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_author');
    }
};
