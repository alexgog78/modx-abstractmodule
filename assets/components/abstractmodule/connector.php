<?php

abstract class AbstractConnector
{
    /** @var string */
    protected $serviceName;

    /** @var modX */
    private $modx;

    /** @var AbstractModule */
    private $service;

    public function __construct()
    {
        $this->modx = $this->getModxConnector();
        $this->service = $this->getService();
        $this->loadLexicons();
    }

    public function run()
    {
        $this->handleRequest();
    }

    /**
     * @return modX
     */
    private function getModxConnector()
    {
        /** @noinspection PhpIncludeInspection */
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
        /** @noinspection PhpIncludeInspection */
        require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
        /** @noinspection PhpIncludeInspection */
        require_once MODX_CONNECTORS_PATH . 'index.php';
        return $modx;
    }

    /**
     * @return object|null
     */
    private function getService()
    {
        return $this->modx->getService($this->serviceName, $this->serviceName, MODX_CORE_PATH . 'components/' . $this->serviceName . '/model/' . $this->serviceName . '/', []);
    }

    private function loadLexicons()
    {
        $this->modx->lexicon->load($this->serviceName . ':default');
    }

    private function handleRequest()
    {
        $request = $this->modx->request;
        $processorsPath = $this->modx->getOption('processorsPath', $this->service->config, MODX_CORE_PATH . 'processors/');
        $request->handleRequest([
            'processors_path' => $processorsPath,
            'location' => '',
        ]);
    }
}
