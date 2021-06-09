<?php
namespace Concrete\Package\NewspushMaster;

use Core;
use Events;
use Package;
use SinglePage;
use Database;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Entity\Attribute\Key\UserKey;
use Concrete\Core\Entity\Attribute\Key\PageKey;
use Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Core\Attribute\Set as AttributeSet;
use Concrete\Core\Page\Type\Type as PageType;
use PageTemplate;
use \Concrete\Core\Page\Type\PublishTarget\Type\Type as PublishTargetType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionKey;
use \Concrete\Core\Page\Type\Composer\LayoutSet as LayoutSet;
use \Concrete\Core\Page\Type\Composer\Control\CorePageProperty\NameCorePageProperty as NameControl;
use \Concrete\Core\Page\Type\Composer\Control\BlockControl as BlockControl;
use \Concrete\Core\Page\Type\Composer\Control\CollectionAttributeControl as AttributeControl;
use BlockType;
use Concrete\Core\Backup\ContentImporter\Importer\Routine\ImportPageTypesBaseRoutine as CoreImportPageTypesBaseRoutine;
use Concrete\Package\NewspushMaster\Backup\ContentImporter\Importer\Routine\ImportPageTypesBaseRoutine;
use Concrete\Package\NewspushMaster\Theme\NewspusherTheme\PageTheme as PageTheme;

class Controller extends Package {
	protected $pkgHandle = 'newspush_master';
	protected $appVersionRequired = '8.4';
	protected $pkgVersion = '0.0.99';
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

		
		
	
        $this->app->bind(CoreImportPageTypesBaseRoutine::class, ImportPageTypesBaseRoutine::class);
        $theme = PageTheme::add('newspusher_theme', $pkg);
        $theme->applyToSite();

        $this->installXml();
        
        SinglePage::add('/dashboard/pages/push',$pkg);
        \Concrete\Core\Job\Job::installByPackage('newspusher_job', $pkg);

        $service = $this->app->make('Concrete\Core\Attribute\Category\CategoryService');
        $categoryEntity = $service->getByHandle('collection');
        $category = $categoryEntity->getController();
        
        $key = $category->getByHandle('push_to_news');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_to_news');
            $key->setAttributeKeyName('Push this Page to News');
            $key = $category->add('boolean', $key, null, $pkg);
            $key = $category->getByHandle('push_to_news');
        }	
        $key = $category->getByHandle('push_date');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_date');
            $key->setAttributeKeyName('Date of the Newspusher Process');
            $key = $category->add('text', $key, null, $pkg);
            $key = $category->getByHandle('push_date');
        }
        $key = $category->getByHandle('push_status_telegram');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_status_telegram');
            $key->setAttributeKeyName('Pushed to Telegram');
            $key = $category->add('text', $key, null, $pkg);
            $key = $category->getByHandle('push_status_telegram');
        } 
        $key = $category->getByHandle('push_status_twitter');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_status_twitter');
            $key->setAttributeKeyName('Pushed to Twitter');
            $key = $category->add('text', $key, null, $pkg);
            $key = $category->getByHandle('push_status_twitter');
        } 
        $key = $category->getByHandle('push_status_facebook');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_status_facebook');
            $key->setAttributeKeyName('Pushed to Facebook');
            $key = $category->add('text', $key, null, $pkg);
            $key = $category->getByHandle('push_status_facebook');
        } 
        $key = $category->getByHandle('push_status_rest');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_status_rest');
            $key->setAttributeKeyName('Pushed to Rest API');
            $key = $category->add('text', $key, null, $pkg);
            $key = $category->getByHandle('push_status_rest');
        } 
        $key = $category->getByHandle('push_status_sendy');
        if (!is_object($key)) {
            $key = new PageKey();
            $key->setAttributeKeyHandle('push_status_sendy');
            $key->setAttributeKeyName('Pushed to Sendy');
            $key = $category->add('text', $key, null, $pkg);
            $key = $category->getByHandle('push_status_sendy');
        }   
        
        self::setPageTypeDefaults();

        

    }


    public function setPageTypeDefaults()
    {
        $service = $this->app->make('Concrete\Core\Attribute\Category\CategoryService');
        $categoryEntity = $service->getByHandle('collection');
        $category = $categoryEntity->getController();
        $key = $category->getByHandle('push_to_news');
        $pageType = PageType::getByHandle('blog_entry');
        $template = $pageType->getPageTypeDefaultPageTemplateObject();
        $pageObj = $pageType->getPageTypePageTemplateDefaultPageObject($template);
        //$pageObj->setAttribute($key,1);
    }

    private function installXml()
    {
        $this->installContentFile('install.xml');
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
        Events::addListener('on_page_version_approve', array($event, 'pageAdd'));

        $al = AssetList::getInstance();
        $al->register('css', 'push', 'css/push.css?v=' . $version, ['version' => $version, 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => false, 'combine' => false], $this);
        $al->register('javascript', 'push', 'js/push.js?v=' . $version, ['version' => $version, 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false], $this);
    }
}
