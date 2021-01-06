<?php

require_once dirname(__DIR__) . '/helpers/searchquery.trait.php';
require_once dirname(__DIR__) . '/helpers/comboquery.trait.php';

abstract class abstractModuleGetListProcessor extends modObjectGetListProcessor
{
    use abstractModuleProcessorHelperSearchQuery;
    use abstractModuleProcessorHelperComboQuery;

    /** @var string */
    public $objectType = 'samplemodule';

    /** @var string */
    public $defaultSortField = 'menuindex';

    /** @var string */
    public $defaultSortDirection = 'ASC';

    /** @var object */
    protected $service;

    /** @var array */
    protected $searchableFields = [
        'name',
    ];

    /**
     * @param modX $modx
     * @param array $properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);
        $this->service = $this->modx->{$this->objectType};
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);
        if ($this->isComboQuery()) {
            $c = $this->comboQuery($c);
        }
        $c = $this->searchQuery($c);
        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryAfterCount($c);
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        return $c;
    }
}
