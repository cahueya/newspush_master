<?php
namespace  Concrete\Package\NewspushMaster;
use Symfony\Component\HttpFoundation\JsonResponse;
use Facebook\FacebookRequestException;
use Facebook\FileUpload\FacebookFile;
use Whoops\Exception\ErrorException;
use Concrete\Core\Package\Package;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use Core;
use Log;
use AhmadAwais\Sendy\API as Sendy;

class PushToSendy {

    public function push($page,$pkg)
    {
        $sendy_url             = $pkg->getConfig()->get('settings.newspusher.sendy_url'            );
        $sendy_apikey          = $pkg->getConfig()->get('settings.newspusher.sendy_apikey'         );
        $sendy_listid          = $pkg->getConfig()->get('settings.newspusher.sendy_listid'         );
        $sendy_name_from       = $pkg->getConfig()->get('settings.newspusher.sendy_name_from'      );
        $sendy_email_from      = $pkg->getConfig()->get('settings.newspusher.sendy_email_from'     );
        $sendy_email_reply     = $pkg->getConfig()->get('settings.newspusher.sendy_email_reply'    );
        $sendy_template_header = $pkg->getConfig()->get('settings.newspusher.sendy_template_header');
        $sendy_template_footer = $pkg->getConfig()->get('settings.newspusher.sendy_template_footer');


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

        $html_message = $sendy_template_header.$page_content.$sendy_template_footer;

        $config = [
            'sendyUrl' => $sendy_url,
            'apiKey'   => $sendy_apikey,
            'listId'   => $sendy_listid,
        ];
        
        $sendy = new Sendy($config);
       // if(empty($encoded_data)){   
            try {

                $responseArray = $sendy->campaign(
                    array(
                        'from_name'            => $sendy_name_from,
                        'from_email'           => $sendy_email_from,
                        'reply_to'             => $sendy_email_reply,
                        'title'                => $page_name, // the title of your campaign.
                        'subject'              => $page_name,
                        //'plain_text'           => $message, // Optional.
                        'html_text'            => $html_message,
                        //'brand_id'             => 0, // Required only if you are creating a 'Draft' campaign. That is `send_campaign` set to 0.
                        'send_campaign'        => 1, // SET: Draft = 0 and Send = 1 for the campaign.
                        // Required only if you set send_campaign to 1 and no `segment_ids` are passed in.. List IDs should be single or comma-separated.
                        'list_ids'             => $sendy_listid,
                        // Required only if you set send_campaign to 1 and no `list_ids` are passed in. Segment IDs should be single or comma-separated.
                        //'segment_ids'          => '1',
                        // Lists to exclude. List IDs should be single or comma-separated. (optional).
                        //'exclude_list_ids'     => '',
                        // Segments to exclude. Segment IDs should be single or comma-separated. (optional).
                        //'exclude_segments_ids' => '',
                        //'query_string'         => 'XXXXXXXX', // Eg. Google Analytics tags.
                    )
                );

                echo $responseArray;

//        //} else {
        //    try {
           } catch(Whoops\Exception\ErrorException $responseArray) {

           }
        //}
    }
}
