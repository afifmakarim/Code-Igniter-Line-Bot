<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require_once('vendor/autoload.php');
require_once('bot_settings.php');

//require_once("dbconnect.php");

class Webhook extends CI_Controller {

	function __construct() {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo "Hello Coders!";
			header('HTTP/1.1 400 Only POST method allowed');
			exit;
        } 
        
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
		$bot = new \LINE\LINEBot($httpClient,['channelSecret'=>LINE_MESSAGE_CHANNEL_SECRET]);

		//recieve data from LINE Messaging API
		$content = file_get_contents('php://input');

		//decode json to array
		$events = json_decode($content, true);
	}

	function index() {

		//get reply token and message if events is not null
		if (!is_null($events)) {
			$replyToken = $events['events'][0]['replyToken'];
            $message = $events['events'][0]['message']['text'];
            print_r($events);
		}

		//condition to reply message
		if (preg_match("test", $message)) {
            $reply = "esol";
		} else {
            $reply = "ey";
		}
        $this->sendMessage($reply, $replyToken);
    }
    
    function sendMessage($reply, $replyToken){

        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($reply);
		$response = $bot->replyMessage($replyToken, $textMessageBuilder);
		if ($response->isSucceeded()) {
			echo 'Succeeded!';
			return;
		}else{
        // Failed
		echo $response->getHTTPStatus().' '.$response->getRawBody();
        }

    }
}
