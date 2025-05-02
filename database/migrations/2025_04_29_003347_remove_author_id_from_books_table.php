<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            // Drop the foreign key constraint before dropping the column
            $table->dropForeign(['author_id']);  // Drop foreign key constraint on author_id
            $table->dropForeign(['genre_id']);   // Drop foreign key constraint on genre_id

            // Now drop the columns
            $table->dropColumn('author_id');
            $table->dropColumn('genre_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');  // Restore author_id column
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');   // Restore genre_id column
        });
    }
};
