<?php

declare(strict_types=1);

abstract class AbstractBaseFactory
{
    protected function initProperties(object $primObj, array $params = []) : mixed
    {
        $obj = new ReflectionObject($primObj);
        $params = $this->params($obj, $params);
        foreach ($params as $key => $value) {
            if ($obj->hasProperty($key)) {
                $refProperty = $obj->getProperty($key);
                $refProperty->setAccessible(true);
                if (is_string($value)) {
                    $value = $key == 'factory' ? $this : Application::getInstance()->getClass($value);
                    if (! is_object($value)) {
                        if (class_exists($value)) {
                            $value = Application::diGet($value);
                        }
                    }
                }
                $refProperty->setValue($primObj, $value);
            }
        }
        return $primObj;
    }

    private function params(ReflectionObject $obj, array $params) : array
    {
        $en = [];

        if ($obj->hasProperty('_table')) {
            $prop = $obj->getDefaultProperties();
            $en = ['entity' => str_replace(' ', '', ucwords(str_replace('_', ' ', $prop['_table']))) . 'Entity'];
        }
        return array_merge($params, $en);
    }
}