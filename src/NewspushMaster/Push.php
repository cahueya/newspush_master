<?php
namespace  Concrete\Package\NewspushMaster;
use Concrete\Core\Package\Package;
use Events;
use Log;
use \File;
use Core;
use Concrete\Package\NewspushMaster\PushToTelegram;
use Concrete\Package\NewspushMaster\PushToTwitter;
use Concrete\Package\NewspushMaster\PushToConcrete5;
use Concrete\Package\NewspushMaster\PushToFacebook;
use Concrete\Package\NewspushMaster\PushToSendy;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Facade;

class Push {

    public function pageAdd($event)
    {
        $pkg = Package::getByHandle('newspush_master');
        $app = Facade::getFacadeApplication();
        $dh = $app->make('helper/date');
        $page = $event->getPageObject();
        $push_to_news = $page->getAttribute('push_to_news');
        $cv = $event->getCollectionVersionObject();
        $publishdate = $cv->cvPublishDate;
        $time = strtotime($dh->toDB(new \DateTime()));

        
        if (!empty($push_to_news)){ // Check of push_to_news is set to TRUE

            if (is_null($publishdate)){ // Check if page pusblishing is scheduled

                $activate_rest_api = $pkg->getConfig()->get('settings.newspusher.activate_rest_api');
                $activate_telegram = $pkg->getConfig()->get('settings.newspusher.activate_telegram');
                $activate_twitter  = $pkg->getConfig()->get('settings.newspusher.activate_twitter' );
                $activate_facebook = $pkg->getConfig()->get('settings.newspusher.activate_facebook');
                $activate_linkedin = $pkg->getConfig()->get('settings.newspusher.activate_linkedin');
                $activate_sendy    = $pkg->getConfig()->get('settings.newspusher.activate_sendy'   );

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
                        $workpath = getcwd();
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
                $page->setAttribute('push_date',$time);
                if(!empty($activate_twitter)){
                    try { 
                        $push_to_twitter = new PushToTwitter;
                        $push = $push_to_twitter->push($page,$pkg);
                    } catch (\RuntimeException $e) {
                        Log::addWarning('Twitter failed: '.$e);
                        $page->setAttribute('push_status_twitter','2');
                        }
                    $page->setAttribute('push_status_twitter','3');
                }
                
                if(!empty($activate_rest_api)){
                    try { 
                        $push_to_concrete5 = new PushToConcrete5;
                        $push = $push_to_concrete5->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('REST API failed: '.$e);
                        $page->setAttribute('push_status_rest','2');
                        }
                    $page->setAttribute('push_status_rest','3');
                }

                if(!empty($activate_facebook)){
                    try { 
                        $push_to_facebook = new PushToFacebook;
                        $push = $push_to_facebook->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('Facebook failed: '.$e);
                        $page->setAttribute('push_status_facebook','2');
                        }
                    $page->setAttribute('push_status_facebook','3');
                }

                if(!empty($activate_linkedin)){
                    try { 
                        $push_to_linkedin = new PushToLinkedin;
                        $push = $push_to_linkedin->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('LinkedIn failed: '.$e);
                        $page->setAttribute('push_status_linkedin','2');
                        }
                    $page->setAttribute('push_status_linkedin','3');
                }

                if(!empty($activate_sendy)){
                    try { 
                        $push_to_sendy = new PushToSendy;
                        $push = $push_to_sendy->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('Sendy failed: '.$e);
                        $page->setAttribute('push_status_sendy','2');
                        }
                    $page->setAttribute('push_status_sendy','3');
                }

                if(!empty($activate_telegram)){
                    try { 
                        $push_to_telegram = new PushToTelegram;
                        $push = $push_to_telegram->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('Telegram failed: '.$e);
                        $page->setAttribute('push_status_telegram','2');
                        }
                    $page->setAttribute('push_status_telegram','3');
                }
            }
        }
    }

