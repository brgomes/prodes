<?php

namespace App\Helpers;

class Registry extends \ArrayObject
{
    private static $_registryClassName = 'App\Helpers\Registry';
    private static $_registry = null;

    public static function getInstance()
    {
        if (self::$_registry === null) {
            self::init();
        }

        return self::$_registry;
    }

    public static function setInstance(Registry $registry)
    {
        if (self::$_registry !== null) {
            dd('Registry is already initialized');
        }

        self::setClassName(get_class($registry));
        self::$_registry = $registry;
    }

    protected static function init()
    {
        self::setInstance(new self::$_registryClassName());
    }

    public static function setClassName($registryClassName = 'App\Helpers\Registry')
    {
        if (self::$_registry !== null) {
            dd('Registry is already initialized');
        }

        if (!is_string($registryClassName)) {
            dd('Argument is not a class name');
        }

        self::$_registryClassName = $registryClassName;
    }

    public static function _unsetInstance()
    {
        self::$_registry = null;
    }

    public static function get($index, $default = null)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index)) {
            return $default;
        }

        return $instance->offsetGet($index);
    }

    public static function set($index, $value)
    {
        $instance = self::getInstance();
        $instance->offsetSet($index, $value);
    }

    public static function isRegistered($index)
    {
        if (self::$_registry === null) {
            return false;
        }

        return self::$_registry->offsetExists($index);
    }

    public function __construct($array = array(), $flags = parent::ARRAY_AS_PROPS)
    {
        parent::__construct($array, $flags);
    }

    public function offsetExists($index)
    {
        return array_key_exists($index, $this);
    }
}
