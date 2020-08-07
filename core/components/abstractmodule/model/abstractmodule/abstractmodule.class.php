<?php

/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'components/abstractmodule/helpers/abstracthelper.trait.php';

abstract class AbstractModule
{
    use AbstractHelper;

    /** @var modX */
    public $modx;

    /** @var array */
    public $config = [];

    /** @var string|null */
    public $namespace = null;

    /** @var bool */
    protected $loadPackage = true;

    /** @var string|null */
    protected $tablePrefix = null;

    /** @var bool */
    protected $loadLexicon = true;

    /** @var array */
    /*protected $handlers = [
        'default' => [],
        'mgr' => [],
        'web' => [],
    ];*/

    protected $handlersMap = [];

    /** @var array */
    //private $initialized = [];

    /**
     * TODO refactor
     * AbstractModule constructor.
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX & $modx, array $config = [])
    {
        $this->modx = &$modx;

        if (!$this->namespace) {
            $this->namespace = strtolower(get_class($this));
        }

        $abstractBasePath = $this->modx->getOption('abstractmodule.core_path', $config, MODX_CORE_PATH . 'components/abstractmodule/');
        $abstractAssetsUrl = $this->modx->getOption('abstractmodule.assets_url', $config, MODX_ASSETS_URL . 'components/abstractmodule/');
        $config = array_merge($config, [
            'abstractJsUrl' => $abstractAssetsUrl . 'js/',
            'abstractСssUrl' => $abstractAssetsUrl . 'css/',
        ]);
        $this->config = array_merge($config, $this->getConfig());

        if ($this->loadPackage) {
            $this->modx->addPackage($this->namespace, $this->config['modelPath'], $this->tablePrefix);
        }

        if ($this->loadLexicon) {
            $this->modx->lexicon->load($this->namespace . ':default');
        }

        include_once MODX_CORE_PATH . 'components/abstractmodule/handlers/default.class.php';
        include_once MODX_CORE_PATH . 'components/abstractmodule/handlers/mgr.class.php';
        include_once MODX_CORE_PATH . 'components/abstractmodule/handlers/web.class.php';
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (in_array($name, array_keys($this->handlersMap))) {
            $handler = $this->loadHandler($name);
            $this->$name = $handler;
            return $this->$name;
        }
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }

    /**
     * @param modManagerController $controller
     */
    public function loadDefaultMgrAssets(modManagerController $controller)
    {
        $controller->addCss($this->abstractСssUrl . 'mgr/default.css');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/abstractmodule.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/widgets/panel.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/widgets/formpanel.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/widgets/grid.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/widgets/localgrid.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/widgets/window.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/widgets/page.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/combo/select.local.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/combo/multiselect.local.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/combo/select.remote.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/combo/multiselect.remote.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/combo/browser.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/misc/renderer.list.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/misc/function.list.js');
        $controller->addJavascript($this->abstractJsUrl . 'mgr/misc/component.list.js');

        $controller->addCss($this->cssUrl . 'mgr/default.css');
        $controller->addJavascript($this->jsUrl . 'mgr/' . $this->namespace . '.js');
        $configJs = $this->modx->toJSON($this->config ?? []);
        $controller->addHtml('<script type="text/javascript">' . get_class($this) . '.config = ' . $configJs . ';</script>');
    }

    /**
     * @param modManagerController $controller
     */
    public function addMgrLexicon(modManagerController $controller)
    {
        $controller->addLexiconTopic($this->namespace . ':default');
    }

    /**
     * @param array $config
     * @return array
     */
    protected function getConfig($config = [])
    {
        $corePath = $this->modx->getOption($this->namespace . '.core_path', $config, MODX_CORE_PATH . 'components/' . $this->namespace . '/');
        $assetsPath = $this->modx->getOption($this->namespace . '.assets_path', $config, MODX_ASSETS_PATH . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->modx->getOption($this->namespace . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . $this->namespace . '/');
        return array_merge($config, [
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
        ]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function loadHandler(string $name)
    {
        $this->log('load handler: ' . $name);
        $path = $this->handlersMap[$name];
        include_once($this->handlersPath . $path . '.class.php');
        //ms2ExtendHandlerMgrMsCategory
        $className = get_class($this) . 'Handler' . ucfirst($name);
        $this->log('Handler class: ' . $className);
        $handler = new $className($this);
        return $handler;
        /*$this->$name = new $className($this);
        return $this->$name;*/
    }
}
