<?php
namespace  Concrete\Package\NewspushMaster;
use Concrete\Core\Http\ServerInterface;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Events;
use Log;
use Loader;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use Concrete\Core\Page\Collection\Collection;
use Concrete\Core\Page\Page;
use Whoops\Exception\ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;

class PushToConcrete5 {

    public function push($page,$pkg)
    {
        $apiURL             = $pkg->getConfig()->get('settings.newspusher.apiURL'            );
        $api_pagepath       = $pkg->getConfig()->get('settings.newspusher.api_pagepath'      );
        $rest_client_id     = $pkg->getConfig()->get('settings.newspusher.rest_client_id'    );
        $rest_client_secret = $pkg->getConfig()->get('settings.newspusher.rest_client_secret');

        $f = $page->getAttribute('thumbnail');
        if (!empty($f)){
                $fileID = $f->getFileID();
                $file = \File::getByID($fileID);
                $version = $file->getVersionToModify();
                $filetitle = $version->getTitle();
                $encoded_data = base64_encode($file->getFileContents());
        }
        $page_tags = $page->getAttribute('tags');
        $page_name = $page->getCollectionName();
        $page_url = \URL::to($page);
        $page_description = $page->getCollectionDescription();
        
        $blocks = $page->getBlocks('Main');
        foreach ($blocks as $block) {
            if ($block->btHandle == 'content') {
                $page_content = $block->getInstance()->getContent();
            }
        }

        $accessTokenURL     = '/index.php/oauth/2.0/token';
        $endpointURL        = '/index.php/ccm/api/v1/pages/write';

        $params = 'grant_type=client_credentials&client_id='.$rest_client_id.'&client_secret='.$rest_client_secret.'&scope=pages:write';
        $url_params = $apiURL.$accessTokenURL;
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
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array('content-Type: application/x-www-form-urlencoded'),
            CURLOPT_URL => $url_params
        ));
        $firstresponse = curl_exec($curl);
        if(!$firstresponse){die("Connection Failure");}
        curl_close($curl);

        $cleanresponse = json_decode($firstresponse, true);

            if (isset($cleanresponse['token_type'])) {
                $tokentype = $cleanresponse['token_type'];
            }
            if (isset($cleanresponse['expires_in'])) {
                $expires_in = $cleanresponse['expires_in'];
            }
            if (isset($cleanresponse['access_token'])) {
                $access_token = $cleanresponse['access_token'];
            }
        $authorization = "Authorization: Bearer ".$access_token;
        $params_raw = [
            'blogTitle' => $page_name,
            'userID' => '1',
            'blogDesc' => $page_description,
            'blogContent' => $page_content,
            'image' => $encoded_data,
            'filename' => $filetitle
        ];
        $params = json_encode($params_raw);
        $url_params = $apiURL.$endpointURL;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url_params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json' , $authorization
            ),
        ));  
        $secondresponse = curl_exec($curl);
        curl_close($curl);
    }
}	




