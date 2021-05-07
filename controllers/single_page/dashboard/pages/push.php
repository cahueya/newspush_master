<?php
namespace Concrete\Package\NewspushMaster\Controller\SinglePage\Dashboard\Pages;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Single as SinglePage;
use Concrete\Package\NewspushMaster;
use Concrete\Core\Routing\Redirect;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use Package;
use Core;
use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;
use GuzzleHttp;

use Log;




defined('C5_EXECUTE') or die("Access Denied.");
class Push extends DashboardPageController
{

    public function view() {  
        $pkg = Package::getByHandle('newspush_master');

        $u = new User();
        $uid = $u->getUserID();
        $ui = UserInfo::getByID($uid);
        $tw_consumerKey = $ui->getAttribute('user_tw_consumerKey');
        $tw_consumerSecret = $ui->getAttribute('user_tw_consumerSecret');
        $tw_accessToken = $ui->getAttribute('user_tw_accessToken');
        $tw_accessTokenSecret = $ui->getAttribute('user_tw_accessTokenSecret');
        $telegramBotToken = $ui->getAttribute('user_telegramBotToken');
        $telegramChatID = $ui->getAttribute('user_telegramChatID');
        $apiURL = $ui->getAttribute('user_apiURL');
        $api_pagepath = $ui->getAttribute('user_api_pagepath');
        $rest_client_id = $ui->getAttribute('user_rest_client_id');
        $rest_client_secret = $ui->getAttribute('user_rest_client_secret');
        $li_app_secret = $ui->getAttribute('user_li_app_secret');
        $li_app_id = $ui->getAttribute('user_li_app_id');

        $fb_app_id = $ui->getAttribute('user_fb_app_id');
        $fb_app_secret = $ui->getAttribute('user_fb_app_secret');
        //$fb_app_url = $ui->getAttribute('user_fb_app_url');
        $fb_app_pageid = $ui->getAttribute('user_fb_app_pageid');
        $fb_app_token = $ui->getAttribute('user_fb_app_token');
        $fb_app_long_token = $ui->getAttribute('user_fb_app_long_token');
        $fb_app_long_page_token = $ui->getAttribute('user_fb_app_long_page_token');
        $fb_app_long_page_token_expiry = $ui->getAttribute('user_fb_app_long_page_token_expiry');

        $activate_rest_api = $ui->getAttribute('activate_rest_api');
        $activate_telegram = $ui->getAttribute('activate_telegram');
        $activate_twitter = $ui->getAttribute('activate_twitter');
        $activate_facebook = $ui->getAttribute('activate_facebook');


        $this->set('li_app_secret', $li_app_secret);
        $this->set('li_app_id', $li_app_id);
        $this->set('apiURL', $apiURL);
        $this->set('api_pagepath', $api_pagepath);
        $this->set('rest_client_id', $rest_client_id);
        $this->set('rest_client_secret', $rest_client_secret);
        $this->set('tw_consumerKey', $tw_consumerKey);
        $this->set('tw_consumerSecret', $tw_consumerSecret);
        $this->set('tw_accessToken', $tw_accessToken);
        $this->set('tw_accessTokenSecret', $tw_accessTokenSecret);
        $this->set('telegramBotToken', $telegramBotToken);
        $this->set('telegramChatID', $telegramChatID);
        $this->set('fb_app_id', $fb_app_id);
        $this->set('fb_app_secret', $fb_app_secret);
        //$this->set('fb_app_url', $fb_app_url);
        $this->set('fb_app_pageid', $fb_app_pageid);
        $this->set('fb_app_token', $fb_app_token);
        $this->set('fb_app_long_token', $fb_app_long_token);
        $this->set('fb_app_long_page_token', $fb_app_long_page_token);
        $this->set('fb_app_long_page_token_expiry', $fb_app_long_page_token_expiry);

        $this->set('activate_rest_api', $activate_rest_api);
        $this->set('activate_twitter', $activate_twitter);
        $this->set('activate_telegram', $activate_telegram);
        $this->set('activate_facebook', $activate_facebook);

        $this->requireAsset('css', 'push');
        $this->requireAsset('javascript', 'push');
    }

