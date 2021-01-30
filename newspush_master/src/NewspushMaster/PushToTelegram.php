<?php
namespace  Concrete\Package\NewspushMaster;

class PushToTelegram {

    public function push($telegramBotToken,$telegramChatID,$page_name,$page_content)
    {

        $bot = new \TelegramBot\Api\BotApi($telegramBotToken);

        $telegram_message_raw = $page_name."\n".$page_content;
        $telegram_message_raw_one=str_ireplace('<p>','',$telegram_message_raw);
        $telegram_message=str_ireplace('</p>','',$telegram_message_raw_one); 

        $bot->sendMessage($telegramChatID, $telegram_message, $parseMode = 'HTML');
    }
}	




