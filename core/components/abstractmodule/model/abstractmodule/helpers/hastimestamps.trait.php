<?php

trait abstractObjectHasTimestamps
{
    /** @var bool */
    protected $timestamps = true;

    /**
     * @return bool
     */
    protected function hasTimestamps()
    {
        return $this->timestamps;
    }

    /**
     * @return $this
     */
    protected function setTimestamps()
    {
        $date = date('Y-m-d H:i:s');
        if ($this->isNew()) {
            $this->setCreatedOn($date);
        } else {
            $this->setUpdatedOn($date);
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    protected function setCreatedOn($value)
    {
        $this->set(static::CREATED_ON, $value);
        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    protected function setUpdatedOn($value)
    {
        $this->set(static::UPDATED_ON, $value);
        return $this;
    }

}
