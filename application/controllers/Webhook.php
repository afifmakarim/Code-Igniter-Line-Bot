<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use \LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;

require_once('vendor/autoload.php');
require_once('bot_settings.php');

//require_once("dbconnect.php");

class Webhook extends CI_Controller {

	function __construct() {
        parent::__construct();

	}

	function index() {

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
		//get reply token and message if events is not null
		if (!is_null($events)) {
			$replyToken = $events['events'][0]['replyToken'];
            $message = $events['events'][0]['message']['text'];
		}

		//condition to reply message
		if(strtolower($message) == '/help'){
            $reply = "Command List :\n/help : menampilkan command list bot\n/mulai : memulai permainan\n/skip : skip soal\n/hapus : menghapus permainan\n/keluar : kick bot dari group/room chat";
		} else if(strtolower($message) == 'ey'){
            $reply = "ey";
        }
        
        $this->sendMessage($reply, $replyToken, $bot);
    }
    
    function sendMessage($reply, $replyToken, $bot){

        $textMessageBuilder = new TextMessageBuilder($reply);
		$response = $bot->replyMessage($replyToken, $textMessageBuilder);
		if ($response->isSucceeded()) {
			return;
		}

    }
}
