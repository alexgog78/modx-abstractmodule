<?php

require_once dirname(__DIR__) . '/helpers/timestamps.trait.php';
require_once dirname(__DIR__) . '/helpers/sortorder.trait.php';
require_once dirname(__DIR__) . '/helpers/json.trait.php';

abstract class abstractSimpleObject extends xPDOSimpleObject
{
    use abstractModuleModelHelperTimestamps;
    use abstractModuleModelHelperSortOrder;
    use abstractModuleModelHelperJson;

    /** @var bool */
    protected $timestamps = true;

    /** @var string */
    public $createdOnField = 'created_on';

    /** @var string */
    public $createdByField = 'created_by';

    /** @var string */
    public $updatedOnField = 'updated_on';

    /** @var string */
    public $updatedByField = 'updated_by';

    /** @var bool */
    protected $sortOrder = true;

    /** @var string */
    protected $sortOrderField = 'sort_order';

    /**
     * @param null|boolean|integer $cacheFlag
     * @return bool
     */
    public function save($cacheFlag = null)
    {
        if ($this->timestamps) {
            $this->setTimestamps();
        }
        if ($this->sortOrder) {
            $this->setSortOrder();
        }
        $this->setJsonFields();
        return parent::save($cacheFlag);
    }

    /**
     * @param array $ancestors
     * @return bool
     */
    public function remove(array $ancestors = [])
    {
        if ($this->sortOrder) {
            $this->removeSortOrder();
        }
        return parent::remove($ancestors);
    }
}
