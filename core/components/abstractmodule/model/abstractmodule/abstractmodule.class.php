<?php

/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'components/abstractmodule/helpers/abstracthelper.trait.php';
//require_once MODX_CORE_PATH . 'components/abstractmodule/helpers/abstractmgrhelper.trait.php';

abstract class AbstractModule
{
    use AbstractHelper;
    //use AbstractMgrHelper;

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
        $this->config = $this->getConfig($config);

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
     * @param array $config
     * @return array
     */
    protected function getConfig($config = [])
    {
        $corePath = $this->modx->getOption($this->namespace . '.core_path', $config, MODX_CORE_PATH . 'components/' . $this->namespace . '/');
        $assetsPath = $this->modx->getOption($this->namespace . '.assets_path', $config, MODX_ASSETS_PATH . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->modx->getOption($this->namespace . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . $this->namespace . '/');
        return array_merge([
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
        ], $config);
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
