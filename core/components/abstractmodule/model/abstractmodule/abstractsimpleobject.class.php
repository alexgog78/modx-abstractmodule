<?php

require_once __DIR__ . '/helpers/hastimestamps.trait.php';
require_once __DIR__ . '/helpers/hasuser.trait.php';
require_once __DIR__ . '/helpers/hassortorder.trait.php';
require_once __DIR__ . '/helpers/hasjson.trait.php';

abstract class abstractSimpleObject extends xPDOSimpleObject
{
    use abstractObjectHasTimestamps;
    use abstractObjectHasUser;
    use abstractObjectHasSortOrder;
    use abstractObjectHasJson;

    const CREATED_ON = 'created_on';
    const UPDATED_ON = 'updated_on';

    const CREATED_BY = 'created_by';
    const UPDATED_BY = 'updated_by';

    const SORT_ORDER = 'sort_order';

    /**
     * @param null|bool|integer $cacheFlag
     * @return bool
     */
    public function save($cacheFlag = null)
    {
        if ($this->hasTimestamps()) {
            $this->setTimestamps();
        }
        if ($this->hasUser()) {
            $this->setUser();
        }
        if ($this->hasSortOrder()) {
            $this->setSortOrder();
        }
        if ($this->hasJson()) {
            $this->setJson();
        }
        return parent::save($cacheFlag);
    }

    /**
     * @param array $ancestors
     * @return bool
     */
    public function remove(array $ancestors = [])
    {
        if ($this->hasSortOrder()) {
            $this->removeSortOrder();
        }
        return parent::remove($ancestors);
    }
}
