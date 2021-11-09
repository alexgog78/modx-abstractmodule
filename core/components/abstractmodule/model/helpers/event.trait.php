<?php

trait abstractModuleEventHelper
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
            $this->log('Could not load event handler file: ' . $handlerFile);
            return;
        }
        require_once MODX_CORE_PATH . 'components/abstractmodule/events/event.class.php';
        require_once MODX_CORE_PATH . 'components/abstractmodule/events/mgrevent.class.php';
        require_once MODX_CORE_PATH . 'components/abstractmodule/events/webevent.class.php';
        require_once $handlerFile;
        $handlerClass = $this::PKG_NAME . 'Event' . $eventName;
        $handler = new $handlerClass($this, $eventName, $scriptProperties);
        if (!($handler instanceof abstractModuleEvent)) {
            exit('Could not load event handler class: ' . $handlerClass);
        }
        $handler->handleEvent();
    }
}
