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
use D3\TestingTools\Development\CanAccessRestricted;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class d3DicUtilitiesTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @test
     *
     * @param string      $className
     * @param string|null $additional
     * @param string      $expected
     *
     * @throws ReflectionException
     * @covers       \D3\DIContainerHandler\d3DicUtilities::getServiceId
     * @dataProvider getServiceIdTestDataProvider
     */
    public function getServiceIdTest(string $className, ?string $additional, string $expected): void
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
     *
     * @throws ReflectionException
     * @covers       \D3\DIContainerHandler\d3DicUtilities::getArgumentId
     * @dataProvider getArgumentIdTestDataProvider
     */
    public function getArgumentIdTest(string $className, string $argumentName, string $expected): void
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
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicUtilities::getVendorDir()
     */
    public function getVendorDirTest(): void
    {
        $sut = oxNew(d3DicUtilities::class);

        $this->assertDirectoryExists(
            (string) $this->callMethod(
                $sut,
                'getVendorDir'
            )
        );
    }
}
