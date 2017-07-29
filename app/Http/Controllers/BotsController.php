<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
class BotsController extends Controller {

    const MODEL = "App\Chats";
    const FbMessenger = "App\Messenger";
    const BotCommunication = "App\BotCommunication";

    use RESTActions;
    /* Endpoint for getting the messages from webhook call*/
    public function talk(Request $request){
      $m = self::FbMessenger;
      $bot=self::BotCommunication;
      $accesstoken=env('ACCESS_TOKEN', "");
      $page_id=693777000809331;//YOUR PAGE_ID
      $msn=new $m($accesstoken,$page_id,json_decode(file_get_contents('php://input'), true));
      $msn->init();
      $BotCommunication=new $bot($msn->getSender());
      $last_talk=$BotCommunication->isSomeTalkNotClosed();
      if($msn->isAPostback()){
        if($msn->isPostbackLike("ACCEPTING")){
          if($last_talk["is_closed"])
            $this->ACCEPTING($msn,$BotCommunication);
          else
            $this->fireNotClosedTalk($last_talk["last_message"],$msn,$BotCommunication);
        }else if($msn->isPostbackLike("NOT_ACCEPTING")){
          if($last_talk["is_closed"])
            $this->NOT_ACCEPTING($msn,$BotCommunication);
          else
            $this->fireNotClosedTalk($last_talk["last_message"],$msn,$BotCommunication);
        }else if($msn->isPostbackLike("CONTINUE_TALKING")){
          $this->CONTINUE_TALKING($msn,$BotCommunication);
        }else if($msn->isPostbackLike("STOP_TALKING")){
          $this->NOT_ACCEPTING($msn,$BotCommunication);
        }
      }else if($msn->isTheUser()){
        $this->sendMessageWithMultipleButtons($msn,$BotCommunication);
      }
    }

    public function sendMessageWithMultipleButtons($msn,$BotCommunication){
        $msn->sendMessageWithMultipleButtons("This is the message that should be displayed in the box of multiple button selection",array(array("type"=>"postback","title"=>"I am agree","payload"=>"ACCEPTING"),array("type"=>"postback","title"=>"I am not agree","payload"=>"NOT_ACCEPTING"),array("type"=>"postback","title"=>"Lets continue talking","payload"=>"CONTINUE_TALKING")));
        $BotCommunication->saveCommunication(1,true);
    }

    public function NOT_ACCEPTING($msn,$BotCommunication){
        $msn->sendMessage("Maybe the next time =/");
        $BotCommunication->saveCommunication(3,true);
    }

    public function ACCEPTING($msn,$BotCommunication){
      $msn->sendMessage("Thanks for accepting");
      $BotCommunication->saveCommunication(2,true);
    }

    public function CONTINUE_TALKING($msn,$BotCommunication){
        $msn->sendMessage("Lets continue talking");
        $msn->sendMessageWithMultipleButtons("This is the message that should be displayed in the box of multiple button selection",array(array("type"=>"postback","title"=>"I dont want, sorry.","payload"=>"STOP_TALKING"),array("type"=>"postback","title"=>"I dont like the idea","payload"=>"STOP_TALKING")));
        $BotCommunication->saveCommunication(4,false);
    }

    public function fireNotClosedTalk($talk,$msn,$BotCommunication){
      switch ($talk) {
        case 4:
          $msn->sendMessage("Mmmm you forgot to conclude the talk");
          $this->CONTINUE_TALKING($msn,$BotCommunication);
          break;
        default:
          break;
      }
    }
}
