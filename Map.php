<?php
/**
 * Created by PhpStorm.
 * User: Robinson
 * Date: 06/10/2016
 * Time: 21:20
 */

class Map {
    private $name;
    private $key;

    /**
     * Map constructor.
     * @param $name
     * @param $key
     */
    public function __construct($name, $key)
    {
        $this->name = $name;
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }




}