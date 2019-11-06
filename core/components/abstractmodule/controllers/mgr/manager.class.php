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
        $this->module->addBackendAssets($this);
    }

    abstract protected function getService();

    /**
     * @param $key
     * @param array $placeholders
     * @return string|null
     */
    protected function getLexicon($key, $placeholders = [])
    {
        return $this->modx->lexicon($this->module->objectType . '.' . $key, $placeholders);
    }

    /**
     * @param array $scriptProperties
     * @return void
     */
    protected function checkForRecord($scriptProperties = [])
    {
        $primaryKey = $scriptProperties[$this->recordPrimaryKey];

        //Check request for primary key
        if (empty($primaryKey) || strlen($primaryKey) !== strlen((integer) $primaryKey)) {
            $this->failure($this->modx->lexicon( $this->module->objectType . '.err_ns'));
        }

        //Check for record
        $this->record = $this->modx->getObject($this->recordClassKey, [
            $this->recordPrimaryKey => $primaryKey
        ]);
        if (!$this->record) {
            $this->failure($this->modx->lexicon($this->module->objectType . '.err_nf'));
        }
    }

    /**
     * @return bool
     */
    protected function loadRichTextEditor() {
        $richTextEditor = $this->modx->getOption('which_editor');
        if (!$this->modx->getOption('use_editor') || empty($richTextEditor)) {
            return false;
        }
        $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
            'editor' => $richTextEditor
        ]);
        if(!is_array($onRichTextEditorInit)) {
            return false;
        }
        $onRichTextEditorInit = implode('', $onRichTextEditorInit);
        $this->addHtml($onRichTextEditorInit);
    }
}
