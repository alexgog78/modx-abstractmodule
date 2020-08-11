<?php

abstract class AbstractMgrController extends modExtraManagerController
{
    /** @var bool */
    protected $loadService = true;

    /** @var bool */
    protected $loadLexicon = true;

    /** @var bool */
    protected $loadRichText = false;

    /** @var AbstractModule */
    protected $service;

    /** @var array */
    protected $languageTopics = [];

    /** @var string */
    protected $recordClassKey = null;

    /** @var string */
    protected $recordPrimaryKey = 'id';

    /** @var xPDOObject|null */
    protected $record = null;

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
        if ($this->loadRichText) {
            $this->loadRichTextEditor();
        }
        parent::initialize();
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        if ($this->loadLexicon) {
            $this->languageTopics[] = $this->namespace . ':default';
        }
        return $this->languageTopics;
    }

    /**
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = [])
    {
        if ($this->recordClassKey) {
            $this->getRecord($scriptProperties);
        }

    }

    public function loadCustomCssJs()
    {
        if ($this->loadService) {
            $this->service->loadDefaultMgrAssets($this);
        }
    }

    /**
     * TODO what for?
     * @param $key
     * @param array $placeholders
     * @return string|null
     */
    protected function getLexiconTopic($key, $placeholders = [])
    {
        return $this->modx->lexicon($this->namespace . '_' . $key, $placeholders);
    }

    private function getService()
    {
        $this->service = $this->modx->getService($this->namespace, $this->namespace, $this->namespace_path . '/model/' . $this->namespace . '/');
    }

    /**
     * @param array $scriptProperties
     * @return bool
     */
    private function getRecord($scriptProperties = [])
    {
        $primaryKey = $scriptProperties[$this->recordPrimaryKey];

        //Check request for primary key
        if (empty($primaryKey) || strlen($primaryKey) !== strlen((integer)$primaryKey)) {
            $this->failure($this->modx->lexicon($this->namespace . '.err_ns'));
            return false;
        }

        //Check for record
        $this->record = $this->modx->getObject($this->recordClassKey, [
            $this->recordPrimaryKey => $primaryKey,
        ]);
        if (!$this->record) {
            $this->failure($this->modx->lexicon($this->namespace . '.err_nf'));
            return false;
        }
    }

    //TODO check
    /*protected function loadCodeEditor($fields = [])
    {
        if (empty($fields)) {
            return;
        }
        $ace = $this->modx->getService('ace', 'Ace', $this->modx->getOption('ace.core_path', null, $this->modx->getOption('core_path') . 'components/ace/') . 'model/ace/');
        if (!$ace) {
            return;
        }
        $ace->initialize();
        $html_elements_mime = $this->modx->getOption('ace.html_elements_mime', null, false);

        //$field = 'modx-template-content';
        $modxTags = true;
        $mimeType = 'text/x-smarty';

        $script = [];
        foreach ($fields as $field) {
            $script[] = "MODx.ux.Ace.replaceComponent('$field', '$mimeType', $modxTags);";
        }
        $this->addHtml('<script>Ext.onReady(function() {' . implode(PHP_EOL, $script) . '});</script>');
    }*/


    private function loadRichTextEditor() {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');
        if ($useEditor && !empty($whichEditor))
        {
            // invoke the OnRichTextEditorInit event
            $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit',array(
                'editor' => $whichEditor, // Not necessary for Redactor
                'elements' => array('foo'), // Not necessary for Redactor
            ));
            if (is_array($onRichTextEditorInit))
            {
                $onRichTextEditorInit = implode('', $onRichTextEditorInit);
            }
            $this->setPlaceholder('onRichTextEditorInit', $onRichTextEditorInit);
        }
    }
}
