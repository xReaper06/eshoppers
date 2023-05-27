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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id');
            $table->decimal('amount',10,2);
            $table->string('confirmation_code');
            $table->integer('user_id');
            $table->string('email');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone_number');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('transactions');
    }
};
