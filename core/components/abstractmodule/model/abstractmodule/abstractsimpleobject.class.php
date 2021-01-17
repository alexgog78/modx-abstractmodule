<?php

require_once dirname(__DIR__) . '/helpers/timestamps.trait.php';
require_once dirname(__DIR__) . '/helpers/menuindex.trait.php';
require_once dirname(__DIR__) . '/helpers/json.trait.php';

abstract class abstractSimpleObject extends xPDOSimpleObject
{
    use abstractModuleModelHelperTimestamps;
    use abstractModuleModelHelperMenuindex;
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
    protected $menuindex = true;

    /** @var string */
    protected $menuindexField = 'menuindex';

    /**
     * @param null|boolean|integer $cacheFlag
     * @return bool
     */
    public function save($cacheFlag = null)
    {
        if ($this->timestamps) {
            $this->setTimestamps();
        }
        if ($this->menuindex) {
            $this->setMenuindex();
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
        if ($this->menuindex) {
            $this->removeMenuindex();
        }
        return parent::remove($ancestors);
    }
}
