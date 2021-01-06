<?php

require_once dirname(__DIR__) . '/helpers/log.trait.php';
require_once dirname(__DIR__) . '/helpers/event.trait.php';

abstract class abstractModule
{
    use abstractModuleHelperLog;
    use abstractModuleHelperEvent;

    const PKG_VERSION = '1.0.0';
    const PKG_RELEASE = 'beta';
    const PKG_NAMESPACE = 'abstractmodule';
    const TABLE_PREFIX = '';
    const DEVELOPER_MODE = true;

    /** @var modX */
    public $modx;

    /** @var array */
    public $config = [];

    /** @var bool */
    protected $loadPackage = true;

    /** @var bool */
    protected $loadLexicon = true;

    /**
     * abstractModule constructor.
     *
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx = $modx;
        $this->config = $this->getConfig($config);
        if ($this->loadPackage) {
            $this->modx->addPackage($this::PKG_NAMESPACE, $this->modelPath, $this::TABLE_PREFIX);
        }
        if ($this->loadLexicon) {
            $this->modx->lexicon->load($this::PKG_NAMESPACE . ':default');
        }
    }

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

    /**
     * @param array $config
     * @return array
     */
    protected function getConfig($config = [])
    {
        $abstractAssetsUrl = $this->modx->getOption('abstractmodule.assets_url', $config, MODX_ASSETS_URL . 'components/abstractmodule/');

        $corePath = $this->modx->getOption($this::PKG_NAMESPACE . '.core_path', $config, MODX_CORE_PATH . 'components/' . $this::PKG_NAMESPACE . '/');
        $assetsPath = $this->modx->getOption($this::PKG_NAMESPACE . '.assets_path', $config, MODX_ASSETS_PATH . 'components/' . $this::PKG_NAMESPACE . '/');
        $assetsUrl = $this->modx->getOption($this::PKG_NAMESPACE . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . $this::PKG_NAMESPACE . '/');

        return array_merge([
            'corePath' => $corePath,
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'abstractJsUrl' => $abstractAssetsUrl . 'js/',
            'abstractСssUrl' => $abstractAssetsUrl . 'css/',

            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'actionUrl' => $assetsUrl . 'action.php',
        ], $config);
    }
}
