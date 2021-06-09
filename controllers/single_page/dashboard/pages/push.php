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
use Facebook\Facebook;
use LinkedIn\AccessToken;
use LinkedIn\Client;
use LinkedIn\Scope;

use Log;

defined('C5_EXECUTE') or die("Access Denied.");
class Push extends DashboardPageController
{

    public function view() {  
        $pkg = Package::getByHandle('newspush_master');

        $tw_consumerKey         = $pkg->getConfig()->get('settings.newspusher.tw_consumerKey'        );
        $tw_consumerSecret      = $pkg->getConfig()->get('settings.newspusher.tw_consumerSecret'     );
        $tw_accessToken         = $pkg->getConfig()->get('settings.newspusher.tw_accessToken'        );
        $tw_accessTokenSecret   = $pkg->getConfig()->get('settings.newspusher.tw_accessTokenSecret'  );
        $telegramBotToken       = $pkg->getConfig()->get('settings.newspusher.telegramBotToken'      );
        $telegramChatID         = $pkg->getConfig()->get('settings.newspusher.telegramChatID'        );
        $apiURL                 = $pkg->getConfig()->get('settings.newspusher.apiURL'                );
        $api_pagepath           = $pkg->getConfig()->get('settings.newspusher.api_pagepath'          );
        $rest_client_id         = $pkg->getConfig()->get('settings.newspusher.rest_client_id'        );
        $rest_client_secret     = $pkg->getConfig()->get('settings.newspusher.rest_client_secret'    );
        $li_app_secret          = $pkg->getConfig()->get('settings.newspusher.li_app_secret'         );
        $li_app_id              = $pkg->getConfig()->get('settings.newspusher.li_app_id'             );
        $linkedinid             = $pkg->getConfig()->get('settings.newspusher.linkedinid'            );
        $linkedintoken          = $pkg->getConfig()->get('settings.newspusher.linkedintoken'         );
        $fb_app_id              = $pkg->getConfig()->get('settings.newspusher.fb_app_id'             );
        $fb_app_secret          = $pkg->getConfig()->get('settings.newspusher.fb_app_secret'         );
        $fb_app_pageid          = $pkg->getConfig()->get('settings.newspusher.fb_app_pageid'         );
        $fb_app_token           = $pkg->getConfig()->get('settings.newspusher.fb_app_token'          );
        $fb_app_long_token      = $pkg->getConfig()->get('settings.newspusher.fb_app_long_token'     );
        $fb_app_long_page_token = $pkg->getConfig()->get('settings.newspusher.fb_app_long_page_token');
        $sendy_url              = $pkg->getConfig()->get('settings.newspusher.sendy_url'             );
        $sendy_apikey           = $pkg->getConfig()->get('settings.newspusher.sendy_apikey'          );
        $sendy_listid           = $pkg->getConfig()->get('settings.newspusher.sendy_listid'          );
        $sendy_name_from        = $pkg->getConfig()->get('settings.newspusher.sendy_name_from'       );
        $sendy_email_from       = $pkg->getConfig()->get('settings.newspusher.sendy_email_from'      );
        $sendy_email_reply      = $pkg->getConfig()->get('settings.newspusher.sendy_email_reply'     );
        $sendy_template_header  = $pkg->getConfig()->get('settings.newspusher.sendy_template_header' );
        $sendy_template_footer  = $pkg->getConfig()->get('settings.newspusher.sendy_template_footer' );
        $activate_rest_api      = $pkg->getConfig()->get('settings.newspusher.activate_rest_api'     );
        $activate_telegram      = $pkg->getConfig()->get('settings.newspusher.activate_telegram'     );
        $activate_twitter       = $pkg->getConfig()->get('settings.newspusher.activate_twitter'      );
        $activate_facebook      = $pkg->getConfig()->get('settings.newspusher.activate_facebook'     );
        $activate_linkedin      = $pkg->getConfig()->get('settings.newspusher.activate_linkedin'     );
        $activate_sendy         = $pkg->getConfig()->get('settings.newspusher.activate_sendy'        );

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
        $this->set('fb_app_pageid', $fb_app_pageid);
        $this->set('fb_app_token', $fb_app_token);
        $this->set('fb_app_long_token', $fb_app_long_token);
        $this->set('fb_app_long_page_token', $fb_app_long_page_token);

        $this->set('li_app_id', $li_app_id);
        $this->set('li_app_secret', $li_app_secret);
        $this->set('linkedinid', $linkedinid);

        $this->set('sendy_url', $sendy_url);
        $this->set('sendy_apikey', $sendy_apikey);
        $this->set('sendy_listid', $sendy_listid);
        $this->set('sendy_name_from', $sendy_name_from);
        $this->set('sendy_email_from', $sendy_email_from);
        $this->set('sendy_email_reply', $sendy_email_reply);
        $this->set('sendy_template_header', $sendy_template_header);
        $this->set('sendy_template_footer', $sendy_template_footer);

        $this->set('activate_rest_api', $activate_rest_api);
        $this->set('activate_twitter', $activate_twitter);
        $this->set('activate_telegram', $activate_telegram);
        $this->set('activate_facebook', $activate_facebook);
        $this->set('activate_linkedin', $activate_linkedin);
        $this->set('activate_sendy', $activate_sendy);

        $this->requireAsset('css', 'push');
        $this->requireAsset('javascript', 'push');
    }

