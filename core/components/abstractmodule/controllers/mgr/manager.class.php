<?php

abstract class amManagerController extends modExtraManagerController
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
        $this->module->initializeBackend();
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
            $this->failure($this->modx->lexicon('abstractmodule_err_ns'));
        }

        //Check for record
        $this->record = $this->modx->getObject($this->recordClassKey, ['id' => $primaryKey]);
        if (!$this->record) {
            $this->failure($this->modx->lexicon('abstractmodule_err_nf'));
        }
    }
}