    public function update_configuration() {
      
      if (!$this->token->validate('perform_update_configuration')) {
            $this->flash('error', $this->token->getErrorMessage());

            return Redirect::to('/dashboard/pages/push');
        }
        
        if ($this->isPost()) {

           $apiURL = $this->post('apiURL');
           $api_pagepath = $this->post('api_pagepath');
           $rest_client_id = $this->post('rest_client_id');
           $rest_client_secret = $this->post('rest_client_secret');
           $tw_consumerKey = $this->post('tw_consumerKey');
           $tw_consumerSecret = $this->post('tw_consumerSecret');
           $tw_accessToken = $this->post('tw_accessToken');
           $tw_accessTokenSecret = $this->post('tw_accessTokenSecret');
           $telegramBotToken = $this->post('telegramBotToken');
           $telegramChatID = $this->post('telegramChatID');
           $li_app_secret = $this->post('li_app_secret');
           $li_app_id = $this->post('li_app_id');

           $fb_app_id = $this->post('fb_app_id');
           $fb_app_secret = $this->post('fb_app_secret');
           //$fb_app_url = $this->post('fb_app_url');
           $fb_app_pageid = $this->post('fb_app_pageid');
           $fb_app_token = $this->post('fb_app_token');

           $activate_rest_api = $this->post('activate_rest_api');
           $activate_twitter = $this->post('activate_twitter');
           $activate_telegram = $this->post('activate_telegram');
           $activate_facebook = $this->post('activate_facebook');

           $pkg = Package::getByHandle('newspush_master');
           $u = new User();
           $uid = $u->getUserID();
           $user = UserInfo::getByID($uid);

           $user->setAttribute('user_tw_consumerKey', $tw_consumerKey);
           $user->setAttribute('user_tw_consumerSecret', $tw_consumerSecret);
           $user->setAttribute('user_tw_accessToken', $tw_accessToken);
           $user->setAttribute('user_tw_accessTokenSecret', $tw_accessTokenSecret);
           $user->setAttribute('user_telegramBotToken', $telegramBotToken);
           $user->setAttribute('user_telegramChatID', $telegramChatID);
           $user->setAttribute('user_apiURL', $apiURL);
           $user->setAttribute('user_api_pagepath', $api_pagepath);
           $user->setAttribute('user_rest_client_id', $rest_client_id);
           $user->setAttribute('user_rest_client_secret', $rest_client_secret);
           $user->setAttribute('user_li_app_secret', $li_app_secret);
           $user->setAttribute('user_li_app_id', $li_app_id);

           $user->setAttribute('user_fb_app_id', $fb_app_id);
           $user->setAttribute('user_fb_app_secret', $fb_app_secret);
           //$user->setAttribute('user_fb_app_url', $fb_app_url);
           $user->setAttribute('user_fb_app_pageid', $fb_app_pageid);
           $user->setAttribute('user_fb_app_token', $fb_app_token);

           $user->setAttribute('activate_rest_api', $activate_rest_api);
           $user->setAttribute('activate_twitter', $activate_twitter);
           $user->setAttribute('activate_telegram', $activate_telegram);
           $user->setAttribute('activate_facebook', $activate_facebook);
                    
           $this->set('message', t("Configuration saved"));
        }

        $this->view();
        
      }
    public function config_saved() {
        $this->set('message', t("Configuration saved"));
        $this->view();
    }


    public function getchatid() {

        $u = new User();
        $uid = $u->getUserID();
        $user = UserInfo::getByID($uid);
        $session = $this->app->make('session');
        $pkg = Package::getByHandle('newspush_master');
        $telegramBotToken = $user->getAttribute('user_telegramBotToken');

        if (!empty($telegramBotToken)){
          try { 
            $params = 'https://api.telegram.org/bot'.$telegramBotToken.'/getUpdates';
            $client = new GuzzleHttp\Client();
            $res = $client->request('GET', $params);
            if ($res->getStatusCode() == 'FAILED') {
              $session->getFlashBag()->add('error', t('Please enter the Bot API token!'));
            } else {
              echo $res->getStatusCode();
              $resbody = $res->getBody();
              $data = json_decode($res->getBody(), true);
              if (!empty($data['result'])){
                $lastentry = max(array_keys($data['result']));
                $lastentrychatid = $data['result'][$lastentry]['channel_post']['chat']['id'];
                $user->setAttribute('user_telegramChatID', $lastentrychatid);
            }else{
              $session->getFlashBag()->add('error', t('Please call your bot in your group or channel!'));
            }
          }
          } catch (\RuntimeException $e) {
            $session->getFlashBag()->add('error', t('Chat ID could not be checked! The server does not respond.'));
          }
        } else {
          $session->getFlashBag()->add('error', t('Please enter the Bot API token!'));
        }
        return $this->app->make(ResponseFactoryInterface::class)->redirect(
            $this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/pages/push#telegram']),
            302
        );
    }


