<?php

/**
 * Copyright (c) D3 Data Development (Inh. Thomas Dartsch)
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * https://www.d3data.de
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author    D3 Data Development - Daniel Seifert <info@shopmodule.com>
 * @link      https://www.oxidmodule.com
 */

declare(strict_types=1);

namespace D3\DIContainerHandler\tests\unit;

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
     * @dataProvider getVendorDirTestDataProvider
     */
    public function getVendorDirTest(string $path): void
    {
        $sut = oxNew(d3DicUtilities::class);

        $vendorDir = (string) $this->callMethod(
            $sut,
            'getVendorDir',
            [$path]
        );

        $this->assertSame('/var/www/html/vendor/', $vendorDir);
        $this->assertDirectoryExists(
            $this->callMethod(
                $sut,
                'getVendorDir'
            )
        );
    }

    public function getVendorDirTestDataProvider(): Generator
    {
        yield 'default' => ['/var/www/html/vendor/d3/oxid-dic-handler/d3DicUtilities.php'];
        yield 'space after' => ['/var/www/html/vendor/d3/oxid-dic-handler/d3DicUtilities.php '];
    }
}
