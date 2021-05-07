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
use Facebook\Facebook;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;

class PushToFacebook {

    public function push($fb_app_id,$fb_app_secret,$fb_app_token,$page_name,$page_content,$publish_path,$file)
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




        //if(!empty($file)){
        try {
            $fv = $file->getApprovedVersion();
            $path = $fv->getRelativePath();
            $workpath = getcwd();
            $fullpath = $workpath.$path;
          
            // Upload to a user's profile. The photo will be in the
            // first album in the profile. You can also upload to
            // a specific album by using /ALBUM_ID as the path     
            $response = (new FacebookRequest(
            $session, 'POST', '/'.$fb_app_pageid.'/photos', array(
                  'source' => curl_file_create($fullpath, 'image/png'),
                  'message' => 'User provided message'
                )
            ))->execute()->getGraphObject();
              // If you're not using PHP 5.5 or later, change the file reference to:
              // 'source' => '@/path/to/file.name'
              echo "Posted with id: " . $response->getProperty('id');
            } catch(FacebookRequestException $e) {
              echo "Exception occured, code: " . $e->getCode();
              echo " with message: " . $e->getMessage();
          














            //$ff = $fb->fileToUpload($fullpath);
            // Returns a `Facebook\FacebookResponse` object
            //$response = $fb->post(
            //   '/'.$fb_app_pageid.'/photos',
            //   array (
            //   'url' => 'image-url',
            //   ),
            //   '{access-token}'
            //);
        //} catch(Facebook\Exceptions\FacebookResponseException $e) {
        //  echo 'Graph returned an error: ' . $e->getMessage();
        //  exit;
        //} catch(Facebook\Exceptions\FacebookSDKException $e) {
        //  echo 'Facebook SDK returned an error: ' . $e->getMessage();
        //  exit;
        //}
        //$graphNode = $response->getGraphNode();

    //} else {
        //try{
        //    $endpoint = '/'.$fb_app_pageid.'/feed';
        //    $params = [
        //        'message' => $message
        //    ];
        //    $logparams = print_r($params,1);
        //    Log::addInfo('Data: '.$endpoint.$logparams);
        //    $response = $fb->post($endpoint, $params, $accessToken, $eTag = null, $graphVersion = 'v2.10');
        //    //return $fr = new FacebookResponse;
        //    return $fr;
        //    $logresponse = print_r($fb,1);
        //    Log::addWarning(t('Data: '.$logresponse));
            /*
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

            */
            //} catch(Facebook\Exceptions\FacebookResponseException $e) {
            //    echo 'Graph returned an error: '.$e->getMessage();
            //    exit;
            //} catch(Facebook\Exceptions\FacebookSDKException $e) {
            //    echo 'Facebook SDK returned an error: '.$e->getMessage();
            //    exit;
            //}

            // } catch (\RuntimeException $e) {
            //         Log::addWarning(t('Something went wrong.'.$data));
            // }
        }
    }
}	