    public function run_job()
    {
        $app = Facade::getFacadeApplication();
        $dh = $app->make('helper/date');
        $time = $dh->toDB(new \DateTime());
        $pkg = Package::getByHandle('newspush_master');

        $activate_rest_api = $pkg->getConfig()->get('settings.newspusher.activate_rest_api');
        $activate_telegram = $pkg->getConfig()->get('settings.newspusher.activate_telegram');
        $activate_twitter  = $pkg->getConfig()->get('settings.newspusher.activate_twitter' );
        $activate_facebook = $pkg->getConfig()->get('settings.newspusher.activate_facebook');
        $activate_linkedin = $pkg->getConfig()->get('settings.newspusher.activate_linkedin');
        $activate_sendy    = $pkg->getConfig()->get('settings.newspusher.activate_sendy'   );

        $list = new \Concrete\Core\Page\PageList();
        $list->filterByPageTypeHandle('blog_entry');
        $list->filterByPushToNews(true);
        $pages = $list->getResults();

        foreach ($pages as $page) {
            if (empty($page->getAttribute('push_date'))){
                
                $page_name        = $page->getCollectionName();
                $page_tags        = $page->getAttribute('tags');
                $page_name        = $page->getCollectionName();
                $page_description = $page->getCollectionDescription();
                $page_path        = $page->getCollectionPath();
                $parent_id        = $page->getCollectionParentID();
                $parent_page      = $page->getByID($parent_id);

                $f = $page->getAttribute('thumbnail');
                if (!empty($f)){
                    $fileID       = $f->getFileID();
                    $imgurl       = $f->getURL();
                    $file         = \File::getByID($fileID);
                    $version      = $file->getVersionToModify();
                    $filetitle    = $version->getTitle();
                    $encoded_data = base64_encode($file->getFileContents());
                    $fv           = $file->getApprovedVersion();
                    $path         = $fv->getRelativePath();
                    $workpath     = getcwd();
                    $fullpath     = $workpath.$path;
                }
                $page_url         = \URL::to($page);
                $parent_path      = $parent_page->getCollectionPath();
                $pure_path        = str_replace ($parent_path,'',$page_path);
                $publish_path     = $apiURL.'/index.php'.$api_pagepath.$pure_path;

                $blocks           = $page->getBlocks('Main');
                foreach ($blocks as $block) {
                    if ($block->btHandle == 'content') {
                        $page_content = $block->getInstance()->getContent();
                    }
                }
                if (!empty($apiURL)){
                $publish_path = $apiURL.'/index.php'.$api_pagepath.$pure_path;
                } else {
                    $publish_path= $page->getCollectionLink();
                }

                $page->setAttribute('push_date',$time);
                if(!empty($activate_twitter)){
                    try { 
                        $push_to_twitter = new PushToTwitter;
                        $push = $push_to_twitter->push($page,$pkg);
                    } catch (\RuntimeException $e) {
                        Log::addWarning('Twitter failed: '.$e);
                        $page->setAttribute('push_status_twitter','2');
                        }
                    $page->setAttribute('push_status_twitter','3');
                }
                
                if(!empty($activate_rest_api)){
                    try { 
                        $push_to_concrete5 = new PushToConcrete5;
                        $push = $push_to_concrete5->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('REST API failed: '.$e);
                        $page->setAttribute('push_status_rest','2');
                        }
                    $page->setAttribute('push_status_rest','3');
                }

                if(!empty($activate_facebook)){
                    try { 
                        $push_to_facebook = new PushToFacebook;
                        $push = $push_to_facebook->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('Facebook failed: '.$e);
                        $page->setAttribute('push_status_facebook','2');
                        }
                    $page->setAttribute('push_status_facebook','3');
                }

                if(!empty($activate_linkedin)){
                    try { 
                        $push_to_linkedin = new PushToLinkedin;
                        $push = $push_to_linkedin->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('LinkedIn failed: '.$e);
                        $page->setAttribute('push_status_linkedin','2');
                        }
                    $page->setAttribute('push_status_linkedin','3');
                }

                if(!empty($activate_sendy)){
                    try { 
                        $push_to_sendy = new PushToSendy;
                        $push = $push_to_sendy->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('Sendy failed: '.$e);
                        $page->setAttribute('push_status_sendy','2');
                        }
                    $page->setAttribute('push_status_sendy','3');
                }

                if(!empty($activate_telegram)){
                    try { 
                        $push_to_telegram = new PushToTelegram;
                        $push = $push_to_telegram->push($page,$pkg);
                        } catch (\RuntimeException $e) {
                        Log::addWarning('Telegram failed: '.$e);
                        $page->setAttribute('push_status_telegram','2');
                        }
                    $page->setAttribute('push_status_telegram','3');
                }
            }
        }
    }
}	




