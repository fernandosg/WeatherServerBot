<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
class BotsController extends Controller {

  const MODEL = "App\Chats";
  const FbMessenger = "App\Messenger";
  const BotCommunication = "App\BotCommunication";
  const WeatherFetch="App\WeatherFetch";
  var $msn;
  var $BotCommunication;
  var $WeatherFetch;
  use RESTActions;
  /* Endpoint for getting the messages from webhook call*/
  public function talk(Request $request){
    $m = self::FbMessenger;
    $bot=self::BotCommunication;
    $this->WeatherFetch=self::WeatherFetch;
    $accesstoken=env('ACCESS_TOKEN', "");
    $page_id=693777000809331;//YOUR PAGE_ID
    $this->msn=new $m($accesstoken,$page_id,json_decode(file_get_contents('php://input'), true));
    $this->msn->init();
    $this->BotCommunication=new $bot($this->msn->getSender());
    $last_talk=$this->BotCommunication->isSomeTalkNotClosed();
    if($this->msn->isAPostback()){
      $postback=$this->msn->getPostback();
      if(strpos($postback,"WEATHER")!==false){
          $information=explode("_",$postback);
          $this->displayInformationWeather($information,$postback);
      }
    }else if($this->msn->isTheUser() && $this->msn->getMessage()=="Hi"){
      if($last_talk["is_closed"]){
        $this->sendMessageWithMultipleButtons();
      }else{
        $this->msn->sendMessage("¿Is there a talk that ends? ");
        $this->fireNotClosedTalk($last_talk["last_message"],$last_talk["postback"]);
      }
      /*else This should be fired soon.
        $this->fireNotClosedTalk($last_talk["last_message"]);
        */
    }else{
      $this->msn->sendMessage("I dont understand sorry =/, ¿do you want something? please say Hi.");
    }
  }
  // DEBO DE LLAMAR A ESTE METODO
  public function displayInformationWeather($information,$postback){
    if(sizeof($information)>=3){
        $weather=$this->WeatherFetch::getTemperatureByCityOnDay($information[0],env("WEATHER_API_KEY",""),$information[1]);
        $this->msn->sendMessage("The weather ".$information[1]." in ".$information[0]." is ".$weather->main->temp);
        $this->BotCommunication->saveCommunication($this->getCityIndex($information[0])+5,$postback,true);// VER COMO PONERLE EL INDICE
    }else if(sizeof($information)==2){
      $this->sendWeatherMessage($information[0],$this->getCityIndex($information[0]),$postback);// VER COMO PONERLE EL INDICE
    }
  }

  public function sendMessageWithMultipleButtons(){
    $this->msn->sendMessageWithMultipleButtons("Hi my name is Bob, this is a test for checking the API FbMessenger, my function is to deliver to you the weather in some local cities from México, ¿what city do you select? ",array(array("type"=>"postback","title"=>"Tampico","payload"=>"TAMPICO_WEATHER"),array("type"=>"postback","title"=>"Cd Madero","payload"=>"CDMADERO_WEATHER"),array("type"=>"postback","title"=>"Altamira","payload"=>"ALTAMIRA_WEATHER")));
    $this->BotCommunication->saveCommunication(1,"test",true);
  }

  public function sendMessageForDays($city,$option,$postback){
    $this->msn->sendMessageWithMultipleButtons("¡Great!, ¿which day do you want to know the weather? ",array(array("type"=>"postback","title"=>"Today","payload"=>($city."_TODAY_WEATHER")),array("type"=>"postback","title"=>"Tomorrow","payload"=>($city."_TOMORROW_WEATHER"))));
    $this->BotCommunication->saveCommunication($option,$postback,false);
  }

  public function sendWeatherMessage($city,$option,$postback){
    $this->sendMessageForDays($city,$option,$postback);
  }

  public function getCityIndex($city){
    $array=array("TAMPICO","CDMADERO","ALTAMIRA");
    return (array_search($city,$array)+2);
  }

  public function fireNotClosedTalk($talk,$postback){
    if($talk==1){
      $this->sendMessageWithMultipleButtons();
    }else{
      $information=explode("_",$postback);
      $this->displayInformationWeather($information,$postback);
    }
  }
}
