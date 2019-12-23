<?php

//TODO Refactor traits
abstract class abstractModule
{
    /** @var string */
    public $objectType = null;

    /** @var array */
    public $handlers = [
        'mgr' => [],
        'default' => []
    ];

    /** @var modX */
    public $modx;

    /** @var array */
    public $config = [];

    /** @var array */
    public $initialized = [];

    /** @var string|null */
    protected $tablePrefix = null;

    /**
     * abstractModule constructor.
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx = &$modx;

        $this->objectType = strtolower(get_class($this));

        //TODO check
        //$abstractBasePath = $this->modx->getOption('abstractmodule.core_path', $config, $this->modx->getOption('core_path') . 'components/abstractmodule/');
        $abstractAssetsUrl = $this->modx->getOption('abstractmodule.assets_url', $config, $this->modx->getOption('assets_url') . 'components/abstractmodule/');

        $basePath = $this->modx->getOption($this->objectType . '.core_path', $config, $this->modx->getOption('core_path') . 'components/' . $this->objectType . '/');
        $assetsUrl = $this->modx->getOption($this->objectType . '.assets_url', $config, $this->modx->getOption('assets_url') . 'components/' . $this->objectType . '/');

        $this->config = array_merge([
            //'abstractBasePath' => $abstractBasePath,
            //'abstractCorePath' => $abstractBasePath,
            //'abstractModelPath' => $abstractBasePath . 'model/',
            //'abstractHandlersPath' => $abstractBasePath . 'handlers/',
            //'abstractProcessorsPath' => $abstractBasePath . 'processors/',
            //'abstractAssetsUrl' => $abstractAssetsUrl,
            'abstractJsUrl' => $abstractAssetsUrl . 'js/',
            'abstractСssUrl' => $abstractAssetsUrl . 'css/',
            //'connectorUrl' => $assetsUrl . 'connector.php',
            //'actionUrl' => $assetsUrl . 'action.php'
        ], [
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

        $this->modx->addPackage($this->objectType, $this->config['modelPath'], $this->tablePrefix);
        $this->modx->lexicon->load($this->objectType . ':default');
    }

    /**
     * @param string $ctx
     * @param array $scriptProperties
     * @return bool
     */
    public function initialize($ctx = 'web', $scriptProperties = [])
    {
        if (isset($this->initialized[$ctx])) {
            return $this->initialized[$ctx];
        }
        $this->config = array_merge($this->config, $scriptProperties);
        $this->config['ctx'] = $ctx;

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
     * @param modManagerController $controller
     */
    public function addLexicon(modManagerController $controller)
    {
        $controller->addLexiconTopic($this->objectType . ':default');
    }

    /**
     * @param string $key
     * @param array $placeholders
     * @return string
     */
    public function getLexiconTopic($key = '', $placeholders = [])
    {
        return $this->modx->lexicon($this->objectType . '.' . $key, $placeholders);
    }

    /**
     * @param mixed $data
     * @param string $level
     */
    public function log($data, $level = 'LOG_LEVEL_ERROR')
    {
        if ($data instanceof xPDOObject) {
            $data = $data->toArray('', false, true, true);
        }
        if (is_array($data)) {
            $data = print_r($data, true);
        }

        $trace = debug_backtrace();
        $file = $trace[0]['file'];
        $line = $trace[0]['line'];
        $this->modx->log(constant('modX::'. $level), $data, '', get_class($this), $file, $line);
    }

    /**
     * @param $eventName
     * @param array $data
     * @return array
     */
    public function invokeEvent($eventName, $data = [])
    {
        $this->modx->event->returnedValues = null;
        $response = [
            'eventOutput' => $this->modx->invokeEvent($eventName, $data),
            'returnedValues' => $this->modx->event->returnedValues
        ];
        return $response;
    }

    /**
     * @return bool
     */
    public function initializeBackend()
    {
        if ($this->modx->controller) {
            $this->addBackendAssets($this->modx->controller);
        }
        return true;
    }

    /**
     * @param modManagerController $controller
     * @return bool
     */
    public function addBackendAssets(modManagerController $controller)
    {
        $controller->addCss($this->config['abstractСssUrl'] . 'mgr/default.css');

        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/abstractmodule.js');

        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/panel.js');
        //$controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/panel.tabs.js');

        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/formpanel.js');
        //$controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/formpanel.tabs.js');

        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/grid.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/window.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/page.js');

        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/select.local.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/multiselect.local.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/select.remote.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/multiselect.remote.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/browser.js');

        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/utils/notice.js');

        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/misc/renderer.list.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/misc/function.list.js');

        $configJs = $this->modx->toJSON($this->config ?? []);
        $controller->addHtml(
            '<script type="text/javascript">' . get_class($this) . '.config = ' . $configJs . ';</script>'
        );
        return true;
    }

    /**
     * @return bool
     */
    public function initializeFrontend()
    {
        $this->addFrontendAssets();
        return true;
    }

    /**
     * @return bool
     */
    public function addFrontendAssets()
    {
        $configJs = $this->modx->toJSON(array(
            'cssUrl' => $this->config['cssUrl'] . 'web/',
            'jsUrl' => $this->config['jsUrl'] . 'web/',
            'actionUrl' => $this->config['actionUrl']
        ));
        $this->modx->regClientStartupScript(
            '<script type="text/javascript">' . get_class($this) . 'Config = ' . $configJs . ';</script>',
            true
        );
        return true;
    }

    /**
     * @param string $ctx
     * @return bool
     */
    private function addHandlers($ctx = 'default')
    {
        $handlers = $this->handlers[$ctx] ?? $this->handlers['default'];
        foreach ($handlers as $key => $className) {
            if (!class_exists($className)) {
                $file = $this->config['handlersPath'] . mb_strtolower($key) . '.class.php';
                if (!is_readable($file)) {
                    $this->log('Could not load ' . $file);
                    return false;
                }
                require_once $file;
            }
            $this->$key = new $className($this, $this->config);
            if (!($this->$key instanceof $className)) {
                $this->log('Could not initialize ' . $className . ' class');
                return false;
            }
        }
        return true;
    }
}
