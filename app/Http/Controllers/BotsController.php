<?php namespace App\Http\Controllers;

class BotsController extends Controller {

    const MODEL = "App\Chats";
    const FbMessenger = "App\Messenger";

    use RESTActions;
    /* Endpoint for getting the messages from webhook call*/
    public function talk(Request $request){
      
    }
}
