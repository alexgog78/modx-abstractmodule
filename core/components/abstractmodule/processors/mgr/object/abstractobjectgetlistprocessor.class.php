<?php

abstract class AbstractObjectGetListProcessor extends modObjectGetListProcessor
{
    /** @var string */
    public $defaultSortField = 'menuindex';

    /** @var string */
    public $defaultSortDirection = 'ASC';

    /** @var AbstractObject|AbstractSimpleObject */
    private $objectFactory;

    /**
     * @return bool|string|null
     */
    public function initialize() {
        $this->objectFactory = $this->modx->newObject($this->classKey);
        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);

        $query = $this->getProperty('query');
        $valuesqry = $this->getProperty('valuesqry');
        if (!empty($query) && empty($valuesqry)) {
            $this->searchQuery($c, $query);
        }

        $combo = $this->getProperty('combo');
        if ($combo) {
            $this->comboQuery($c);
        }
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

    /**
     * @param xPDOQuery $c
     * @param string $query
     * @return xPDOQuery
     */
    protected function searchQuery(xPDOQuery $c, $query)
    {
        $searchableFields = $this->objectFactory::$searchableFields;
        $filter = [];
        foreach ($searchableFields as $field) {
            $filter['OR:' . $field . ':LIKE'] = '%' . $query . '%';
        }
        $c->where([$filter]);
        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    protected function comboQuery(xPDOQuery $c)
    {
        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = parent::prepareRow($object);
        return $objectArray;
    }
}
