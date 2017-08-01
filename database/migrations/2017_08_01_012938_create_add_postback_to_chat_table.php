<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddPostbackToChatTable extends Migration
{

    public function up()
    {
        Schema::table("chats",function($table){
          $table->string("postback");
        });
    }

    public function down()
    {
        Schema::table("chats",function($table){
          $table->dropColumn("postback");
        });
    }
}
