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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('name');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('contact_num');
            $table->enum('role', ['admin', 'member', 'librarian'])->default('member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['first_name', 'last_name', 'contact_num', 'role']);
            $table->string('name');
        });
    }
};
