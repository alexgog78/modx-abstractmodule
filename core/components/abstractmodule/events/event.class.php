<?php

abstract class abstractModuleEvent
{
    /** @var abstractModule */
    protected $service;

    /** @var modX */
    protected $modx;

    /** @var string */
    protected $eventName;

    /** @var array */
    protected $scriptProperties = [];

    /**
     * abstractModuleEvent constructor.
     *
     * @param abstractModule $service
     * @param string $eventName
     * @param array $scriptProperties
     */
    public function __construct(abstractModule $service, string $eventName, array $scriptProperties = [])
    {
        $this->service = $service;
        $this->modx = $service->modx;
        $this->eventName = $eventName;
        $this->scriptProperties = $scriptProperties;
    }

    public function handleEvent()
    {
        if (!$this->checkPermissions()) {
            return;
        }
        $this->run();
    }

    /**
     * @return bool
     */
    protected function checkPermissions()
    {
        return true;
    }

    abstract protected function run();
}
