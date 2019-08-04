<?php

abstract class abstractModule
{
    /** @var string|null */
    public $package = null;

    /** @var array */
    public $handlers = [];

    /** @var modX */
    public $modx;

    /** @var array */
    public $config = [];

    /** @var array */
    public $initialized = [];

    /** @var pdoFetch */
    public $pdoTools;

    /**
     * abstractModule constructor.
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx = &$modx;

        $basePath = $this->modx->getOption($this->package . '.core_path', $config, $this->modx->getOption('core_path') . 'components/' . $this->package . '/');
        $assetsUrl = $this->modx->getOption($this->package . '.assets_url', $config, $this->modx->getOption('assets_url') . 'components/' . $this->package . '/');
        $this->config = array_merge([
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath . 'model/',
            'handlersPath' => $basePath . 'handlers/',
            'processorsPath' => $basePath . 'processors/',
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'actionUrl' => $assetsUrl . 'action.php'
        ], $config);

        $this->modx->addPackage($this->package, $this->config['modelPath']);
        $this->modx->lexicon->load($this->package . ':default');

        if ($this->pdoTools = $this->modx->getService('pdoFetch')) {
            $this->pdoTools->setConfig($this->config);
        }
    }

    /**
     * @param string $ctx
     * @param array $scriptProperties
     * @return bool
     */
    public function initialize($ctx = 'web', $scriptProperties = [])
    {
        $this->config = array_merge($this->config, $scriptProperties);
        $this->config['ctx'] = $ctx;
        if ($this->initialized[$ctx]) {
            return true;
        }

        $this->addHandlers($ctx);
        switch ($ctx) {
            case 'mgr':
                $this->initializeBackend();
                break;
            default:
                $this->initializeFrontend();
                break;
        }

        $this->initialized[$ctx] = true;
    }

    /**
     * @param string $message
     * @param array $data
     * @param array $placeholders
     * @return array
     */
    public function success($message = '', $data = [], $placeholders = [])
    {
        return [
            'success' => true,
            'message' => $this->getLexiconTopic($message, $placeholders),
            'data' => $data
        ];
    }

    /**
     * @param string $message
     * @param array $data
     * @param array $placeholders
     * @return array
     */
    public function error($message = '', $data = [], $placeholders = [])
    {
        return [
            'success' => false,
            'message' => $this->getLexiconTopic($message, $placeholders),
            'data' => $data
        ];
    }

    /**
     * @param string $key
     * @param array $placeholders
     * @return string
     */
    public function getLexiconTopic($key = '', $placeholders = [])
    {
        return $this->modx->lexicon($this->package . '.' . $key, $placeholders);
    }

    /**
     * @return bool
     */
    protected function initializeBackend()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function initializeFrontend()
    {
        return true;
    }

    /**
     * @param string $ctx
     * @return bool
     */
    private function addHandlers($ctx = 'default')
    {
        $handlers = $this->handlers[$ctx] ?? $this->handlers['default'];
        foreach ($handlers as $className) {
            require_once $this->config['handlersPath'] . mb_strtolower($className) . '.class.php';
            $classNamespace = get_class($this) . '\Handlers\\' . $className;
            $this->$className = new $classNamespace($this, $this->config);
            if (!($this->$className instanceof $classNamespace)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize ' . $classNamespace . ' class');
                return false;
            }
        }
        return true;
    }
}