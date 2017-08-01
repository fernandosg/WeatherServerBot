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

  public function saveCommunication($type_message,$postback,$it_concluded){
    $chat=$this->m::where("fb_id",$this->sender_id)->first();
    if($chat!=null){
      $chat->last_message=$type_message;
      $chat->status=$it_concluded;
      $chat->postback=$postback;
      $chat->save();
    }else{
      $this->m::create(["last_message"=>$type_message,"status"=>$it_concluded,"fb_id"=>$this->sender_id,"postback"=>$postback]);
    }
  }

  /* Checking if some talk is not closed, then should be returned the information  */
  public function isSomeTalkNotClosed(){
    $chat=$this->m::where("fb_id",$this->sender_id)->first();
    if($chat!=null){
      return array("is_closed"=>(($chat->status==true)),"last_message"=>$chat->last_message,"postback"=>$chat->postback);
    }
    return array("is_closed"=>true,"last_message"=>1,"postback"=>"");
  }
}
?>