    public function update_configuration() {
      
      if (!$this->token->validate('perform_update_configuration')) {
            $this->flash('error', $this->token->getErrorMessage());

            return Redirect::to('/dashboard/pages/push');
        }
        
        if ($this->isPost()) {

           $apiURL                = $this->post('apiURL'               );
           $api_pagepath          = $this->post('api_pagepath'         );
           $rest_client_id        = $this->post('rest_client_id'       );
           $rest_client_secret    = $this->post('rest_client_secret'   );
           $tw_consumerKey        = $this->post('tw_consumerKey'       );
           $tw_consumerSecret     = $this->post('tw_consumerSecret'    );
           $tw_accessToken        = $this->post('tw_accessToken'       );
           $tw_accessTokenSecret  = $this->post('tw_accessTokenSecret' );
           $telegramBotToken      = $this->post('telegramBotToken'     );
           $telegramChatID        = $this->post('telegramChatID'       );
           $li_app_secret         = $this->post('li_app_secret'        );
           $li_app_id             = $this->post('li_app_id'            );
           $linkedinid            = $this->post('linkedinid'           );
           $fb_app_id             = $this->post('fb_app_id'            );
           $fb_app_secret         = $this->post('fb_app_secret'        );
           //$fb_app_pageid         = $this->post('fb_app_pageid'        );
           $fb_app_token          = $this->post('fb_app_token'         );
           $sendy_url             = $this->post('sendy_url'            );
           $sendy_apikey          = $this->post('sendy_apikey'         );
           $sendy_listid          = $this->post('sendy_listid'         );
           $sendy_name_from       = $this->post('sendy_name_from'      );
           $sendy_email_from      = $this->post('sendy_email_from'     );
           $sendy_email_reply     = $this->post('sendy_email_reply'    );
           $sendy_template_header = $this->post('sendy_template_header');
           $sendy_template_footer = $this->post('sendy_template_footer');
           $activate_rest_api     = $this->post('activate_rest_api'    );
           $activate_twitter      = $this->post('activate_twitter'     );
           $activate_telegram     = $this->post('activate_telegram'    );
           $activate_facebook     = $this->post('activate_facebook'    );
           $activate_linkedin     = $this->post('activate_linkedin'    );
           $activate_sendy        = $this->post('activate_sendy'       );

           $pkg = Package::getByHandle('newspush_master');
           $pkg->getConfig()->save('settings.newspusher.tw_consumerKey',       $tw_consumerKey);
           $pkg->getConfig()->save('settings.newspusher.tw_consumerSecret',    $tw_consumerSecret);
           $pkg->getConfig()->save('settings.newspusher.tw_accessToken',       $tw_accessToken);
           $pkg->getConfig()->save('settings.newspusher.tw_accessTokenSecret', $tw_accessTokenSecret);
           $pkg->getConfig()->save('settings.newspusher.telegramBotToken',     $telegramBotToken);
           $pkg->getConfig()->save('settings.newspusher.telegramChatID',       $telegramChatID);
           $pkg->getConfig()->save('settings.newspusher.apiURL',               $apiURL);
           $pkg->getConfig()->save('settings.newspusher.api_pagepath',         $api_pagepath);
           $pkg->getConfig()->save('settings.newspusher.rest_client_id',       $rest_client_id);
           $pkg->getConfig()->save('settings.newspusher.rest_client_secret',   $rest_client_secret);
           $pkg->getConfig()->save('settings.newspusher.li_app_secret',         $li_app_secret);
           $pkg->getConfig()->save('settings.newspusher.li_app_id',             $li_app_id);
           $pkg->getConfig()->save('settings.newspusher.linkedinid',            $linkedinid);
           $pkg->getConfig()->save('settings.newspusher.fb_app_id',             $fb_app_id);
           $pkg->getConfig()->save('settings.newspusher.fb_app_secret',         $fb_app_secret);
           //$pkg->getConfig()->save('settings.newspusher.fb_app_pageid',         $fb_app_pageid);
           $pkg->getConfig()->save('settings.newspusher.fb_app_token',          $fb_app_token);
           $pkg->getConfig()->save('settings.newspusher.sendy_url',              $sendy_url);
           $pkg->getConfig()->save('settings.newspusher.sendy_apikey',           $sendy_apikey);
           $pkg->getConfig()->save('settings.newspusher.sendy_listid',           $sendy_listid);
           $pkg->getConfig()->save('settings.newspusher.sendy_name_from',        $sendy_name_from);
           $pkg->getConfig()->save('settings.newspusher.sendy_email_from',       $sendy_email_from);
           $pkg->getConfig()->save('settings.newspusher.sendy_email_reply',      $sendy_email_reply);
           $pkg->getConfig()->save('settings.newspusher.sendy_template_header',  $sendy_template_header);
           $pkg->getConfig()->save('settings.newspusher.sendy_template_footer',  $sendy_template_footer);
           $pkg->getConfig()->save('settings.newspusher.activate_rest_api',      $activate_rest_api);
           $pkg->getConfig()->save('settings.newspusher.activate_twitter',       $activate_twitter);
           $pkg->getConfig()->save('settings.newspusher.activate_telegram',      $activate_telegram);
           $pkg->getConfig()->save('settings.newspusher.activate_facebook',      $activate_facebook);
           $pkg->getConfig()->save('settings.newspusher.activate_linkedin',      $activate_linkedin);
           $pkg->getConfig()->save('settings.newspusher.activate_sendy',         $activate_sendy);
                    
           $this->set('message', t("Configuration saved"));
        }
        $this->view();
      }

