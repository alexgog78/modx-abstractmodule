<?php

abstract class abstractModule
{
    /** @var string|null */
    public $package = null;

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

        //TODO check
        $abstractBasePath = $this->modx->getOption('abstractmodule.core_path', $config, $this->modx->getOption('core_path') . 'components/abstractmodule/');
        $abstractAssetsUrl = $this->modx->getOption('abstractmodule.assets_url', $config, $this->modx->getOption('assets_url') . 'components/abstractmodule/');

        $basePath = $this->modx->getOption($this->package . '.core_path', $config, $this->modx->getOption('core_path') . 'components/' . $this->package . '/');
        $assetsUrl = $this->modx->getOption($this->package . '.assets_url', $config, $this->modx->getOption('assets_url') . 'components/' . $this->package . '/');

        $this->config = array_merge([
            'abstractBasePath' => $abstractBasePath,
            'abstractCorePath' => $abstractBasePath,
            'abstractModelPath' => $abstractBasePath . 'model/',
            'abstractHandlersPath' => $abstractBasePath . 'handlers/',
            'abstractProcessorsPath' => $abstractBasePath . 'processors/',
            'abstractAssetsUrl' => $abstractAssetsUrl,
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
        $file = $trace[1]['file'];
        $line = $trace[1]['line'];
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
        $this->modx->controller->addCss($this->config['abstractСssUrl'] . 'mgr/default.css');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/abstractmodule.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/panel.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/formpanel.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/grid.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/window.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/select.local.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/multiselect.local.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/select.remote.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/multiselect.remote.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/browser.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/util/panel.notice.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/misc/renderer.list.js');
        $this->modx->controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/misc/function.list.js');
        return true;
    }

    /**
     * @return bool
     */
    public function initializeFrontend()
    {
        $configJs = $this->modx->toJSON(array(
            'cssUrl' => $this->config['cssUrl'] . 'web/',
            'jsUrl' => $this->config['jsUrl'] . 'web/',
            'actionUrl' => $this->config['actionUrl']
        ));
        $this->modx->regClientStartupScript(
            '<script type="text/javascript">' . get_class($this) . ' = {config: ' . $configJs . '};</script>',
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
        require_once $this->config['abstractHandlersPath'] . 'handler.class.php';

        $handlers = $this->handlers[$ctx] ?? $this->handlers['default'];
        foreach ($handlers as $className) {
            $classNamespace = get_class($this) . '\Handlers\\' . $className;

            require_once $this->config['handlersPath'] . mb_strtolower($className) . '.class.php';
            $this->$className = new $classNamespace($this, $this->config);
            if (!($this->$className instanceof $classNamespace)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize ' . $classNamespace . ' class');
                return false;
            }
        }
        return true;
    }
}