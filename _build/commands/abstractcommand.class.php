<?php

abstract class abstractCommand
{
    /** @var modX */
    protected $modx;

    /** @var array */
    protected $config = [];

    public function __construct(modX &$modx, $config = [])
    {
        $this->modx = &$modx;
        $this->config = $config;
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
}
