<?php

abstract class abstractModuleEvent
{
    /** @var bool */
    public static $useMgrContext = true;

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
    public function __construct(abstractModule $service, string $eventName, $scriptProperties = [])
    {
        $this->service = $service;
        $this->modx = $service->modx;
        $this->eventName = $eventName;
        $this->scriptProperties = $scriptProperties;
    }

    abstract function run();
}
