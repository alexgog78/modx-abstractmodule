<?php

if (!trait_exists('amHelper')) {
    require_once MODX_CORE_PATH . 'components/abstractmodule/helpers/amhelper.trait.php';
}

abstract class abstractModule
{
    use amHelper;

    /** @var modX */
    public $modx;

    /** @var array */
    public $config = [];

    /** @var string */
    public $objectType = null;

    /** @var array */
    protected $handlers = [
        'default' => [],
        'mgr' => [],
        'web' => [],
    ];

    /** @var string|null */
    protected $tablePrefix = null;

    /** @var array */
    private $initialized = [];

    /**
     * abstractModule constructor.
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx = &$modx;
        $this->objectType = strtolower(get_class($this));
        $this->config = $this->getConfig($config);

        $this->modx->addPackage($this->objectType, $this->config['modelPath'], $this->tablePrefix);
        $this->modx->lexicon->load($this->objectType . ':default');

        $ctx = $this->config['ctx'];
        if (!$ctx || $this->initialized[$ctx]) {
            return;
        }
        $this->loadHandlers('default');
        $this->loadHandlers(($ctx != 'mgr') ? 'web' : $ctx);
        $this->initialized[$ctx] = true;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function getConfig($config = [])
    {
        $abstractBasePath = $this->modx->getOption('abstractmodule.core_path', $config, MODX_CORE_PATH . 'components/abstractmodule/');
        $abstractAssetsUrl = $this->modx->getOption('abstractmodule.assets_url', $config, MODX_ASSETS_URL . 'components/abstractmodule/');
        $abstractConfig = [
            'abstractJsUrl' => $abstractAssetsUrl . 'js/',
            'abstractСssUrl' => $abstractAssetsUrl . 'css/',
        ];
        $corePath = $this->modx->getOption($this->objectType . '.core_path', $config, MODX_CORE_PATH . 'components/' . $this->objectType . '/');
        $assetsPath = $this->modx->getOption($this->objectType . '.assets_path', $config, MODX_ASSETS_PATH . 'components/' . $this->objectType . '/');
        $assetsUrl = $this->modx->getOption($this->objectType . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . $this->objectType . '/');
        $moduleConfig = [
            'corePath' => $corePath,
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'handlersPath' => $corePath . 'handlers/',
            'processorsPath' => $corePath . 'processors/',

            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'actionUrl' => $assetsUrl . 'action.php',
        ];
        return array_merge($abstractConfig, $moduleConfig, $config);
    }

    /**
     * @param $folder
     */
    private function loadHandlers($folder)
    {
        $handlers = $this->handlers[$folder];
        if (!$handlers) {
            return;
        }
        foreach ($handlers as $handler) {
            $this->loadHandler($handler, $folder);
        }
    }

    /**
     * @param $handler
     * @param $folder
     * @return bool
     */
    private function loadHandler($handler, $folder)
    {
        $handlerName = $folder . $handler;
        $className = get_class($this) . ucfirst($folder) . $handler . 'Handler';
        $file = $this->config['handlersPath'] . $folder . '/' . mb_strtolower($handler) . '.class.php';
        if (!class_exists($className)) {
            if (!is_readable($file)) {
                $this->log('Could not load handler class: ' . $handler);
                return false;
            }
            require_once $file;
        }
        $this->$handlerName = new $className($this, $this->config);
        if (!($this->$handlerName instanceof $className)) {
            $this->log('Could not initialize handler class: ' . $className);
            return false;
        }
    }
}
