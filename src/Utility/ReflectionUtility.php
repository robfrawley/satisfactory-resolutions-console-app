<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Utility;

/**
 * Class ReflectionUtility
 */
class ReflectionUtility
{
    use NotConstructableUtilityTrait;

    const IS_PRIVATE = \ReflectionMethod::IS_PRIVATE;
    const IS_PROTECTED = \ReflectionMethod::IS_PROTECTED;
    const IS_PUBLIC = \ReflectionMethod::IS_PUBLIC;

    /**
     * @param object|string $class
     *
     * @return \ReflectionClass|\ReflectionObject
     */
    public static function getReflectionObject($class): \ReflectionClass
    {
        try {
            return new \ReflectionObject($class);
        } catch (\Exception $e) {
            return self::getReflectionClass($class);
        }
    }

    /**
     * @param object|string $class
     */
    public static function getReflectionClass($class): \ReflectionClass
    {
        try {
            return new \ReflectionClass(self::getClassName($class));
        } catch (\ReflectionException $e) {
            throw new \RuntimeException(sprintf('Failed to create reflection object/class "%s" (%s).', self::getClassName($class), $e->getMessage()), $e->getCode(), $e);
        }
    }

    /**
     * @param object|string $class
     *
     * @return \ReflectionMethod[]
     */
    public static function getReflectionClassMethods($class, int $which = \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE): array
    {
        return self::getReflectionObject($class)->getMethods($which);
    }

    /**
     * @param object|string $class
     *
     * @return \ReflectionMethod[]
     */
    public static function getParentReflectionClassMethods($class, int $which = \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE): array
    {
        return self::getReflectionObject($class)->getParentClass()->getMethods($which);
    }

    /**
     * @param object|string $class
     *
     * @return \ReflectionMethod[]
     */
    public static function getReflectionClassProperties($class, int $which = \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE): array
    {
        return self::getReflectionObject($class)->getProperties($which);
    }

    /**
     * @param object|string $class
     *
     * @return \ReflectionMethod[]
     */
    public static function getParentReflectionClassProperties($class, int $which = \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE): array
    {
        return self::getReflectionObject($class)->getParentClass()->getProperties($which);
    }

    /**
     * @param object|string $class
     */
    public static function getReflectionClassMethod($class, string $method): \ReflectionMethod
    {
        try {
            return self::getReflectionClass($class)->getMethod($method);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException(sprintf('Failed to create reflection method "%s" for class "%s" (%s).', self::getClassName($class), $method, $e->getMessage()), $e->getCode(), $e);
        }
    }

    /**
     * @param object|string $class
     * @param mixed         ...$arguments
     *
     * @return mixed
     */
    public static function invokeReflectionClassMethod($class, string $method, ...$arguments)
    {
        $m = self::getReflectionClassMethod($class, $method);
        $m->setAccessible(true);

        return $m->invoke($class, ...$arguments);
    }

    /**
     * @param object|string $class
     */
    public static function getClassName($class): string
    {
        return self::getReflectionObject($class)->getName();
    }
}
