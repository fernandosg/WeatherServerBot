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
      if($this->msn->isPostbackLike("TAMPICO_WEATHER_DELIVERING")){
        if($last_talk["is_closed"])
          $this->sendWeatherMessage("Tampico",1,false);
        else
          $this->fireNotClosedTalk($last_talk["last_message"]);
      }else if($this->msn->isPostbackLike("CDMADERO_WEATHER_DELIVERING")){
        if($last_talk["is_closed"])
          $this->sendWeatherMessage("Ciudad Madero",2,false);
        else
          $this->fireNotClosedTalk($last_talk["last_message"]);
      }else if($this->msn->isPostbackLike("ALTAMIRA_WEATHER_DELIVERING")){
        $this->sendWeatherMessage("Altamira",3,false);
      }else if($this->msn->isPostbackLike("STOP_TALKING")){
        $this->NOT_ACCEPTING();
      }else if($this->msn->isPostbackLike("TODAY_WEATHER")){
        $this->msn->sendMessage("The weather today is ");
      }else if($this->msn->isPostbackLike("TOMORROW_WEATHER")){
        $this->msn->sendMessage("The weather tomorrow is");
      }
    }else if($this->msn->isTheUser() && $this->msn->getMessage()=="Hi"){
      $this->sendMessageWithMultipleButtons();
    }else{
      $this->msn->sendMessage("I dont understand sorry =/, ¿do you want something? please say Hi.");
    }
  }

  public function sendMessageWithMultipleButtons(){
    $this->msn->sendMessageWithMultipleButtons("Hi my name is Bob, this is a test for checking the API FbMessenger, my function is to deliver to you the weather in some local cities from México, ¿what city do you select? ",array(array("type"=>"postback","title"=>"Tampico","payload"=>"TAMPICO_WEATHER_DELIVERING"),array("type"=>"postback","title"=>"Cd Madero","payload"=>"CDMADERO_WEATHER_DELIVERING"),array("type"=>"postback","title"=>"Altamira","payload"=>"ALTAMIRA_WEATHER_DELIVERING")));
    $this->BotCommunication->saveCommunication(1,true);
  }

  public function sendMessageForDays(){
    $this->msn->sendMessageWithMultipleButtons("¡Great!, ¿which day do you want to know the weather? ",array(array("type"=>"postback","title"=>"Today","payload"=>"TODAY_WEATHER"),array("type"=>"postback","title"=>"Tomorrow","payload"=>"TOMORROW_WEATHER")));
    $this->BotCommunication->saveCommunication(4,false);
  }

  public function sendWeatherMessage($city,$option,$is_conclude){
    $this->sendMessageForDays();
    $this->BotCommunication->saveCommunication($option,$is_conclude);
  }



  public function fireNotClosedTalk($talk){
    switch ($talk) {
      case 4:
        $this->msn->sendMessage("Mmmm you forgot to conclude the talk");
        // In each city should be display the message for receive the date.
      break;
      default:
      break;
    }
  }
}
