<?php

require_once dirname(__DIR__) . '/helpers/assets.trait.php';
require_once dirname(__DIR__) . '/helpers/richtext.trait.php';

abstract class abstractModuleMgrDefaultController extends modExtraManagerController
{
    use abstractModuleControllerHelperAssets;
    use abstractModuleControllerHelperRichText;

    /** @var abstractModule */
    protected $service;

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
        $this->service =  $this->modx->getService($this->namespace, $this->namespace, $this->namespace_path . '/model/');
        if ($this->hasRichText()) {
            $this->loadRichText();
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
        return $this->service->lexicon($this->pageTitle) . ' | ' . $this->modx->lexicon($this->namespace);
    }

    public function loadCustomCssJs()
    {
        $this->service->loadMgrAssets($this);
    }
}
