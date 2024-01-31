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

namespace D3\DIContainerHandler\tests;

use D3\DIContainerHandler\d3DicHandler;
use D3\DIContainerHandler\d3DicUtilities;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class d3DicUtilitiesTest extends TestCase
{
    /**
     * @test
     * @param string      $className
     * @param string|null $additional
     * @param             $expected
     *
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicUtilities::getServiceId
     * @dataProvider getServiceIdTestDataProvider
     */
    public function getServiceIdTest(string $className, string $additional = null, $expected): void
    {
        $sut = oxNew(d3DicUtilities::class);

        $this->assertSame(
            $expected,
            $this->callMethod(
                $sut,
                'getServiceId',
                [$className, $additional]
            )
        );
    }

    public function getServiceIdTestDataProvider(): Generator
    {
        yield 'NS only' => [d3DicHandler::class, null, 'd3\dicontainerhandler\d3dichandler'];
        yield 'NS + additional' => [d3DicHandler::class, 'additional', 'additional.d3\dicontainerhandler\d3dichandler'];
    }

    /**
     * @test
     *
     * @param string      $className
     * @param string      $argumentName
     * @param             $expected
     *
     * @return void
     * @throws ReflectionException
     * @covers       \D3\DIContainerHandler\d3DicUtilities::getArgumentId
     * @dataProvider getArgumentIdTestDataProvider
     */
    public function getArgumentIdTest(string $className, string $argumentName, $expected): void
    {
        $sut = oxNew(d3DicUtilities::class);

        $this->assertSame(
            $expected,
            $this->callMethod(
                $sut,
                'getArgumentId',
                [ $className, $argumentName]
            )
        );
    }

    public function getArgumentIdTestDataProvider(): Generator
    {
        yield 'default' => [d3DicHandler::class, 'argumentName', 'd3\dicontainerhandler\d3dichandler.args.argumentname'];
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicUtilities::getVendorDir()
     */
    public function getVendorDirTest(): void
    {
        $sut = oxNew(d3DicUtilities::class);

        $this->assertDirectoryExists(
            $this->callMethod(
                $sut,
                'getVendorDir'
            )
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