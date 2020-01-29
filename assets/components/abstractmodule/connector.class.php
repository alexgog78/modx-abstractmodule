<?php

abstract class amConnector
{
    /** @var string */
    private $name = '';

    /** @var string */
    private $class = '';

    /** @var modX */
    private $modx;

    /** @var object */
    private $service;

    /** @var string */
    private $processorsPath = '';

    /**
     * amConnector constructor.
     * @param string $name
     * @param string $class
     */
    public function __construct($name, $class = '')
    {
        /** @noinspection PhpIncludeInspection */
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
        /** @noinspection PhpIncludeInspection */
        require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
        /** @noinspection PhpIncludeInspection */
        require_once MODX_CONNECTORS_PATH . 'index.php';
        /** @var modX $modx */
        $this->modx = $modx;
        $this->name = $name;
        $this->class = $class;
    }

    public function process()
    {
        $this->getService();
        $this->getLexicon();
        $this->getProcessorsPath();
        $this->handleRequest();
    }

    protected function getService()
    {
        $basePath = $this->modx->getOption('core_path') . 'components/' . $this->name . '/model/' . $this->name . '/';
        $this->service = $this->modx->getService($this->name, $this->class, $basePath, []);
    }

    protected function getLexicon()
    {
        $this->modx->lexicon->load($this->name . ':default');
    }

    protected function getProcessorsPath()
    {
        $this->processorsPath = $this->modx->getOption('processorsPath', $this->service->config, MODX_CORE_PATH . 'processors/');
    }

    private function handleRequest()
    {
        /** @var modConnectorRequest $request */
        $request = $this->modx->request;
        $request->handleRequest([
            'processors_path' => $this->processorsPath,
            'location' => '',
        ]);
    }
}
