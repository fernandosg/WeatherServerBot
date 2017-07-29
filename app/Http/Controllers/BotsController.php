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
      if($msn->isAPostback()){
        if($msn->isPostbackLike("ACCEPTING")){
          $msn->sendMessage("Thanks for accepting");
          $BotCommunication->saveCommunication(2);
        }else if($msn->isPostbackLike("NOT_ACCEPTING")){
          $msn->sendMessage("Maybe the next time =/");
          $BotCommunication->saveCommunication(3);
        }
      }else{
        $msn->sendMessageWithMultipleButtons("This is the message that should be displayed in the box of multiple button selection",array(array("type"=>"postback","title"=>"I am agree","payload"=>"ACCEPTING"),array("type"=>"postback","title"=>"I am not agree","payload"=>"NOT_ACCEPTING")));
      }
    }
}
