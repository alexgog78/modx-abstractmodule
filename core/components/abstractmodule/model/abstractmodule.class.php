<?php

require_once dirname(__DIR__) . '/helpers/log.trait.php';
require_once dirname(__DIR__) . '/helpers/event.trait.php';
require_once dirname(__DIR__) . '/helpers/mgr.trait.php';
require_once dirname(__DIR__) . '/helpers/web.trait.php';

abstract class abstractModule
{
    use abstractModuleHelperLog;
    use abstractModuleHelperEvent;
    use abstractModuleHelperMgr;
    use abstractModuleHelperWeb;

    const PKG_VERSION = '1.1.0';
    const PKG_RELEASE = 'beta';
    const PKG_NAMESPACE = 'abstractmodule';
    const TABLE_PREFIX = '';
    const DEVELOPER_MODE = false;

    /** @var modX */
    public $modx;

    /** @var bool */
    protected $loadPackage = true;

    /** @var array */
    protected $languageTopics = [
        'default',
    ];

    /** @var array */
    protected $abstractConfig = [];

    /** @var array */
    protected $config = [];

    /**
     * abstractModule constructor.
     *
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX $modx, array $config = [])
    {
        $this->modx = $modx;
        $this->setAbstractConfig($config);
        $this->setConfig($config);
        if ($this->loadPackage) {
            $this->modx->addPackage($this::PKG_NAMESPACE, $this->modelPath, $this::TABLE_PREFIX);
        }
        foreach ($this->languageTopics as $topic) {
            $this->modx->lexicon->load($this::PKG_NAMESPACE . ':' . $topic);
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
     * @return array
     */
    public function getAbstractConfig()
    {
        return $this->abstractConfig;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    protected function setAbstractConfig($config = [])
    {
        $corePath = $this->modx->getOption(self::PKG_NAMESPACE . '.core_path', $config, MODX_CORE_PATH . 'components/' . self::PKG_NAMESPACE . '/');
        $assetsPath = $this->modx->getOption(self::PKG_NAMESPACE . '.assets_path', $config, MODX_ASSETS_PATH . 'components/' . self::PKG_NAMESPACE . '/');
        $assetsUrl = $this->modx->getOption(self::PKG_NAMESPACE . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . self::PKG_NAMESPACE . '/');

        $this->abstractConfig = array_merge([
            'corePath' => $corePath,
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
        ], $config);
    }

    /**
     * @param array $config
     */
    protected function setConfig($config = [])
    {
        $corePath = $this->modx->getOption($this::PKG_NAMESPACE . '.core_path', $config, MODX_CORE_PATH . 'components/' . $this::PKG_NAMESPACE . '/');
        $assetsPath = $this->modx->getOption($this::PKG_NAMESPACE . '.assets_path', $config, MODX_ASSETS_PATH . 'components/' . $this::PKG_NAMESPACE . '/');
        $assetsUrl = $this->modx->getOption($this::PKG_NAMESPACE . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . $this::PKG_NAMESPACE . '/');

        $this->config = array_merge([
            'corePath' => $corePath,
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'eventsPath' => $corePath . 'events/',

            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'actionUrl' => $assetsUrl . 'action.php',
        ], $config);
    }
}
