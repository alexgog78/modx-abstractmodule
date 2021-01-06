<?php

trait abstractModuleHelperLog
{
    /**
     * @param $data
     * @param int $level
     */
    public function log($data, $level = modX::LOG_LEVEL_ERROR)
    {
        if ($data instanceof xPDOObject) {
            $data = $data->toArray('', false, true, true);
        }
        if ($data instanceof xPDOCriteria) {
            $data = $data->prepare()->queryString;
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
