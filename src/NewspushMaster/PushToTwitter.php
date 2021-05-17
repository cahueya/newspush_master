<?php
namespace  Concrete\Package\NewspushMaster;
use Database;
use Core;
use Events;
use Log;
use Loader;
use Abraham\TwitterOAuth\TwitterOAuth;

class PushToTwitter {

    public function push($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret,$page_name,$page_content,$publish_path,$encoded_data,$fullpath)
    {
    $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
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
