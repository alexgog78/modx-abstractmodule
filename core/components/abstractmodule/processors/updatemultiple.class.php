<?php

abstract class abstractModuleUpdateMultipleProcessor extends modObjectProcessor
{
    /** @var string */
    public $objectType;

    /** @var string */
    public $classKey;

    /** @var xPDOObject */
    public $object;

    /** @var abstractModule */
    protected $service;

    /**
     * @param modX $modx
     * @param array $properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);
        $this->service = $this->modx->{$this->objectType};
        $this->languageTopics[] = $this->objectType . ':status';
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $records = $this->getProperty('records');
        if (empty($records)) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_ns'));
        }
        $records = explode(',', $records);

        $fields = $this->getProperty('fields');
        if (empty($fields)) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_data'));
        }
        $fields = $this->modx->fromJSON($fields);

        $response = $this->modx->updateCollection($this->classKey, $fields, [$this->primaryKeyField . ':IN' => $records]);
        return $this->outputArray([], $response);
    }
}
