<?php

require_once dirname(__DIR__) . '/helpers/service.trait.php';
require_once dirname(__DIR__) . '/helpers/assets.trait.php';
require_once dirname(__DIR__) . '/helpers/richtext.trait.php';

abstract class abstractModuleMgrDefaultController extends modExtraManagerController
{
    use abstractModuleControllerHelperService;
    use abstractModuleControllerHelperAssets;
    use abstractModuleControllerHelperRichText;

    /** @var bool */
    protected $loadService = true;

    /** @var bool */
    protected $loadRichText = false;

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
        if ($this->loadService) {
            $this->getService();
        }
        $this->setAssetsVersion();
        if ($this->loadRichText) {
            $this->loadRichTextEditor();
        }
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
        $this->loadAbstractCssJs();
        $this->loadDefaultCssJs();
    }
}
