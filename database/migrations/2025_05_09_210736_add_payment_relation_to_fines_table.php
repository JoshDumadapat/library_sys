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
        Schema::table('fines', function (Blueprint $table) {

            $table->unsignedBigInteger('payment_id')->nullable()->after('ftype_id');

            $table->enum('fine_status', ['paid', 'unpaid', 'pending'])
                ->default('unpaid')
                ->change();


            $table->foreign('payment_id')
                ->references('payment_id')
                ->on('payments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('fines', function (Blueprint $table) {

            $table->dropForeign(['payment_id']);


            $table->dropColumn('payment_id');


            $table->enum('fine_status', ['paid', 'unpaid'])
                ->default('unpaid')
                ->change();
        });
    }
};
