<?php
namespace  Concrete\Package\NewspushMaster;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;
use Log;

class PushToTelegram {

    public function push($telegramBotToken,$telegramChatID,$page_name,$page_content,$encoded_data,$fullpath)
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

        if(!empty($fullpath)) {
            try {      
                $result = Request::sendPhoto([
                    'chat_id' => $telegramChatID,
                    'photo'   => $fullpath,
                    'caption' => $plaintext,
                ]);
            } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            }
        } else {
            try {      
                $result = Request::sendMessage([
                    'chat_id' => $telegramChatID,
                    'text'    => $plaintext,
                ]);
            } catch (Longman\TelegramBot\Exception\TelegramException $e) {

            }
        }
    }
}




