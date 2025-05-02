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
        Schema::table('books', function (Blueprint $table) {
            $table->integer('total_copies')->after('isbn'); 
            $table->integer('volume')->after('total_copies'); 
            $table->date('published_date')->after('volume'); 
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('total_copies');
            $table->dropColumn('volume');
            $table->dropColumn('published_date');
        });
    }
};
