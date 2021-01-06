<?php

abstract class abstractModuleGetProcessor extends modObjectGetProcessor
{
    /** @var string */
    public $objectType = 'samplemodule';

    /** @var array */
    public $languageTopics = [
        'samplemodule:status',
    ];

    /** @var object */
    protected $service;

    /**
     * @param modX $modx
     * @param array $properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);
        $this->service = $this->modx->{$this->objectType};
    }
}
