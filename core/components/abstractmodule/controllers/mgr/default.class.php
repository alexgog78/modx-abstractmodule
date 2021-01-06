<?php

require_once dirname(__DIR__) . '/helpers/service.trait.php';
require_once dirname(__DIR__) . '/helpers/assets.trait.php';
require_once dirname(__DIR__) . '/helpers/richtext.trait.php';

abstract class abstractModuleMgrDefaultController extends modExtraManagerController
{
    use abstractModuleControllerHelperService;
    use abstractModuleControllerHelperAssets;
    use abstractModuleControllerHelperRichText;

    /** @var string */
    protected $assetsVersion;

    /** @var bool */
    protected $loadRichText = true;

    /** @var array */
    protected $languageTopics = [];

    /** @var string */
    protected $pageTitle = '';

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }

    public function initialize()
    {
        $this->service = $this->getService();
        $this->assetsVersion = $this->getAssetsVersion();
        if ($this->loadRichText) {
            $this->loadRichTextEditor();
        }
    }

    /**
     * @return string
     */
    protected function getAssetsVersion()
    {
        if ($this->service::DEVELOPER_MODE) {
            return time();
        }
        return $this->service::PKG_VERSION . '-' . $this->service::PKG_RELEASE;
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array_merge($this->languageTopics, [
            $this->namespace . ':default',
            $this->namespace . ':status',
        ]);
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon($this->pageTitle) . ' | ' . $this->modx->lexicon($this->namespace);
    }

    public function loadCustomCssJs()
    {
        $this->loadCoreCssJs();
        $this->loadDefaultCssJs();
        $this->addJavascript($this->service->jsUrl . 'mgr/combo/browser.image.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/combo/select.collection.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/combo/select.optionone.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/combo/select.optiontwo.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/combo/multiselect.tag.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/combo/multiselect.category.js');
    }

    /**
     * @param string $script
     */
    public function addCss($script)
    {
        $script .= '?' . $this->assetsVersion;
        parent::addCss($script);
    }

    /**
     * @param string $script
     */
    public function addJavascript($script)
    {
        $script .= '?' . $this->assetsVersion;
        parent::addJavascript($script);
    }

    /**
     * @param string $script
     */
    public function addLastJavascript($script)
    {
        $script .= '?' . $this->assetsVersion;
        parent::addLastJavascript($script);
    }
}
