<?php

abstract class abstractModuleEvent
{
    /** @var bool */
    public static $useMgrContext = true;

    /** @var abstractModule */
    protected $service;

    /** @var modX */
    protected $modx;

    /** @var array */
    protected $scriptProperties = [];

    /**
     * abstractModuleEvent constructor.
     *
     * @param abstractModule $service
     * @param array $scriptProperties
     */
    public function __construct(abstractModule $service, $scriptProperties = [])
    {
        $this->service = $service;
        $this->modx = $service->modx;
        $this->scriptProperties = $scriptProperties;
    }

    abstract function run();
}
