<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlanceBankHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blance_bank_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('balance_bank_id');
            $table->foreign('balance_bank_id')->references('id')->on('blance_bank');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('activity');
            $table->enum('type',['credit','debit']);
            $table->string('ip');
            $table->string('location');
            $table->string('user_agent');
            $table->string('author');
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
        Schema::dropIfExists('blance_bank_history');
    }
}
