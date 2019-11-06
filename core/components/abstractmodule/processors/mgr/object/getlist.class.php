<?php

abstract class amObjectGetListProcessor extends modObjectGetListProcessor
{
    /** @var string */
    public $defaultSortField = 'id';

    /** @var string */
    public $defaultSortDirection = 'ASC';

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
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
    public function prepareQueryAfterCount(xPDOQuery $c) {
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
}