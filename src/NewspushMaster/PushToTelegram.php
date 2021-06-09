<?php
namespace  Concrete\Package\NewspushMaster;

use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;
use Log;

class PushToTelegram {

    public function push($page,$pkg)
    {
        //$pkg = Package::getByHandle('newspush_master');
        //$u                = new User();
        //$uid              = $u->getUserID();
        //$user             = UserInfo::getByID($uid);
        //$telegramBotToken = $user->getAttribute('user_telegramBotToken');
        //$telegramChatID   = $user->getAttribute('user_telegramChatID');

        $telegramBotToken = $pkg->getConfig()->get('settings.newspusher.telegramBotToken');
        $telegramChatID   = $pkg->getConfig()->get('settings.newspusher.telegramChatID'  );

        $bot_username = 'Bot';
        $f = $page->getAttribute('thumbnail');
        if (!empty($f)){
            $fileID = $f->getFileID();
            $imgurl = $f->getURL();
            $file = \File::getByID($fileID);
            $version = $file->getVersionToModify();
            $filetitle = $version->getTitle();
            $encoded_data = base64_encode($file->getFileContents());
            $fv = $file->getApprovedVersion();
            $path = $fv->getRelativePath();
            $workpath = getcwd();
            $fullpath = $workpath.$path;
        }
        $blocks = $page->getBlocks('Main');
        foreach ($blocks as $block) {
            if ($block->btHandle == 'content') {
                $page_content = $block->getInstance()->getContent();
            }
        }

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
            echo $e->getMessage();
            }
        }
    }
}




