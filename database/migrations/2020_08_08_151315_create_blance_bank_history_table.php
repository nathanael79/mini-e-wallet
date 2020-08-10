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
            $table->foreign('balance_bank_id')->references('id')->on('blance_bank')->onDelete('cascade');
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
        Schema::dropIfExists('blance_bank_history');
    }
}
