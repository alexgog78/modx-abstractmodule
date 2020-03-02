<?php

if (php_sapi_name() !== 'cli') {
    die('Only console input');
}

abstract class AbstractCLI
{
    /** @var modX */
    protected $modx;

    /** @var array */
    protected $config = [];

    /**
     * AbstractCLI constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
        $this->initializeModX();
    }

    abstract public function run();

    /**
     * @param mixed $data
     * @param int $level
     */
    public function log($data, $level = modX::LOG_LEVEL_INFO)
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
        $this->modx->log($level, $data, '', get_class($this), $file, $line);
    }

    private function initializeModX()
    {
        define('MODX_API_MODE', true);
        require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
        $this->modx = new modX();
        $this->modx->initialize('mgr');
        $this->modx->setLogLevel(modX::LOG_LEVEL_INFO);
        $this->modx->setLogTarget('ECHO');
    }
}
