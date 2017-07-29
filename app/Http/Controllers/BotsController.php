<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
class BotsController extends Controller {

    const MODEL = "App\Chats";
    const FbMessenger = "App\Messenger";
    const BotCommunication = "App\BotCommunication";
    var $msn;
    var $BotCommunication;
    use RESTActions;
    /* Endpoint for getting the messages from webhook call*/
    public function talk(Request $request){
      $m = self::FbMessenger;
      $bot=self::BotCommunication;
      $accesstoken=env('ACCESS_TOKEN', "");
      $page_id=693777000809331;//YOUR PAGE_ID
      $this->msn=new $m($accesstoken,$page_id,json_decode(file_get_contents('php://input'), true));
      $this->msn->init();
      $this->BotCommunication=new $bot($this->msn->getSender());
      $last_talk=$this->BotCommunication->isSomeTalkNotClosed();
      if($this->msn->isAPostback()){
        if($this->msn->isPostbackLike("ACCEPTING")){
          if($last_talk["is_closed"])
            $this->ACCEPTING();
          else
            $this->fireNotClosedTalk($last_talk["last_message"]);
        }else if($this->msn->isPostbackLike("NOT_ACCEPTING")){
          if($last_talk["is_closed"])
            $this->NOT_ACCEPTING();
          else
            $this->fireNotClosedTalk($last_talk["last_message"]);
        }else if($this->msn->isPostbackLike("CONTINUE_TALKING")){
          $this->CONTINUE_TALKING();
        }else if($this->msn->isPostbackLike("STOP_TALKING")){
          $this->NOT_ACCEPTING();
        }
      }else if($this->msn->isTheUser()){
        $this->sendMessageWithMultipleButtons();
      }
    }

    public function sendMessageWithMultipleButtons(){
        $this->msn->sendMessageWithMultipleButtons("This is the message that should be displayed in the box of multiple button selection",array(array("type"=>"postback","title"=>"I am agree","payload"=>"ACCEPTING"),array("type"=>"postback","title"=>"I am not agree","payload"=>"NOT_ACCEPTING"),array("type"=>"postback","title"=>"Lets continue talking","payload"=>"CONTINUE_TALKING")));
        $this->BotCommunication->saveCommunication(1,true);
    }

    public function NOT_ACCEPTING(){
        $this->msn->sendMessage("Maybe the next time =/");
        $this->BotCommunication->saveCommunication(3,true);
    }

    public function ACCEPTING(){
      $this->msn->sendMessage("Thanks for accepting");
      $this->BotCommunication->saveCommunication(2,true);
    }

    public function CONTINUE_TALKING(){
        $this->msn->sendMessage("Lets continue talking");
        $this->msn->sendMessageWithMultipleButtons("This is the message that should be displayed in the box of multiple button selection",array(array("type"=>"postback","title"=>"I dont want, sorry.","payload"=>"STOP_TALKING"),array("type"=>"postback","title"=>"I dont like the idea","payload"=>"STOP_TALKING")));
        $this->BotCommunication->saveCommunication(4,false);
    }

    public function fireNotClosedTalk($talk){
      switch ($talk) {
        case 4:
          $this->msn->sendMessage("Mmmm you forgot to conclude the talk");
          $this->CONTINUE_TALKING();
          break;
        default:
          break;
      }
    }
}
