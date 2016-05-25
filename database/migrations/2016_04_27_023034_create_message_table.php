<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message',function($table){
               $table->integer('id_user')->unsigned();
               $table->integer('id_receiver')->unsigned();
               $table->text('content');
               $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
               $table->foreign('id_receiver')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('message');
    }
}
