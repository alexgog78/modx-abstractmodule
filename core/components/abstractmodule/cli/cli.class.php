<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/config.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

abstract class AbstractCLI
{
    const LOG_LEVEL_INFO = 0;
    const LOG_LEVEL_SUCCESS = 1;
    const LOG_LEVEL_ERROR = 2;

    /** @var modX */
    protected $modx;

    /**
     * AbstractCLI constructor.
     */
    public function __construct()
    {
        $this->modx = new modX();
        $this->modx->initialize('mgr');
        $this->modx->setLogLevel(modX::LOG_LEVEL_ERROR);
        $this->modx->setLogTarget('ECHO');
    }

    /**
     * @param $argument
     * @param int $mode
     * @return $this
     */
    /*public function addArgument($argument, $mode = 1)
    {
        $this->application->addArgument('foo', InputArgument::REQUIRED, 'The directory');
        return $this;
    }*/

    /**
     * TODO
     * @param $argument
     * @param int $mode
     * @return $this
     */
    /*public function addOption($argument, $mode = 1)
    {
        $this->application->addOption('bar', null, InputOption::VALUE_REQUIRED);
        return $this;
    }*/

    abstract protected function run();

    /**
     * @param $data
     */
    protected function info($data)
    {
        $this->log($data, self::LOG_LEVEL_INFO);
    }

    /**
     * @param $data
     */
    protected function success($data)
    {
        $this->log($data, self::LOG_LEVEL_SUCCESS);
    }

    /**
     * @param $data
     */
    protected function error($data)
    {
        $this->log($data, self::LOG_LEVEL_ERROR);
        exit();
    }

    /**
     * @param mixed $data
     * @param int $level
     */
    private function log($data, $level = self::LOG_LEVEL_INFO)
    {
        if ($data instanceof xPDOObject) {
            $data = $data->toArray('', false, true, true);
        }
        if (is_array($data)) {
            $data = print_r($data, true);
        }
        switch ($level) {
            case self::LOG_LEVEL_SUCCESS:
                $style =  "\033[32m";
                break;
            case self::LOG_LEVEL_ERROR:
                $style =  "\033[31m";
                break;
            default:
                $style = "\033[33m";
                break;
        }
        echo $style . $data . PHP_EOL;
    }
}
