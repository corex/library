<?php

use CoRex\Support\Properties;

class PropertiesHelper extends Properties
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