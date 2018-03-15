<?php

use CoRex\Support\Base\BaseProperties;

class BasePropertiesHelper extends BaseProperties
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