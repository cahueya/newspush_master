<?php
namespace  Concrete\Package\NewspushMaster;
use Database;
use Core;
use Events;
use Log;
use Loader;
use Concrete\Core\File;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use LinkedIn\Client;
use LinkedIn\Scope;
use LinkedIn\AccessToken;

class PushToLinkedin {

    public function push($page,$page_name,$page_content,$encoded_data,$fullpath)
    {

        $pkg           = Package::getByHandle('newspush_master');
        $u             = new User();
        $uid           = $u->getUserID();
        $user          = UserInfo::getByID($uid);
    	$li_app_id     = $user->getAttribute('user_li_app_id');
        $li_app_secret = $user->getAttribute('user_li_app_secret');

        $client = new Client(
            $li_app_id,
            $li_app_secret
        );

        $redirectUrl = $client->getRedirectUrl();

        $scopes = [
          Scope::READ_LITE_PROFILE, 
          Scope::READ_EMAIL_ADDRESS,
          Scope::SHARE_AS_USER,
          Scope::SHARE_AS_ORGANIZATION,
        ];
        $loginUrl = $client->getLoginUrl($scopes);
        $accessToken = $client->getAccessToken($_GET['code']);





    
    }
}




