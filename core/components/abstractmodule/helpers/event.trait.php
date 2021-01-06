<?php

trait abstractModuleHelperEvent
{
    /**
     * @param $eventName
     * @param array $data
     * @return array
     */
    public function invokeEvent($eventName, $data = [])
    {
        $this->modx->event->returnedValues = null;
        return [
            'eventOutput' => $this->modx->invokeEvent($this::PKG_NAMESPACE . $eventName, $data),
            'returnedValues' => $this->modx->event->returnedValues ?? [],
        ];
    }
}
