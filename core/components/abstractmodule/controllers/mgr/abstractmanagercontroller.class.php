<?php

abstract class abstractManagerController extends modExtraManagerController
{
    /** @var abstractModule */
    protected $module;

    /** @var array */
    protected $languageTopics = [];

    /** @var string */
    protected $recordClassKey = null;

    /** @var string */
    protected $recordPrimaryKey = 'id';

    /** @var xPDOObject|null */
    protected $record = null;

    /**
     * @return void
     */
    public function initialize()
    {
        $this->getService();
        parent::initialize();
    }

    abstract protected function getService();

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
    }

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
    protected function getRecord($scriptProperties = [])
    {
        $primaryKey = $scriptProperties[$this->recordPrimaryKey];

        //Check request for primary key
        if (empty($primaryKey) || strlen($primaryKey) !== strlen((integer)$primaryKey)) {
            $this->failure($this->modx->lexicon($this->module->objectType . '.err_ns'));
        }

        //Check for record
        $this->record = $this->modx->getObject($this->recordClassKey, [
            $this->recordPrimaryKey => $primaryKey,
        ]);
        if (!$this->record) {
            $this->failure($this->modx->lexicon($this->module->objectType . '.err_nf'));
        }
    }

    protected function loadRichTextEditor()
    {
        $richTextEditor = $this->modx->getOption('which_editor');
        if (!$this->modx->getOption('use_editor') || empty($richTextEditor)) {
            return;
        }
        $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
            'editor' => $richTextEditor,
        ]);
        if (!is_array($onRichTextEditorInit)) {
            return;
        }
        $onRichTextEditorInit = implode('', $onRichTextEditorInit);
        $this->addHtml($onRichTextEditorInit);
    }

    protected function loadCodeEditor()
    {
        $config = [
            'id' => 0,
            'record' => &$this->record,
            'mode' => $this->record ? modSystemEvent::MODE_UPD : modSystemEvent::MODE_NEW,
        ];
        $onTempFormPrerender = $this->modx->invokeEvent('OnTempFormPrerender', $config);
        if (is_array($onTempFormPrerender)) {
            $onTempFormPrerender = implode('', $onTempFormPrerender);
        }
        $this->setPlaceholder('onTempFormPrerender', $onTempFormPrerender);
    }
}