<?php
namespace  Concrete\Package\NewspushMaster;
use Symfony\Component\HttpFoundation\JsonResponse;
use Facebook\FacebookRequestException;
use Facebook\FileUpload\FacebookFile;
use Whoops\Exception\ErrorException;
use Concrete\Core\Package\Package;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use Facebook\Facebook;
use Core;
use Log;

class PushToFacebook {

    public function push($fb_app_id,$fb_app_secret,$fb_app_token,$page_name,$page_content,$encoded_data,$fullpath)
    {
        $pkg = Package::getByHandle('newspush_master');
        $u = new User();
        $uid = $u->getUserID();
        $user = UserInfo::getByID($uid);

        $message = strip_tags(
            html_entity_decode(
                str_ireplace($br,'\n',$page_content),
                ENT_COMPAT,
                "utf-8"
            )
        );

        $fb_app_pageid = $user->getAttribute('user_fb_app_pageid');

        $fb = new Facebook([
           'app_id' => $fb_app_id,
           'app_secret' => $fb_app_secret,
           'default_graph_version' => 'v2.10',
        ]);

        $accessToken = $user->getAttribute('user_fb_app_long_page_token');

        if(empty($fullpath)){   
            try {
            	$endpoint = '/'.$fb_app_pageid.'/feed';
                $params = [
                    'message' => $message,    
                ];
                $response = $fb->post($endpoint, $params, $accessToken, $eTag = null, $graphVersion = 'v2.10');

            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: '.$e->getMessage();
            }
        } else {
            try {
            	$filePath = $fullpath;
                $photo = new FacebookFile($filePath);
                $endpoint = '/'.$fb_app_pageid.'/photos';
                $params = [
                	'source' => $photo,
                    'caption' => $message,    
                ];
                $response = $fb->post($endpoint, $params, $accessToken, $eTag = null, $graphVersion = 'v2.10');
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: '.$e->getMessage();
            }
        }
    }
}