    public function flushtokens(){
            $u = new User();
            $uid = $u->getUserID();
            $user = UserInfo::getByID($uid);
            $user->setAttribute('user_fb_app_long_token', '');
            $user->setAttribute('user_fb_app_long_page_token', '');

            return $this->app->make(ResponseFactoryInterface::class)->redirect(
                $this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/pages/push#facebook']),
            302
        );
    }



    public function getfacebookauth(){

        $u = new User();
        $uid = $u->getUserID();
        $user = UserInfo::getByID($uid);
        $session = $this->app->make('session');
        $pkg = Package::getByHandle('newspush_master');
        $fb_app_token = $user->getAttribute('user_fb_app_token');
        $fb_app_id = $user->getAttribute('user_fb_app_id');
        //$fb_app_url = $user->getAttribute('user_fb_app_url');
        $fb_app_pageid = $user->getAttribute('user_fb_app_pageid');
        $fb_app_secret = $user->getAttribute('user_fb_app_secret');
        $fb_app_long_token = $user->getAttribute('user_fb_app_long_token');
        $fb_app_long_page_token = $user->getAttribute('fb_app_long_page_token');

        if (empty($fb_app_long_page_token)){

            if (empty($fb_app_long_token)){
                try {
                    $authurl = 'https://graph.facebook.com/v9.0/oauth/access_token?grant_type=fb_exchange_token';
                    $client_id = '&client_id='.$fb_app_id;
                    $client_secret = '&client_secret='.$fb_app_secret;
                    $exchange_token = '&fb_exchange_token='.$fb_app_token;
                    $params = $authurl.$client_id.$client_secret.$exchange_token;
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
                        CURLOPT_HTTPHEADER => array('content-Type: application/x-www-form-urlencoded'),
                        CURLOPT_URL => $params
                    ));

                    $response = curl_exec($curl);

                    if(!$response){die("Connection Failure");}
                    curl_close($curl);
                    $data = json_decode($response, true);
                    $logdata = print_r($data,1);
                    Log::addInfo('Response: '.$logdata);
                    $returntoken = $data['access_token'];
                    $user->setAttribute('user_fb_app_long_token', $returntoken);
                } catch (\RuntimeException $e) {
                        $session->getFlashBag()->add('error', t('Extended token could not be generated!'));
                }

            } elseif (empty($fb_app_long_page_token)){
                try {
                    $authurl = 'https://graph.facebook.com/';
                    $params = $authurl.$fb_app_pageid.'?fields=name,access_token&access_token='.$fb_app_long_token;
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
                        CURLOPT_HTTPHEADER => array('content-Type: application/x-www-form-urlencoded'),
                        CURLOPT_URL => $params
                    ));
                    $response = curl_exec($curl);
                    if(!$response){die("Connection Failure");}
                    curl_close($curl);
                    $data = json_decode($response, true);
                    $returntoken = $data['access_token'];
                    $returnexpiry = $data['expires_in'];
                    $user->setAttribute('user_fb_app_long_page_token', $returntoken);
                    $user->setAttribute('user_fb_app_long_page_token_expiry', $returnexpiry);
                } catch (\RuntimeException $e) {
                    $session->getFlashBag()->add('error', t('Chat ID could not be checked! The server does not respond or the token is expired.'));
                }

                } else {
                $session->getFlashBag()->add('error', t('The page access token is already set!'));
            }
        }
        return $this->app->make(ResponseFactoryInterface::class)->redirect(
            $this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/pages/push#facebook']),
            302
        );
    }
}
?>
