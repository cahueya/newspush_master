<?php
namespace  Concrete\Package\NewspushMaster;
use Concrete\Core\Http\ServerInterface;
use Concrete\Core\Package\Package;
use Database;
use Core;
use Events;
use Log;
use Loader;
use Concrete\Core\Page\Collection\Collection;
use Concrete\Core\Page\Page;
use Whoops\Exception\ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Abraham\TwitterOAuth\TwitterOAuth;

class PushToTwitter {

    public function push($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret,$page_name,$page_content,$publish_path,$imgurl,$file)
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
        if (!empty($imgurl)) {
            $connection->setTimeouts(10, 15);
            $fv = $file->getApprovedVersion();
            $path = $fv->getRelativePath();
            $workpath = getcwd();
            $fullpath = $workpath.$path;
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