    public function config_saved() {
        $this->set('message', t("Configuration saved"));
        $this->view();
    }

    public function getchatid() {
        $session = $this->app->make('session');
        $pkg = Package::getByHandle('newspush_master');
        $telegramBotToken = $pkg->getConfig()->get('settings.newspusher.telegramBotToken');

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
                $pkg->getConfig()->save('settings.newspusher.telegramChatID',$lastentrychatid);
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

    public function getlinkedintoken() {

        $u = new User();
        $uid = $u->getUserID();
        $user = UserInfo::getByID($uid);
        $session = $this->app->make('session');
        $pkg = Package::getByHandle('newspush_master');
        
        $li_app_secret = $user->getAttribute('user_li_app_secret');
        $li_app_id = $user->getAttribute('user_li_app_id');
        $linkedintoken = $user->getAttribute('user_linkedintoken');

        $authurl = 'https://www.linkedin.com/oauth/v2/accessToken';
        $params = $authurl.'?grant_type=client_credentials&client_id='.$li_app_id.'&client_secret='.$li_app_secret;
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
        $token_expire = $data['expires_in'];








        $user->setAttribute('user_linkedintoken', $accessToken);
          
        return $this->app->make(ResponseFactoryInterface::class)->redirect(
            $this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/pages/push#linkedin']),
            302
        );
       
    }

    public function getlinkedinid() {

        $u = new User();
        $uid = $u->getUserID();
        $user = UserInfo::getByID($uid);
        $session = $this->app->make('session');
        $pkg = Package::getByHandle('newspush_master');
        $linkedinid = $user->getAttribute('user_linkedinid');
        $li_app_secret = $user->getAttribute('user_li_app_secret');
        $li_app_id = $user->getAttribute('user_li_app_id');



        $client = new Client(
            $li_app_id,
            $li_app_secret
        );
        $profile = $client->get(
            'organizations',
            ['is-company-admin' => true]
        );
        print_r($profile);







          
        return $this->app->make(ResponseFactoryInterface::class)->redirect(
            $this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/pages/push#linkedin']),
            302
        );
       
    }

    public function flushtokens(){

            $pkg = Package::getByHandle('newspush_master');
            $pkg->getConfig()->save('settings.newspusher.fb_app_token',          NULL);
            $pkg->getConfig()->save('settings.newspusher.fb_app_long_token',     NULL);
            $pkg->getConfig()->save('settings.newspusher.fb_app_long_page_token',NULL);

            return $this->app->make(ResponseFactoryInterface::class)->redirect(
                $this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/pages/push#facebook']),
            302
        );
    }

    public function getfacebookauth(){

        $session = $this->app->make('session');
        $pkg = Package::getByHandle('newspush_master');

        $fb_app_id              = $pkg->getConfig()->get('settings.newspusher.fb_app_id'             );
        $fb_app_secret          = $pkg->getConfig()->get('settings.newspusher.fb_app_secret'         );
        $fb_app_pageid          = $pkg->getConfig()->get('settings.newspusher.fb_app_pageid'         );
        $fb_app_token           = $pkg->getConfig()->get('settings.newspusher.fb_app_token'          );
        $fb_app_long_token      = $pkg->getConfig()->get('settings.newspusher.fb_app_long_token'     );
        $fb_app_long_page_token = $pkg->getConfig()->get('settings.newspusher.fb_app_long_page_token');

        	try {
    		$authurl = 'https://graph.facebook.com/me?';
            $params = $authurl.'fields=id,name&access_token='.$fb_app_token;
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
            $page_id = $data['id'];
            $pkg->getConfig()->save('settings.newspusher.fb_app_pageid',$page_id);

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
            $returntoken = $data['access_token'];
            $pkg->getConfig()->save('settings.newspusher.fb_app_long_token',$returntoken);

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

            $pkg->getConfig()->save('settings.newspusher.fb_app_long_page_token',$returntoken);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
            	$session->getFlashBag()->add('error', t('Graph returned an error: '.$e->getMessage()));        
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
            	$session->getFlashBag()->add('error', t('Facebook SDK returned an error: '.$e->getMessage()));         
            }
       
        return $this->app->make(ResponseFactoryInterface::class)->redirect(
            $this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/pages/push#facebook']),
            302
        );
    }
}
?>
