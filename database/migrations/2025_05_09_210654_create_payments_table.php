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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('trans_ID');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['cash', 'gcash', 'card', 'check']);
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('collected_by');
            $table->timestamps();

            $table->foreign('trans_ID')->references('trans_ID')->on('transactions');
            $table->foreign('collected_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
