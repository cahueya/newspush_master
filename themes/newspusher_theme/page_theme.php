<?php
namespace Concrete\Package\NewspushMaster\Theme\NewspusherTheme;

use Concrete\Core\Area\Layout\Preset\Provider\ThemeProviderInterface;
use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Support\Facade\Package;

class PageTheme extends Theme implements ThemeProviderInterface
{
    /**
     * Image Thumbnails.
     *
     * @var array
     */
    private $themeResponsiveImageMap = [];

    public function __construct()
    {
        parent::__construct();

        $pkg = Package::getByHandle($this->getPackageHandle());
        if (is_object($pkg)) {
            $thumbTypes = $pkg->getFileConfig()->get('config.theme.thumbnail_types', []);
            $this->themeResponsiveImageMap = array_combine(
                array_keys($thumbTypes),
                array_column($thumbTypes, 'media-min-width')
            );
        }
    }

    public function registerAssets()
    {
        $this->providesAsset('css', 'bootstrap/*');
        $this->providesAsset('css', 'blocks/form');
        $this->providesAsset('css', 'blocks/social_links');
        $this->providesAsset('css', 'blocks/share_this_page');
        $this->providesAsset('css', 'blocks/feature');
        $this->providesAsset('css', 'blocks/testimonial');
        $this->providesAsset('css', 'blocks/date_navigation');
        $this->providesAsset('css', 'blocks/topic_list');
        $this->providesAsset('css', 'blocks/faq');
        $this->providesAsset('css', 'blocks/tags');
        $this->providesAsset('css', 'core/frontend/*');
        $this->providesAsset('css', 'blocks/feature/templates/hover_description');

        $this->providesAsset('css', 'blocks/event_list');

        $this->requireAsset('css', 'font-awesome');
        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('javascript', 'picturefill');
        $this->requireAsset('javascript-conditional', 'html5-shiv');
        $this->requireAsset('javascript-conditional', 'respond');
    }

    protected $pThemeGridFrameworkHandle = 'bootstrap3';

    public function getThemeName()
    {
        return t('Newspusher Theme');
    }

    public function getThemeDescription()
    {
        return t('Newspusher Theme');
    }

    /**
     * @return array
     */
    public function getThemeBlockClasses()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getThemeAreaClasses()
    {
        return [
            'Page Footer' => ['area-content-accent'],
        ];
    }

    /**
     * @return array
     */
    public function getThemeDefaultBlockTemplates()
    {
        return [
            'calendar' => 'bootstrap_calendar.php',
        ];
    }

    /**
     * @return array
     */
    public function getThemeResponsiveImageMap()
    {
        return $this->themeResponsiveImageMap;
    }

    /**
     * @return array
     */
    public function getThemeEditorClasses()
    {
        return [
            ['title' => t('Big Text'), 'menuClass' => 'large-text', 'spanClass' => 'large-text', 'forceBlock' => -1],
            ['title' => t('Bigger Text'), 'menuClass' => 'extra-large-text', 'spanClass' => 'extra-large-text', 'forceBlock' => -1],
            ['title' => t('Small text'), 'menuClass' => 'small-text', 'spanClass' => 'small-text', 'forceBlock' => -1],
            ['title' => t('Smaller Text'), 'menuClass' => 'extra-small-text', 'spanClass' => 'extra-small-text', 'forceBlock' => -1],
            ['title' => t('Image Caption'), 'menuClass' => 'image-caption', 'spanClass' => 'image-caption', 'forceBlock' => '-1'],
            ['title' => t('Standard Button'), 'menuClass' => '', 'spanClass' => 'btn btn-default', 'forceBlock' => '-1'],
            ['title' => t('Success Button'), 'menuClass' => '', 'spanClass' => 'btn btn-success', 'forceBlock' => '-1'],
            ['title' => t('Primary Button'), 'menuClass' => '', 'spanClass' => 'btn btn-primary', 'forceBlock' => '-1'],
        ];
    }

    /**
     * @return array
     */
    public function getThemeAreaLayoutPresets()
    {
        return [];
    }
}
