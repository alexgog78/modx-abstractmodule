<?php

require_once MODX_CORE_PATH . 'components/abstractmodule/helpers/abstractmgrhelper.trait.php';

abstract class AbstractMgrController extends modExtraManagerController
{
    use AbstractMgrHelper;

    /** @var bool */
    protected $loadService = true;

    /** @var AbstractModule */
    protected $service;

    /** @var bool */
    protected $loadRichText = false;

    /** @var bool */
    protected $loadObject = false;

    /** @var string */
    protected $objectClassKey;

    /** @var string */
    protected $objectGetProcessorPath;

    /** @var string */
    protected $objectPrimaryKey = 'id';

    /** @var array */
    protected $object = [];

    /** @var array */
    protected $languageTopics = [
        'default',
        'manager',
        'record',
        'status',
    ];

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
        array_walk($this->languageTopics, function (&$item) {
            $item = $this->namespace . ':' . $item;
        });
        return $this->languageTopics;
    }

    /**
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = [])
    {
        if ($this->loadObject) {
            $this->getRecord($scriptProperties);
        }
    }

    public function loadCustomCssJs()
    {
        $this->loadDefaultMgrAssets($this);
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
        $primaryKey = $scriptProperties[$this->objectPrimaryKey];

        $response = $this->modx->runProcessor($this->objectGetProcessorPath, [
            $this->objectPrimaryKey => $primaryKey,
        ], [
            'processors_path' => $this->service->processorsPath ?? ''
        ]);
        if ($response->isError()) {
            $this->failure($response->getMessage());
        }
        $this->object = $response->getObject();
    }

    private function loadRichTextEditor()
    {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');
        if (!$useEditor || empty($whichEditor)) {
            return;
        }
        $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
            'editor' => $whichEditor,
            'elements' => ['ta'],
        ]);
        if (is_array($onRichTextEditorInit)) {
            $onRichTextEditorInit = implode('', $onRichTextEditorInit);
        }
        $this->setPlaceholder('onRichTextEditorInit', $onRichTextEditorInit);
        $this->addHtml($onRichTextEditorInit);
    }
}
