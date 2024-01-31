<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * https://www.d3data.de
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 * @link      https://www.oxidmodule.com
 */

declare(strict_types=1);

namespace D3\DIContainerHandler\tests\autoload;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class functions_oxDICTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function d3GetOxidDICTest(): void
    {
        $this->assertInstanceOf(
            ContainerBuilder::class,
            d3GetOxidDIC()
        );
    }

    /**************/



    /**
     * Calls a private or protected object method.
     *
     * @param object $object
     * @param string $methodName
     * @param array $arguments
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function callMethod($object, $methodName, array $arguments = [])
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $arguments);
    }

    /**
     * Sets a private or protected property in defined class instance
     *
     * @param object $object
     * @param string $valueName
     * @param mixed $value
     * @throws ReflectionException
     */
    public function setValue($object, $valueName, $value)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($valueName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * Sets a private or protected property in mocked class instance based on original class
     * (required for e.g. final properties, which aren't contained in mock, but in original class)
     * @param $mockedClassName  * FQNS of original class
     * @param $object           * mock object
     * @param $valueName        * property name
     * @param $value            * new property value
     *
     * @throws ReflectionException
     */
    public function setMockedClassValue($mockedClassName, $object, $valueName, $value)
    {
        $property = new \ReflectionProperty($mockedClassName, $valueName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * get a private or protected property from defined class instance
     *
     * @param object $object
     * @param string $valueName
     * @param mixed $value
     * @return mixed
     * @throws ReflectionException
     */
    public function getValue($object, $valueName)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($valueName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    /**
     * get a private or protected property from mocked class instance based on original class
     * (required for e.g. final properties, which aren't contained in mock, but in original class)
     * @param object $object
     * @param string $valueName
     * @param mixed $value
     * @return mixed
     * @throws ReflectionException
     */
    public function getMockedClassValue($mockedClassName, $object, $valueName)
    {
        $property = new \ReflectionProperty($mockedClassName, $valueName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}