<?php

namespace KodiCMS\Support\Traits;

trait Tentacle
{
    /**
     * @var array
     */
    protected static $tentacles = [];

    /**
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (array_key_exists($method, static::$tentacles)) {
            $method = static::$tentacles[$method];
            $method = \Closure::bind($method, $this, get_class());

            return $method($this);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * @param string   $name
     * @param callable $function
     */
    public static function addRelation($name, callable $function)
    {
        static::$tentacles[$name] = $function;
    }

    /**
     * @return array
     */
    public function getCustomRelations()
    {
        return array_keys(static::$tentacles);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $attribute = parent::getAttribute($key);

        if (! is_null($attribute)) {
            return $attribute;
        }

        if (array_key_exists($key, static::$tentacles)) {
            return $this->getRelationshipFromMethod($key);
        }
    }
}
