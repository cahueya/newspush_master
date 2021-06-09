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

    public function push($page,$pkg)
    {
        $fb_app_id     = $pkg->getConfig()->get('settings.newspusher.fb_app_id'    );
        $fb_app_secret = $pkg->getConfig()->get('settings.newspusher.fb_app_secret');
        $fb_app_pageid = $pkg->getConfig()->get('settings.newspusher.fb_app_pageid');
        $fb_app_token  = $pkg->getConfig()->get('settings.newspusher.fb_app_token' );

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
                $workpath = DIR_BASE;
                $fullpath = $workpath.$path;
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
            }
        }

        $message = strip_tags(
            html_entity_decode(
                str_ireplace($br,'\n',$page_content),
                ENT_COMPAT,
                "utf-8"
            )
        );

        $fb_app_pageid = $pkg->getConfig()->get('settings.newspusher.fb_app_pageid');

        $fb = new Facebook([
           'app_id' => $fb_app_id,
           'app_secret' => $fb_app_secret,
           'default_graph_version' => 'v2.10',
        ]);

        $accessToken = $pkg->getConfig()->get('settings.newspusher.fb_app_long_page_token');

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
        $page->setAttribute('push_status_facebook',true);
    }
}
