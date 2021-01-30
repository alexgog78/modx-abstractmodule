<?php

abstract class abstractModuleRemoveMultipleProcessor extends modObjectProcessor
{
    /** @var string */
    public $objectType;

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

        $collection = $this->modx->getCollection($this->classKey, [$this->primaryKeyField . ':IN' => $records]);
        foreach ($collection as $item) {
            $item->remove();
        }
        return $this->outputArray([], count($collection));
    }
}
