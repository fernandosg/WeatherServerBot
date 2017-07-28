<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{

    public function up()
    {
        Schema::create('messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('last_message');
            $table->boolean('status');
            // Constraints declaration

        });
    }

    public function down()
    {
        Schema::drop('messages');
    }
}
