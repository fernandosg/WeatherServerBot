<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model {

    protected $fillable = ["fb_id", "status", "last_message"];

    protected $dates = [];

    public static $rules = [
        "fb_id" => "required",
    ];

    public $timestamps = false;



}
