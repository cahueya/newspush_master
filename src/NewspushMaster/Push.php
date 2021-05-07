<?php
namespace  Concrete\Package\NewspushMaster;
use Concrete\Core\Http\ServerInterface;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Events;
use Log;
use Loader;
use Concrete\Core\Page\Collection\Collection;
use Concrete\Core\Page\Page;
use Whoops\Exception\ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use \File;
use Concrete\Package\NewspushMaster\PushToTelegram;
use Concrete\Package\NewspushMaster\PushToTwitter;
use Concrete\Package\NewspushMaster\PushToConcrete5;
use Concrete\Package\NewspushMaster\PushToFacebook;

use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;

class Push {

    public function pageAdd($event)
    {

        $pkg = Package::getByHandle('newspush_master');
        
        $u = new User();
        $uid = $u->getUserID();
        $ui = UserInfo::getByID($uid);
        /** Twitter **/
        $consumerKey = $ui->getAttribute('user_tw_consumerKey');
        $consumerSecret = $ui->getAttribute('user_tw_consumerSecret');
        $accessToken = $ui->getAttribute('user_tw_accessToken');
        $accessTokenSecret = $ui->getAttribute('user_tw_accessTokenSecret');
        $activate_twitter = $ui->getAttribute('activate_twitter');
        /** Telegram  **/
        $telegramBotToken = $ui->getAttribute('user_telegramBotToken');
        $telegramChatID = $ui->getAttribute('user_telegramChatID');
        $activate_telegram = $ui->getAttribute('activate_telegram');
        /** REST Client **/
        $apiURL = $ui->getAttribute('user_apiURL');
        $api_pagepath = $ui->getAttribute('user_api_pagepath');
        $rest_client_id = $ui->getAttribute('user_rest_client_id');
        $rest_client_secret = $ui->getAttribute('user_rest_client_secret');
        $accessTokenURL = '/index.php/oauth/2.0/token';
        $endpointURL = '/index.php/ccm/api/v1/pages/write';
        $activate_rest_api = $ui->getAttribute('activate_rest_api');
        /** LinkedIn **/
        $li_app_id = $ui->getAttribute('user_li_app_id');
        $li_app_secret = $ui->getAttribute('user_li_app_secret');
        /** Facebook **/
        $fb_app_id = $ui->getAttribute('user_fb_app_id');
        $fb_app_secret = $ui->getAttribute('user_fb_app_secret');
        //$fb_app_url = $ui->getAttribute('user_fb_app_url');
        $fb_app_token = $ui->getAttribute('user_fb_app_token');
        $activate_facebook = $ui->getAttribute('activate_facebook');


        /** Content **/
        $page = $event->getPageObject();
        $push_to_news = $page->getAttribute('push_to_news');

        $f = $page->getAttribute('thumbnail');
        if (!empty($f)){
        $fileID = $f->getFileID();
        $imgurl = $f->getURL();
        $file = \File::getByID($fileID);
        $version = $file->getVersionToModify();
        $filetitle = $version->getTitle();
        //Log::addInfo('Das Bild: '.$imgurl.' Der Titel: '.$filetitle);
        $encoded_data = base64_encode(file_get_contents($imgurl));    
        }
    



        $page_tags = $page->getAttribute('tags');
        $page_name = $page->getCollectionName();
        $page_url = \URL::to($page);
        $page_description = $page->getCollectionDescription();
        $page_path = $page->getCollectionPath();
        $parent_id = $page->getCollectionParentID();
        $parent_page = $page->getByID($parent_id);
        $parent_path = $parent_page->getCollectionPath();
        $pure_path = str_replace ($parent_path,'',$page_path);
        $publish_path = $apiURL.'/index.php'.$api_pagepath.$pure_path;

        if (!empty($apiURL)){
            $publish_path = $apiURL.'/index.php'.$api_pagepath.$pure_path;
        } else {
            $publish_path= $page->getCollectionLink();
        }

        $blocks = $page->getBlocks('Main');
        foreach ($blocks as $block) {
            if ($block->btHandle == 'content') {
                $page_content = $block->getInstance()->getContent();
                if (!empty($page_content)) {
                    echo 'Content: '.$page_content;
                    break;
                }
            }
        }

        if ($push_to_news ==1) {

            if(!empty($apiURL)&&!empty($api_pagepath)&&!empty($rest_client_id)&&!empty($rest_client_secret)&&!empty($activate_rest_api)){

                $push_to_concrete5 = new PushToConcrete5;
                $push = $push_to_concrete5->push($apiURL,$api_pagepath,$rest_client_id,$rest_client_secret,$page_content,$page_name,$page_description,$page_tags,$encoded_data,$filetitle);
            }

            if(!empty($telegramBotToken)&&!empty($telegramChatID)&&!empty($activate_telegram)){

                $push_to_telegram = new PushToTelegram;
                //if(!empty($encoded_data)){
                //    $push = $push_to_telegram->sendPhoto($telegramBotToken,$telegramChatID,$page_name,$page_content,$encoded_data);
                //} else {
                    $push = $push_to_telegram->push($telegramBotToken,$telegramChatID,$page_name,$page_content,$imgurl,$encoded_data,$filetitle,$file);
                //}
            }

            if(!empty($consumerKey)&&!empty($consumerSecret)&&!empty($accessToken)&&!empty($accessTokenSecret)&&!empty($activate_twitter)){

                $push_to_twitter = new PushToTwitter;
                $push = $push_to_twitter->push($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret,$page_name,$page_content,$publish_path,$imgurl,$file);
            }

            if(!empty($fb_app_id)&&!empty($fb_app_secret)&&!empty($fb_app_token)&&!empty($activate_facebook)){

                $push_to_facebook = new PushToFacebook;
                $push = $push_to_facebook->push($fb_app_id,$fb_app_secret,$fb_app_token,$page_name,$page_content,$publish_path,$file);
            }
        }
    }
}	




