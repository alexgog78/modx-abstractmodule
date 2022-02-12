<?php

require_once __DIR__ . '/helpers/assets.trait.php';
require_once __DIR__ . '/helpers/event.trait.php';
require_once __DIR__ . '/helpers/lexicon.trait.php';
require_once __DIR__ . '/helpers/log.trait.php';
require_once __DIR__ . '/helpers/processor.trait.php';

abstract class abstractModule
{
    use abstractModuleAssetsHelper;
    use abstractModuleEventHelper;
    use abstractModuleLexiconHelper;
    use abstractModuleLogHelper;
    use abstractModuleProcessorHelper;

    const PKG_VERSION = '1.1.1';
    const PKG_RELEASE = 'beta';
    const PKG_NAME = 'abstractModule';
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
        $this->setConfig($config);
        if ($this->loadPackage) {
            $this->modx->addPackage($this::PKG_NAMESPACE, $this->modelPath, $this::TABLE_PREFIX);
        }
        foreach ($this->languageTopics as $topic) {
            $this->loadLexicon($topic);
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
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $key
     * @param array $options
     * @param mixed $default
     * @param bool $skipEmpty
     * @return mixed
     */
    public function getOption(string $key, $options = [], $default = null, $skipEmpty = false)
    {
        return $this->modx->getOption($this::PKG_NAMESPACE . '_' . $key, $options, $default, $skipEmpty);
    }

    /**
     * @param array $config
     */
    protected function setConfig($config = [])
    {
        $corePath = $this->modx->getOption($this::PKG_NAMESPACE . '.core_path', $config, MODX_CORE_PATH . 'components/' . $this::PKG_NAMESPACE . '/');
        $assetsPath = $this->modx->getOption($this::PKG_NAMESPACE . '.assets_path', $config, MODX_ASSETS_PATH . 'components/' . $this::PKG_NAMESPACE . '/');
        $assetsUrl = $this->modx->getOption($this::PKG_NAMESPACE . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . $this::PKG_NAMESPACE . '/');

        $baseAssetsUrl = $this->modx->getOption(self::PKG_NAMESPACE . '.assets_url', $config, MODX_ASSETS_URL . 'components/' . self::PKG_NAMESPACE . '/');

        $this->config = array_merge([
            'corePath' => $corePath,
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'controllersPath' => $corePath . 'controllers/',
            'eventsPath' => $corePath . 'events/',

            'baseAssetsUrl' => $baseAssetsUrl,
            'baseJsUrl' => $baseAssetsUrl . 'js/',
            'baseCssUrl' => $baseAssetsUrl . 'css/',

            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'actionUrl' => $assetsUrl . 'action.php',
            'actionKey' => $this::PKG_NAMESPACE . '_action',
        ], $config);
    }
}
