<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model {

    protected $fillable = ["last_message", "status"];

    protected $dates = [];

    public static $rules = [
        "last_message" => "required",
        "status" => "required",
    ];

    public $timestamps = false;

    // Relationships

}
