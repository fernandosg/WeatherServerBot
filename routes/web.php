<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

/**
 * Routes for resource messages
 */
$app->get('messages', 'MessagesController@all');
$app->get('messages/{id}', 'MessagesController@get');
$app->post('messages', 'MessagesController@add');
$app->put('messages/{id}', 'MessagesController@put');
$app->delete('messages/{id}', 'MessagesController@remove');

/**
 * Routes for resource chats
 */
$app->get('chats', 'ChatsController@all');
$app->get('chats/{id}', 'ChatsController@get');
$app->post('chats', 'ChatsController@add');
$app->put('chats/{id}', 'ChatsController@put');
$app->delete('chats/{id}', 'ChatsController@remove');

/**
 * Routes for resource bot
 */
 $app->post("boot/talk","BotsController@talk");
