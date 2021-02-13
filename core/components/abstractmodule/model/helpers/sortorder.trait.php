<?php

trait abstractModuleModelHelperSortOrder
{
    private function setSortOrder()
    {
        if ($this->isNew()) {
            $this->newSortOrder();
        } elseif ($this->isDirty($this->sortOrderField)) {
            $this->updateSortOrder();
        }
    }
    
    private function newSortOrder()
    {
        $sortOrder = $this->xpdo->getCount($this->_class, $this->getSortOrderConditions());
        $this->set($this->sortOrderField, $sortOrder);
    }

    private function updateSortOrder()
    {
        $query = $this->xpdo->newQuery($this->_class, $this->getSortOrderConditions());
        $query->select('MAX(' . $this->sortOrderField . ')');
        $maxSortOrder = $this->xpdo->getValue($query->prepare());

        $query = $this->xpdo->newQuery($this->_class, array_merge($this->getSortOrderConditions(), [
            $this->getPK() => $this->getPrimaryKey()
        ]));
        $query->select($this->sortOrderField);
        $oldSortOrder = $this->xpdo->getValue($query->prepare());
        $newSortOrder = $this->get($this->sortOrderField);

        if ($newSortOrder > $maxSortOrder) {
            $newSortOrder = $maxSortOrder;
        }

        if ($newSortOrder > $oldSortOrder) {
            $query = $this->xpdo->newQuery($this->_class);
            $query->command('UPDATE');
            $query->where(array_merge($this->getSortOrderConditions(), [
                $this->sortOrderField . ':>' => $oldSortOrder,
                $this->sortOrderField . ':<=' => $newSortOrder,
            ]));
            $query->query['set'][$this->sortOrderField] = [
                'value' => $this->sortOrderField . ' - 1',
                'type' => false,
            ];
            $stmt = $query->prepare();
            $stmt->execute();
        }
        if ($newSortOrder < $oldSortOrder) {
            $query = $this->xpdo->newQuery($this->_class);
            $query->command('UPDATE');
            $query->where(array_merge($this->getSortOrderConditions(), [
                $this->sortOrderField . ':<' => $oldSortOrder,
                $this->sortOrderField . ':>=' => $newSortOrder,
            ]));
            $query->query['set'][$this->sortOrderField] = [
                'value' => $this->sortOrderField . ' + 1',
                'type' => false,
            ];
            $stmt = $query->prepare();
            $stmt->execute();
        }

        $this->set($this->sortOrderField, $newSortOrder);
    }

    private function removeSortOrder()
    {
        $query = $this->xpdo->newQuery($this->_class);
        $query->command('UPDATE');
        $query->where(array_merge($this->getSortOrderConditions(), [
            $this->sortOrderField . ':>' => $this->get($this->sortOrderField),
        ]));
        $query->query['set'][$this->sortOrderField] = [
            'value' => $this->sortOrderField . ' - 1',
            'type' => false,
        ];
        $stmt = $query->prepare();
        $stmt->execute();
    }

    /**
     * @return array
     */
    protected function getSortOrderConditions()
    {
        return [];
    }
}
