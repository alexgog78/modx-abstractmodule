<?php

trait abstractModuleProcessorHelperSearchQuery
{
    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    private function searchQuery(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        $valuesqry = $this->getProperty('valuesqry');
        if (empty($query) || !empty($valuesqry)) {
            return $c;
        }
        $filter = [];
        foreach ($this->searchableFields as $field) {
            $filter['OR:' . $field . ':LIKE'] = '%' . $query . '%';
        }
        $c->where([$filter]);
        return $c;
    }
}
