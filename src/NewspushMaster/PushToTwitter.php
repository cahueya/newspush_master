<?php
namespace  Concrete\Package\NewspushMaster;
use Database;
use Core;
use Events;
use Log;
use Loader;
use \File;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\User;
use Abraham\TwitterOAuth\TwitterOAuth;

class PushToTwitter {

    public function push($page,$pkg)
    {
        $consumerKey       = $pkg->getConfig()->get('settings.newspusher.tw_consumerKey'      );
        $consumerSecret    = $pkg->getConfig()->get('settings.newspusher.tw_consumerSecret'   );
        $accessToken       = $pkg->getConfig()->get('settings.newspusher.tw_accessToken'      );
        $accessTokenSecret = $pkg->getConfig()->get('settings.newspusher.tw_accessTokenSecret');

        $blocks = $page->getBlocks('Main');
        foreach ($blocks as $block) {
            if ($block->btHandle == 'content') {
                $page_content = $block->getInstance()->getContent();
            }
        }
        $f = $page->getAttribute('thumbnail');
        if (!empty($f)){
                $fileID = $f->getFileID();
                $imgurl = $f->getURL();
                $file = \File::getByID($fileID);
                $fv = $file->getApprovedVersion();
                $path = $fv->getRelativePath();
                $workpath = DIR_BASE;
                $fullpath = $workpath.$path;
        }

        $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
        $connection->setTimeouts(10, 15);
        $content = $connection->get("account/verify_credentials");

        $br = array("<br />","<br>","<br/>");
        $plaintext = strip_tags(
            html_entity_decode(
                str_ireplace($br,'\n',$page_content),
                ENT_COMPAT,
                "utf-8"
            )
        );

        if (!empty($fullpath)) {
            $connection->setTimeouts(10, 15);
            $media = $connection->upload('media/upload', ['media' => $fullpath]);
            $parameters = [
                'status' => $plaintext,
                'media_ids' => implode(',', [$media->media_id_string])
            ];
            $result = $connection->post('statuses/update', $parameters);
        } else {
            $statues = $connection->post("statuses/update", ["status" => $plaintext]);
        }  
    }
}




