<?php

abstract class AbstractManagerController extends modExtraManagerController
{
    /** @var string\bool */
    protected $moduleClass = false;

    /** @var AbstractModule */
    protected $module;

    /** @var array */
    protected $languageTopics = [];

    /** @var string */
    protected $recordClassKey = null;

    /** @var string */
    protected $recordPrimaryKey = 'id';

    /** @var xPDOObject|null */
    protected $record = null;

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
        return array_merge([
            $this->module->objectType . ':default',
        ], $this->languageTopics);
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
    }

    protected function getService()
    {
        $namespace = strtolower($this->moduleClass);
        $this->module = $this->modx->getService($namespace, $this->moduleClass, MODX_CORE_PATH . 'components/' . $namespace . '/model/' . $namespace . '/', [
            'ctx' => $this->modx->context->key,
        ]);
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
     * @return bool
     */
    protected function getRecord($scriptProperties = [])
    {
        $primaryKey = $scriptProperties[$this->recordPrimaryKey];

        //Check request for primary key
        if (empty($primaryKey) || strlen($primaryKey) !== strlen((integer)$primaryKey)) {
            $this->failure($this->modx->lexicon($this->module->objectType . '.err_ns'));
            return false;
        }

        //Check for record
        $this->record = $this->modx->getObject($this->recordClassKey, [
            $this->recordPrimaryKey => $primaryKey,
        ]);
        if (!$this->record) {
            $this->failure($this->modx->lexicon($this->module->objectType . '.err_nf'));
            return false;
        }
    }

    //TODO check
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

    //TODO check
    protected function loadCodeEditor($fields = [])
    {
        if (empty($fields)) {
            return;
        }
        $ace = $this->modx->getService('ace', 'Ace', $this->modx->getOption('ace.core_path', null, $this->modx->getOption('core_path') . 'components/ace/') . 'model/ace/');
        if (!$ace) {
            return;
        }
        $ace->initialize();
        $html_elements_mime=$this->modx->getOption('ace.html_elements_mime',null,false);

        //$field = 'modx-template-content';
        $modxTags = true;
        $mimeType = 'text/x-smarty';

        $script = [];
        foreach ($fields as $field) {
            $script[] = "MODx.ux.Ace.replaceComponent('$field', '$mimeType', $modxTags);";
        }
        $this->addHtml('<script>Ext.onReady(function() {' . implode(PHP_EOL, $script) . '});</script>');
    }
}
