<?php

trait abstractObjectHasSortOrder
{
    /** @var bool */
    protected $sortOrder = true;

    /**
     * @return bool
     */
    protected function hasSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @return $this
     */
    protected function setSortOrder()
    {
        if ($this->isNew()) {
            $this->newSortOrder();
        } elseif ($this->isDirty(static::SORT_ORDER)) {
            $this->updateSortOrder();
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function newSortOrder()
    {
        $sortOrder = $this->xpdo->getCount($this->_class, $this->getSortOrderConditions());
        $this->set(static::SORT_ORDER, $sortOrder);
        return $this;
    }

    /**
     * @return $this
     */
    protected function updateSortOrder()
    {
        $query = $this->xpdo->newQuery($this->_class, $this->getSortOrderConditions());
        $query->select('MAX(' . static::SORT_ORDER . ')');
        $maxSortOrder = $this->xpdo->getValue($query->prepare());

        $query = $this->xpdo->newQuery($this->_class, array_merge($this->getSortOrderConditions(), [
            $this->getPK() => $this->getPrimaryKey()
        ]));
        $query->select(static::SORT_ORDER);
        $oldSortOrder = $this->xpdo->getValue($query->prepare());
        $newSortOrder = $this->get(static::SORT_ORDER);

        if ($newSortOrder > $maxSortOrder) {
            $newSortOrder = $maxSortOrder;
        }

        if ($newSortOrder > $oldSortOrder) {
            $query = $this->xpdo->newQuery($this->_class);
            $query->command('UPDATE');
            $query->where(array_merge($this->getSortOrderConditions(), [
                static::SORT_ORDER . ':>' => $oldSortOrder,
                static::SORT_ORDER . ':<=' => $newSortOrder,
            ]));
            $query->query['set'][static::SORT_ORDER] = [
                'value' => static::SORT_ORDER . ' - 1',
                'type' => false,
            ];
            $stmt = $query->prepare();
            $stmt->execute();
        }
        if ($newSortOrder < $oldSortOrder) {
            $query = $this->xpdo->newQuery($this->_class);
            $query->command('UPDATE');
            $query->where(array_merge($this->getSortOrderConditions(), [
                static::SORT_ORDER . ':<' => $oldSortOrder,
                static::SORT_ORDER . ':>=' => $newSortOrder,
            ]));
            $query->query['set'][static::SORT_ORDER] = [
                'value' => static::SORT_ORDER . ' + 1',
                'type' => false,
            ];
            $stmt = $query->prepare();
            $stmt->execute();
        }

        $this->set(static::SORT_ORDER, $newSortOrder);
        return $this;
    }

    protected function removeSortOrder()
    {
        $query = $this->xpdo->newQuery($this->_class);
        $query->command('UPDATE');
        $query->where(array_merge($this->getSortOrderConditions(), [
            static::SORT_ORDER . ':>' => $this->get(static::SORT_ORDER),
        ]));
        $query->query['set'][static::SORT_ORDER] = [
            'value' => static::SORT_ORDER . ' - 1',
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
