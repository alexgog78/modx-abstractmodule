<?php

trait abstractModuleLogHelper
{
    /**
     * @param $data
     * @param int $level
     */
    public function log($data, $level = modX::LOG_LEVEL_ERROR)
    {
        if ($data instanceof xPDOObject) {
            $data = $data->toArray('', false, true, false);
        }
        if ($data instanceof xPDOCriteria) {
            $data = $data->prepare()->queryString;
        }
        if (is_array($data)) {
            $data = print_r($data, true);
        }
        $trace = debug_backtrace();
        $this->modx->log($level, $data, '', $this::PKG_NAME, $trace[0]['file'], $trace[0]['line']);
    }
}
