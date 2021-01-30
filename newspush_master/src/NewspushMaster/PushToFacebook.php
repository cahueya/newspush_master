<?php
namespace  Concrete\Package\NewspushMaster;
use Symfony\Component\HttpFoundation\JsonResponse;
use Whoops\Exception\ErrorException;
use Concrete\Core\Package\Package;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use GuzzleHttp;
use Core;
use Log;

class PushToFacebook {

    public function push($fb_app_id,$fb_app_secret,$fb_app_url,$fb_app_token,$page_name,$page_content,$publish_path)
    {
        $pkg = Package::getByHandle('newspush_master');
        $u = new User();
        $uid = $u->getUserID();
        $user = UserInfo::getByID($uid);
        
        $fb_message= urlencode(strip_tags($page_content)); 
        $fb_app_long_page_token = $user->getAttribute('user_fb_app_long_page_token');
        $fb_app_pageid = $user->getAttribute('user_fb_app_pageid');

        try{
        	$params = 'https://graph.facebook.com/'.$fb_app_pageid.'/feed?message='.$fb_message.'&access_token='.$fb_app_long_page_token;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST =>false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => array('content-Type: application/x-www-form-urlencoded'),
                CURLOPT_URL => $params
            ));
            $response = curl_exec($curl);
            if(!$response){die("Connection Failure");}
            curl_close($curl);
            $data = json_decode($response, true);
        } catch (\RuntimeException $e) {
                Log::addWarning(t('Something went wrong.'.$data));
        }
    }
}	




