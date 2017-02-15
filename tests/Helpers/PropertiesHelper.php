<?php

class PropertiesHelper extends \CoRex\Support\Properties
{
    private $privateValue;
    protected $protectedValue;
    public $publicValue;

    /**
     * Get private.
     *
     * @return mixed
     */
    public function getPrivate()
    {
        return $this->privateValue;
    }

    /**
     * Get protected.
     *
     * @return mixed
     */
    public function getProtected()
    {
        return $this->protectedValue;
    }
}