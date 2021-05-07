<?php
namespace Concrete\Package\NewspushMaster;

use Core;
use Events;
use Package;
use SinglePage;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Entity\Attribute\Key\UserKey;
use Concrete\Core\Entity\Attribute\Key\PageKey;
use Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Core\Attribute\Set as AttributeSet;


class Controller extends Package {
	protected $pkgHandle = 'newspush_master';
	protected $appVersionRequired = '8.4';
	protected $pkgVersion = '0.0.33';

    protected $pkgAutoloaderRegistries = [
        'src/NewspushMaster' => '\Concrete\Package\NewspushMaster',
    ];

	public function getPackageDescription () {
		return t("Push to News");
	}

	public function getPackageName () {
		return t("Newspush Master");
	}

	public function install () {

		$pkg = parent::install();
        SinglePage::add('/dashboard/pages/push',$pkg);

        $service = $this->app->make('Concrete\Core\Attribute\Category\CategoryService');
        $categoryEntity = $service->getByHandle('collection');
        $category = $categoryEntity->getController();

        $key = $category->getByHandle('push_to_news');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_to_news');
            $key->setAttributeKeyName('Push this Page to News');
            $key = $category->add('boolean', $key, null, $pkg);
        }		


        //user attributes for customers
        $uakc = AttributeKeyCategory::getByHandle('user');
        $uakc->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);

        //define attr group, and the different attribute types we'll use
        $custSet = AttributeSet::getByHandle('newspusher_data');
        if (!is_object($custSet)) {
            $custSet = $uakc->addSet('newspusher_data', t('Newspusher Data'), $pkg);
        }

        $text = AttributeType::getByHandle('text');
        $boolean = AttributeType::getByHandle('boolean');

        self::installUserAttribute('user_apiURL', $text, $pkg, $custSet);
        self::installUserAttribute('user_api_pagepath', $text, $pkg, $custSet);
        self::installUserAttribute('user_rest_client_id', $text, $pkg, $custSet);
        self::installUserAttribute('user_rest_client_secret', $text, $pkg, $custSet);
        self::installUserAttribute('user_tw_consumerKey', $text, $pkg, $custSet);
        self::installUserAttribute('user_tw_consumerSecret', $text, $pkg, $custSet);
        self::installUserAttribute('user_tw_accessToken', $text, $pkg, $custSet);
        self::installUserAttribute('user_tw_accessTokenSecret', $text, $pkg, $custSet);
        self::installUserAttribute('user_telegramBotToken', $text, $pkg, $custSet);
        self::installUserAttribute('user_telegramChatID', $text, $pkg, $custSet);
        self::installUserAttribute('user_li_app_secret', $text, $pkg, $custSet);
        self::installUserAttribute('user_li_app_id', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_id', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_secret', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_url', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_pageid', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_token', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_long_token', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_long_page_token', $text, $pkg, $custSet);
        self::installUserAttribute('user_fb_app_long_page_token_expiry', $text, $pkg, $custSet);
        self::installUserAttribute('activate_rest_api', $boolean, $pkg, $custSet);
        self::installUserAttribute('activate_twitter', $boolean, $pkg, $custSet);
        self::installUserAttribute('activate_telegram', $boolean, $pkg, $custSet);
        self::installUserAttribute('activate_facebook', $boolean, $pkg, $custSet);

    }

    public static function installUserAttribute($handle, $type, $pkg, $set, $data = null)
    {
        $app = Application::getFacadeApplication();
        $service = $app->make('Concrete\Core\Attribute\Category\CategoryService');
        $categoryEntity = $service->getByHandle('user');
        $category = $categoryEntity->getController();

        $attr = $category->getByHandle($handle);

        if (!is_object($attr)) {
            $name = Application::getFacadeApplication()->make("helper/text")->unhandle($handle);

            $key = new UserKey();
            $key->setAttributeKeyHandle($handle);
            $key->setAttributeKeyName(t($name));
            $key = $category->add($type, $key, null, $pkg);

            $key->setAttributeSet($set);
        }
    }

    public function uninstall() {
        $pkg = parent::uninstall();
    }

	public function upgrade () {
		$pkg = parent::upgrade();
		$pkg = Package::getByHandle($this->pkgHandle);
	}

    public function on_start() {   
        require $this->getPackagePath() . '/vendor/autoload.php';
        $event = Core::make('Concrete\Package\NewspushMaster\Push');
        Events::addListener('on_page_type_publish', array($event, 'pageAdd'));

        $al = AssetList::getInstance();
        $al->register('css', 'push', 'css/push.css?v=' . $version, ['version' => $version, 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => false, 'combine' => false], $this);
        $al->register('javascript', 'push', 'js/push.js?v=' . $version, ['version' => $version, 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false], $this);






    }
}
