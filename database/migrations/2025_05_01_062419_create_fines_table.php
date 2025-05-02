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
        Schema::create('fines', function (Blueprint $table) {
            $table->id('fine_id');
            $table->unsignedBigInteger('tdetail_ID'); 
            $table->decimal('fine_amt', 8, 2);
            $table->enum('reason', ['overdue', 'damaged','lost'])->default('overdue');
            $table->enum('fine_status', ['paid', 'unpaid'])->default('unpaid');
            $table->unsignedBigInteger('collected_by')->nullable(); // Admin who collected
            $table->unsignedBigInteger('ftype_id')->nullable();
        
            $table->timestamps();
        
            // Foreign keys
            $table->foreign('tdetail_ID')->references('tdetail_ID')->on('trans_details')->onDelete('cascade');
            $table->foreign('collected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ftype_id')->references('ftype_id')->on('fine_types')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
