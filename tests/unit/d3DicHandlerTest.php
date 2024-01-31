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
use D3\TestingTools\Development\CanAccessRestricted;
use d3DIContainerCache;
use Generator;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

define('D3_MODCFG_TEST', true);

class d3DicHandlerTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::getInstance
     */
    public function getInstanceTest(): void
    {
        $sut = new d3DicHandler();

        $this->setValue(
            $sut,
            '_instance',
            null
        );

        $containerBuilder = $this->callMethod(
            $sut,
            'getInstance'
        );

        $this->assertInstanceOf(
            ContainerBuilder::class,
            $containerBuilder
        );

        $this->assertSame(
            $containerBuilder,
            $this->callMethod(
                $sut,
                'getInstance'
            )
        );

        $this->assertTrue($containerBuilder->isCompiled());
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::getUncompiledInstance
     */
    public function getUncompiledInstanceTest(): void
    {
        $sut = new d3DicHandler();

        $containerBuilder = $this->callMethod(
            $sut,
            'getUncompiledInstance'
        );

        $this->assertInstanceOf(
            ContainerBuilder::class,
            $containerBuilder
        );

        $this->assertFalse($containerBuilder->isCompiled());
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::removeInstance
     */
    public function removeInstanceTest(): void
    {
        $sut = new d3DicHandler();

        $containerBuilder = $this->callMethod(
            $sut,
            'getInstance'
        );

        $this->callMethod(
            $sut,
            'removeInstance'
        );

        $this->assertNotSame(
            $containerBuilder,
            $this->callMethod(
                $sut,
                'getInstance'
            )
        );
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::d3GetConfig
     */
    public function d3GetConfigTest(): void
    {
        $sut = new d3DicHandler();

        $this->assertInstanceOf(
            Config::class,
            $this->callMethod(
                $sut,
                'd3GetConfig'
            )
        );
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::d3GetCacheFilePath
     */
    public function d3GetCacheFilePathTest(): void
    {
        $sut = new d3DicHandler();

        $this->assertMatchesRegularExpression(
            '/.*?\/tmp\/.*?DicContainer_\d+\.php$/m',
            $this->callMethod(
                $sut,
                'd3GetCacheFilePath'
            )
        );
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::d3GetCacheContainer
     */
    public function d3GetCacheContainerTest(): void
    {
        $sut = new d3DicHandler();

        $this->callMethod($sut, 'buildContainer');

        $this->assertInstanceOf(
            d3DIContainerCache::class,
            $this->callMethod(
                $sut,
                'd3GetCacheContainer'
            )
        );
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::d3GetFileLoader
     */
    public function d3GetFileLoaderTest(): void
    {
        $sut = new d3DicHandler();

        $containerBuilderMock = $this->getMockBuilder(ContainerBuilder::class)
            ->getMock();

        $this->assertInstanceOf(
            YamlFileLoader::class,
            $this->callMethod(
                $sut,
                'd3GetFileLoader',
                [$containerBuilderMock]
            )
        );
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::loadFiles
     */
    public function loadFilesTest(): void
    {
        /** @var ContainerBuilder|MockObject $containerBuilderMock */
        $containerBuilderMock = $this->getMockBuilder(ContainerBuilder::class)
            ->getMock();

        /** @var YamlFileLoader|MockObject $fileLoaderMock */
        $fileLoaderMock = $this->getMockBuilder(YamlFileLoader::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['load'])
            ->getMock();
        $fileLoaderMock->expects($this->atLeastOnce())->method('load');

        /** @var d3DicHandler|MockObject $sut */
        $sut = $this->getMockBuilder(d3DicHandler::class)
            ->onlyMethods(['d3GetFileLoader'])
            ->getMock();
        $sut->method('d3GetFileLoader')->willReturn($fileLoaderMock);

        $this->callMethod(
            $sut,
            'loadFiles',
            [$containerBuilderMock]
        );
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::isNotInTest
     */
    public function isNotInTest(): void
    {
        $sut = new d3DicHandler();

        $this->assertTrue(
            $this->callMethod(
                $sut,
                'isNotInTest'
            )
        );
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @dataProvider cacheFileExistsTestDataProvider
     * @covers \D3\DIContainerHandler\d3DicHandler::cacheFileExists
     */
    public function cacheFileExistsTest($cacheExist)
    {
        /** @var d3DicHandler|MockObject $sut */
        if (!$cacheExist) {
            $sut = $this->getMockBuilder(d3DicHandler::class)
                ->onlyMethods(['d3GetCacheFilePath'])
                ->getMock();
            $sut->method('d3GetCacheFilePath')->willReturn('foo');
        } else {
            $sut = new d3DicHandler();
        }

        $this->assertSame(
            $cacheExist,
            $this->callMethod(
                $sut,
                'cacheFileExists'
            )
        );
    }

    /**
     * @return Generator
     */
    public function cacheFileExistsTestDataProvider(): Generator
    {
        yield 'cacheExist'  => [true];
        yield 'cacheMissing'=> [false];
    }

    /**
     * @test
     *
     * @param bool $productive
     * @param int  $debug
     * @param bool $notInTest
     * @param bool $cacheFileExist
     * @param bool $cachedContainer
     *
     * @return void
     * @throws ReflectionException
     * @dataProvider buildContainerTestDataProvider
     * @covers       \D3\DIContainerHandler\d3DicHandler::buildContainer
     */
    public function buildContainerTest(bool $productive, int $debug, bool $notInTest, bool $cacheFileExist, bool $cachedContainer): void
    {
        $cachedContainerMock = $this->getMockBuilder(d3DIContainerCache::class)
            ->getMock();

        /** @var ContainerBuilder|MockObject $containerBuilderMock */
        $containerBuilderMock = $this->getMockBuilder(ContainerBuilder::class)->onlyMethods([ 'compile' ])->getMock();
        $containerBuilderMock->expects($this->exactly((int) ! $cachedContainer))->method('compile');

        /** @var Config|MockObject $configMock */
        $configMock = $this->getMockBuilder(Config::class)
            ->onlyMethods(['isProductiveMode', 'getConfigParam'])
            ->getMock();
        $configMock->method('isProductiveMode')->willReturn($productive);
        $configMock->method('getConfigParam')->willReturnMap([['iDebug', $debug]]);

        /** @var d3DicHandler|MockObject $sut */
        $sut = $this->getMockBuilder(d3DicHandler::class)
            ->onlyMethods(['d3GetConfig', 'd3GetCacheContainer', 'getContainerBuilder', 'isNotInTest', 'cacheFileExists'])
            ->getMock();
        $sut->method('d3GetConfig')->willReturn($configMock);
        $sut->expects($this->exactly((int) $cachedContainer))->method('d3GetCacheContainer')->willReturn($cachedContainerMock);
        $sut->expects($this->exactly((int) !$cachedContainer))->method('getContainerBuilder')->willReturn($containerBuilderMock);
        $sut->method('isNotInTest')->willReturn($notInTest);
        $sut->method('cacheFileExists')->willReturn($cacheFileExist);

        $this->assertSame(
            $cachedContainer ? $cachedContainerMock : $containerBuilderMock,
            $this->callMethod(
                $sut,
                'buildContainer',
                ['false']
            )
        );
    }

    /**
     * @return Generator
     */
    public function buildContainerTestDataProvider(): Generator
    {
        yield 'notProductive'       => [false, 0, false, true, false];
        yield 'debug'               => [true, 1, false, true, false];
        yield 'inTest'              => [true, 0, false, true, false];
        yield 'cacheFileNotExist'   => [true, 0, false, false, false];
        yield 'cachedContainer'     => [true, 0, true, true, true];
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::getContainerBuilder
     */
    public function getContainerBuilderTest(): void
    {
        $sut = new d3DicHandler();

        $this->assertInstanceOf(
            ContainerBuilder::class,
            $this->callMethod(
                $sut,
                'getContainerBuilder'
            )
        );
    }
}
