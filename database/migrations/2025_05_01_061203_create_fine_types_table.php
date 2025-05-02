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
        Schema::create('fine_types', function (Blueprint $table) {
            $table->id('ftype_id');
            $table->enum('reason', ['overdue', 'missing', 'damaged', 'returned'])->change();
            $table->decimal('default_amount', 8, 2)->nullable(); // For flat rates
            $table->boolean('is_per_day')->default(false); // For overdue
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_types');
    }
};
