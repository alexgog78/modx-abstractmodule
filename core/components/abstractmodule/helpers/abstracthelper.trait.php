<?php

trait AbstractHelper
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
        if (is_array($data)) {
            $data = print_r($data, true);
        }

        $trace = debug_backtrace();
        $file = $trace[1]['file'];
        $line = $trace[1]['line'];
        $this->modx->log($level, $data, '', get_class($this), $file, $line);
    }

    /**
     * @param string $key
     * @param array $placeholders
     * @return string
     */
    public function getLexiconTopic($key = '', $placeholders = [])
    {
        return $this->modx->lexicon($this->objectType . '.' . $key, $placeholders);
    }

    /**
     * @param $eventName
     * @param array $data
     * @return array
     */
    public function invokeEvent($eventName, $data = [])
    {
        $this->modx->event->returnedValues = null;
        $response = [
            'eventOutput' => $this->modx->invokeEvent($eventName, $data),
            'returnedValues' => $this->modx->event->returnedValues,
        ];
        return $response;
    }
}
