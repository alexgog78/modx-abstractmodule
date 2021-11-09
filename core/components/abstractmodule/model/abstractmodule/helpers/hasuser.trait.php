<?php

trait abstractObjectHasUser
{
    /** @var bool */
    protected $user = true;

    /**
     * @return bool
     */
    protected function hasUser()
    {
        return $this->user;
    }

    /**
     * @return $this
     */
    protected function setUser()
    {
        $user = $this->xpdo->user->id;
        if ($this->isNew()) {
            $this->setCreatedBy($user);
        } else {
            $this->setUpdatedBy($user);
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    protected function setCreatedBy($value)
    {
        $this->set(static::CREATED_BY, $value);
        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    protected function setUpdatedBy($value)
    {
        $this->set(static::UPDATED_BY, $value);
        return $this;
    }

}
