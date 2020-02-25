<?php

//TODO
class runCommand
{
    /** @var modX */
    private $modx;

    /** @var object */
    private $command;

    /**
     * Parse constructor.
     * @param modX $modx
     */
    public function __construct(modX &$modx, $config = [])
    {
        $this->modx = &$modx;
        $action = $config[1];
        array_shift($config);
        array_shift($config);
        if (!$this->getHandler($action, $config)) {
            die();
        }
    }

    public function run()
    {
        $this->command->run();
        $this->log('Finish');
    }

    /**
     * @param $data
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

    /**
     * @return bool
     */
    private function getHandler($action, $config = [])
    {
        $command = $this->modx->loadClass($action, dirname(__FILE__) . '/', true, true);
        if (!$command) {
            return false;
        }
        $this->command = new $command($this->modx, $config);
        return true;
    }
}

$basePath = dirname(dirname(__FILE__));

/** @noinspection PhpIncludeInspection */
require_once $basePath . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$command = new runCommand($modx, $argv);
$command->run();
exit();
