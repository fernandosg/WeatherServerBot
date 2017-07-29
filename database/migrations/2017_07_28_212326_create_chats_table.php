<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{

    public function up()
    {
        Schema::create('chats', function(Blueprint $table) {
            $table->increments('id');
            $table->string('fb_id');
            $table->integer('message_id')->unsigned();
            $table->foreign('message_id')
                ->references('id')
                ->on('messages');

        });
    }

    public function down()
    {
        Schema::drop('chats');
    }
}
