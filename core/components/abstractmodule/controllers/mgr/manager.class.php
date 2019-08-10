<?php

abstract class abstractModuleManagerController extends modExtraManagerController
{
    /** @var abstractModule */
    protected $module;

    /** @var array */
    protected $languageTopics = [];

    /** @var string */
    protected $recordClassKey = null;

    /** @var string */
    protected $recordPrimaryKey = 'id';

    /** @var xPDOObject */
    protected $record;

    /**
     * @return void
     */
    public function initialize()
    {
        $this->getService();
        parent::initialize();
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return $this->languageTopics;
    }

    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        //
        $this->addJavascript($this->module->config['baseJsUrl'] . 'mgr/abstractmodule.js');
        $this->addJavascript($this->module->config['baseJsUrl'] . 'mgr/record.panel.js');

        //Base JS and CSS
        $this->addCss($this->module->config['cssUrl'] . 'mgr/default.css');
        $this->addJavascript($this->module->config['jsUrl'] . 'mgr/ms2bundle.js');
        $this->addJavascript($this->module->config['jsUrl'] . 'mgr/misc/renderer.list.js');
        $this->addJavascript($this->module->config['jsUrl'] . 'mgr/misc/combo.list.js');
        $this->addJavascript($this->module->config['jsUrl'] . 'mgr/misc/function.list.js');

        $configJs = $this->modx->toJSON($this->module->config ?? []);
        $this->addHtml('
            <script type="text/javascript">
                Ext.onReady(function () {
                    ' . get_class($this->module) . '.config = ' . $configJs . ';
                });
            </script>'
        );
    }

    abstract protected function getService();

    /**
     * @param array $scriptProperties
     * @return void
     */
    protected function checkForRecord($scriptProperties = [])
    {
        $primaryKey = $scriptProperties[$this->recordPrimaryKey];

        //Check request for primary key
        if (empty($primaryKey) || strlen($primaryKey) !== strlen((integer) $primaryKey)) {
            $this->failure($this->modx->lexicon($this->module->package . '_err_ns'));
        }

        //Check for record
        $this->record = $this->modx->getObject($this->recordClassKey, ['id' => $primaryKey]);
        if (!$this->record) {
            $this->failure($this->modx->lexicon($this->module->package . '_err_nf'));
        }
    }
}