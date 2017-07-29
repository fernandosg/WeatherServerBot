<?php
namespace App;
class BotCommunication{
  var $sender_id;
  var $m;
  var $m_message;
  const MODEL = "App\Chats";
  const MODEL_MESSAGE="App\Messages";
  public function __construct($sender_id){
    $this->sender_id=$sender_id;
    $this->m=self::MODEL;
    $this->m_message=self::MODEL_MESSAGE;
  }

  public function saveCommunication($type_message){
    $chat=$this->m::where("fb_id",$this->sender_id)->first();
    if($chat!=null){
      $message=$m->message;
      $message->last_message=$type_message;
      $message->boolean=false;
    }else{
      $message=$this->m_message::create(["last_message"=>$type_message,"status"=>"false"]);
      $this->m::create(["message_id"=>$message->id,"message"=>$type_message,"fb_id"=>$this->sender_id]);
    }
  }
}
?>
