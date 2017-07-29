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
            $table->integer("last_message");
            $table->boolean("status");
        });
    }

    public function down()
    {
        Schema::drop('chats');
    }
}
