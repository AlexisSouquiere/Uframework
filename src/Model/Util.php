<?php

namespace Model;

class Util
{
    public static function setPropertyValue($object, $value, $attribute = 'id')
    {
        $reflClass = new \ReflectionClass(get_class($object));
        $property = $reflClass->getProperty($attribute);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
