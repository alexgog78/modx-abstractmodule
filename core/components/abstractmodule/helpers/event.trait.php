<?php

trait abstractModuleHelperEvent
{
    /**
     * @param string $eventName
     * @param array $data
     * @return array
     */
    public function invokeEvent(string $eventName, $data = [])
    {
        $this->modx->event->returnedValues = null;
        return [
            'eventOutput' => $this->modx->invokeEvent($this::PKG_NAMESPACE . $eventName, $data),
            'returnedValues' => $this->modx->event->returnedValues ?? [],
        ];
    }

    /**
     * @param string $eventName
     * @param array $scriptProperties
     */
    public function handleEvent(string $eventName, $scriptProperties = [])
    {
        $handlerFile = $this->eventsPath .  strtolower($eventName) . '.php';
        if (!file_exists($handlerFile)) {
            return;
        }
        require_once dirname(__DIR__) . '/events/event.class.php';
        require_once $handlerFile;
        $handlerClass = get_class($this) . 'Event' . $eventName;
        if ($this->modx->context->key == 'mgr' && !$handlerClass::$useMgrContext) {
            return;
        }
        $handler = new $handlerClass($this, $eventName, $scriptProperties);
        if (!($handler instanceof abstractModuleEvent)) {
            exit('Could not load event handler: ' . $handlerClass);
        }
        $handler->run();
    }
}
