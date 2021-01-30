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

class PushToTwitter {

    public function push($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret,$page_name,$page_content,$publish_path)
    {
        //require_once $this->getPackagePath() . "/vendor/dg/twitter-php/src/Twitter.php";
        $twitter = new \Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
        if (!$twitter->authenticate()) {
            Log::addInfo('Twitter not authenticated');
            die('Invalid credentials for Twitter');
        } else {
            $strippedmessage = strip_tags ($publish_path."\n".$page_content); 
            $message = substr($strippedmessage,0,280);
            $twitter->send($message);
        }
    }
}	




