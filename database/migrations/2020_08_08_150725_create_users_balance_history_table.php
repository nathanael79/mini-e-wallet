<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersBalanceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_balance_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_balance_id');
            $table->foreign('user_balance_id')->references('id')->on('users_balance')->onDelete('cascade');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('activity');
            $table->enum('type',['credit','debit']);
            $table->string('ip')->nullable();
            $table->string('location')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('author')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_balance_history');
    }
}
