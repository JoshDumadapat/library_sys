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
        Schema::create('trans_details', function (Blueprint $table) {
            $table->id('tdetail_ID');
        
            $table->unsignedBigInteger('trans_ID');
            $table->unsignedBigInteger('book_ID');
        
            $table->enum('td_status', ['borrowed', 'returned'])->default('borrowed');
        
            $table->timestamps();
        
            // Foreign keys
            $table->foreign('trans_ID')->references('trans_ID')->on('transactions')->onDelete('cascade');
            $table->foreign('book_ID')->references('book_ID')->on('books')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_details');
    }
};
