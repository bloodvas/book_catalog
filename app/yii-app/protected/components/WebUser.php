<?php

class WebUser extends CWebUser
{
    private $_model = null;
    
    /**
     * Check if user is guest (not authenticated)
     */
    public function getIsGuest()
    {
        return $this->getState('__id') === null;
    }
    
    /**
     * Check if user is authenticated user
     */
    public function getIsUser()
    {
        return !$this->getIsGuest();
    }
    
    /**
     * Get user model
     */
    public function getModel()
    {
        if ($this->_model === null && !$this->getIsGuest()) {
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }
}
