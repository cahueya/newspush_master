<?php
namespace  Concrete\Package\NewspushMaster;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;
//use Telegram;
use GuzzleHttp;
use Log;

class PushToTelegram {

    public function push($telegramBotToken,$telegramChatID,$page_name,$page_content,$imgurl,$encoded_data,$filetitle,$file)
    {

        $bot_username = 'Bot';
        // Create Telegram API object
        $telegram = new Telegram($telegramBotToken, $bot_username);
        $plaintext = $page_name."\n".strip_tags(
            html_entity_decode(
                str_ireplace($br,'\n',$page_content),
                ENT_COMPAT,
                "utf-8"
            )
        );

        if(!empty($imgurl)) {
            try {      
                $result = Request::sendPhoto([
                    'chat_id' => $telegramChatID,
                    'photo'   => Request::encodeFile($imgurl),
                    'caption' => $plaintext,
                ]);
            } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            // log telegram errors
            // echo $e->getMessage();
            }
        } else {
            try {      
                $result = Request::sendMessage([
                    'chat_id' => $telegramChatID,
                    'text'    => $plaintext,
                ]);
            } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            // log telegram errors
            // echo $e->getMessage();
            }







        }
    }



/*

        if (!empty($imgurl)) {

            $chatId = $telegramChatID;
            $caption = $fullmsg;
            //$photo = curl_file_create($imgurl,'image/jpeg',$filetitle);
            //$photo = 'attach://'.$imgurl;
            //$url = 'http://'.$imgurl;
            $fv = $file->getApprovedVersion();
            $path = $fv->getRelativePath();
            $realpath = __DIR__ . $path;


            $imgobj = new \CURLFile($photo = $realpath , $mime_type = 'multipart/form-data' , $posted_filename = 'Title' );
            //$imgobj = new \CURLFile($realpath);
            //$imgobj = 'http://backgroundimages.concrete5.org/wallpaper/20210415.jpg';
          
           
            $logphoto = print_r($imgobj,1);
            Log::addinfo('Obj: '. $logphoto);

            $bot->sendPhoto($chatId,$imgobj,$caption,$replyToMessageId = null,$replyMarkup = null,$disableNotification = false,$parseMode = null);

            return $response;

        } else {
            $bot->sendMessage($telegramChatID, $fullmsg, $parseMode = 'HTML');
        }
    }
/*
    public function sendPhoto(
        $chatId,
        $photo,
        $caption = null,
        $replyToMessageId = null,
        $replyMarkup = null,
        $disableNotification = false,
        $parseMode = null
    )
    {

        if (!empty($encoded_data)) {
        	$bot->sendMessage($telegramChatID,
        		              $fullmsg,
        		              $caption = null,
                              $replyToMessageId = null,
                              $replyMarkup = null,
                              $disableNotification = false,
                              $parseMode = null);
                              };
        $bot->sendMessage($telegramChatID, $fullmsg, $parseMode = 'HTML');
    }
    */
}




