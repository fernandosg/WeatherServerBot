<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model {

    protected $fillable = ["fb_id", "message_id"];

    protected $dates = [];

    public static $rules = [
        "fb_id" => "required",
        "message" => "unsigned",
        "message_id" => "required|numeric",
    ];

    public $timestamps = false;

    public function message()
    {
        return $this->belongsTo("App\Message");
    }


}
