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

use D3\DIContainerHandler\d3DicException;
use D3\DIContainerHandler\d3DicHandler;
use D3\TestingTools\Development\CanAccessRestricted;
use d3DIContainerCache;
use Exception;
use Generator;
use org\bovigo\vfs\vfsStream;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class d3DicHandlerTest extends TestCase
{
    use CanAccessRestricted;

    public function setUp(): void
    {
        parent::setUp();

        d3DicHandler::removeInstance();
    }

    /**
     * @test
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
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::getUncompiledInstance
     */
    public function getUncompiledInstanceTest(): void
    {
        $sut = new d3DicHandler();

        // test new instance
        $containerBuilder = $this->callMethod(
            $sut,
            'getUncompiledInstance'
        );

        $this->assertInstanceOf(
            ContainerBuilder::class,
            $containerBuilder
        );

        $this->assertFalse($containerBuilder->isCompiled());

        // test if compiled instance is getting resetted
        $this->callMethod(
            $sut,
            'removeInstance'
        );
        $this->callMethod(
            $sut,
            'getInstance'
        );

        $containerBuilder = $this->callMethod(
            $sut,
            'getUncompiledInstance'
        );
        $this->assertFalse($containerBuilder->isCompiled());
    }

    /**
     * @test
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
     * @param bool $throwException
     * @param bool $expectException
     * @param string $circularReferenceMethod
     * @param string $expectedExceptionMessage
     * @return void
     * @throws ReflectionException
     * @covers       \D3\DIContainerHandler\d3DicHandler::createInstance
     * @dataProvider canCreateInstanceDataProvider
     */
    public function canCreateInstance(bool $throwException, bool $expectException, string $circularReferenceMethod = '', string $expectedExceptionMessage = ''): void
    {
        /** @var d3DicHandler|MockObject $sut */
        $sut = $this->getMockBuilder(d3DicHandler::class)
            ->onlyMethods(['buildContainer', 'getFunctionNameFromTrace'])
            ->getMock();
        if ($throwException) {
            $sut->method('buildContainer')->willThrowException(new Exception('fixture'));
        }

        $sut->method('getFunctionNameFromTrace')->willReturn($circularReferenceMethod);
        if ($expectException) {
            $this->expectException(d3DicException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $this->callMethod(
            $sut,
            'createInstance'
        );
    }

    public function canCreateInstanceDataProvider(): Generator
    {
        yield "don't throw exception" => [false, false];
        yield "throw exception" => [true, true, '', 'fixture'];
        yield "has circular reference method name" => [false, true, 'getViewConfig', 'method getViewConfig can\'t use DIC due the danger of circular reference'];
    }

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::getFunctionNameFromTrace
     */
    public function canGetFunctionNameFromTrace()
    {
        /** @var d3DicHandler|MockObject $sut */
        $sut = $this->getMockBuilder(d3DicHandler::class)
            ->onlyMethods(get_class_methods(d3DicHandler::class))
            ->getMock();

        $this->assertSame(
            'invokeArgs',
            $this->callMethod(
                $sut,
                'getFunctionNameFromTrace'
            )
        );
    }

    /**
     * @test
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
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::d3GetCacheFilePath
     */
    public function d3GetCacheFilePathTest(): void
    {
        $sut = new d3DicHandler();

        $this->assertMatchesRegularExpression(
            '/.*?\/tmp\/.*?DicContainer_\d+\.php$/m',
            (string) $this->callMethod(
                $sut,
                'd3GetCacheFilePath'
            )
        );
    }

    /**
     * @test
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
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::loadFiles
     */
    public function loadFilesTest(): void
    {
        /** @var ContainerBuilder|MockObject $containerBuilderMock */
        $containerBuilderMock = $this->getMockBuilder(ContainerBuilder::class)
            ->getMock();

        $fileLoaderMock = $this->getMockBuilder(YamlFileLoader::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['load'])
            ->getMock();
        $fileLoaderMock->expects($this->atLeastOnce())->method('load');

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
     * @throws ReflectionException
     * @dataProvider cacheFileExistsTestDataProvider
     * @covers \D3\DIContainerHandler\d3DicHandler::cacheFileExists
     */
    public function cacheFileExistsTest(bool $cacheExist): void
    {
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

    public function cacheFileExistsTestDataProvider(): Generator
    {
        yield 'cacheExist'  => [true];
        yield 'cacheMissing'=> [false];
    }

    /**
     * @test
     * @param bool $useCacheContainer
     * @param bool $compile
     * @param bool $useDefault
     * @return void
     * @throws ReflectionException
     * @dataProvider buildContainerTestDataProvider
     * @covers       \D3\DIContainerHandler\d3DicHandler::buildContainer
     */
    public function buildContainerTest(bool $useCacheContainer, bool $compile, bool $useDefault = false): void
    {
        $structure = [
            'source_directory'  => [],
        ];
        vfsStream::setup();
        $fsRoot = vfsStream::create($structure);

        $containerBuilderMock = $this->getMockBuilder(ContainerBuilder::class)->onlyMethods([ 'compile' ])->getMock();
        $containerBuilderMock->expects($this->exactly((int) (!$useCacheContainer && $compile)))->method('compile');

        /** @var PhpDumper|MockObject $phpDumperMock */
        $phpDumperMock = $this->getMockBuilder(PhpDumper::class)
            ->disableOriginalConstructor()
            ->onlyMethods(get_class_methods(PhpDumper::class))
            ->getMock();
        $phpDumperMock->expects($this->exactly((int) (!$useCacheContainer && $compile)))->method('dump')->willReturn('fixture');

        /** @var d3DicHandler|MockObject $sut */
        $sut = $this->getMockBuilder(d3DicHandler::class)
            ->onlyMethods(
                [
                    'd3UseCachedContainer', 'd3GetCacheContainer', 'getContainerBuilder',
                    'loadFiles','d3GetCacheFilePath', 'getPhpDumper'
                ])
            ->getMock();
        $sut->expects($this->once())->method('d3UseCachedContainer')->willReturn($useCacheContainer);
        $sut->expects($this->exactly((int) $useCacheContainer))->method('d3GetCacheContainer');
        $sut->expects($this->exactly((int) !$useCacheContainer))->method('getContainerBuilder')->willReturn($containerBuilderMock);
        $sut->expects($this->exactly((int) !$useCacheContainer))->method('loadFiles');
        $sut->method('d3GetCacheFilePath')->willReturn($fsRoot->getChild('source_directory')->url().'/DIContainer.php');
        $sut->method('getPhpDumper')->willReturn($phpDumperMock);

        $useDefault ?
            $this->assertInstanceOf(
                Container::class,
                $this->callMethod(
                    $sut,
                    'buildContainer'
                )
            ):
            $this->assertInstanceOf(
                Container::class,
                $this->callMethod(
                    $sut,
                    'buildContainer',
                    [$compile]
                )
            );

        if (!$useCacheContainer && $compile) {
            $this->assertSame(
                'fixture',
                file_get_contents($fsRoot->getChild('source_directory')->url() . '/DIContainer.php')
            );
        }
    }

    public function buildContainerTestDataProvider(): Generator
    {
        yield "can't use cached container, do compile"      => [false, true];
        yield "can't use cached container, don't compile"   => [false, false];
        yield "use cached container"                        => [true, false];
        yield "can't use cached container, do compile, default" => [false, true, true];
    }

    /**
     * @test
     * @param bool $productive
     * @param int  $debug
     * @param bool $cacheFileExist
     * @param bool $expected
     *
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::d3UseCachedContainer
     * @dataProvider canUseCachedContainerDataProvider
     */
    public function canUseCachedContainerTest(bool $productive, int $debug, bool $cacheFileExist, bool $expected)
    {
        /** @var Config|MockObject $configMock */
        $configMock = $this->getMockBuilder(Config::class)
            ->onlyMethods(['isProductiveMode', 'getConfigParam'])
            ->getMock();
        $configMock->method('isProductiveMode')->willReturn($productive);
        $configMock->method('getConfigParam')->willReturnMap([['iDebug', null, $debug]]);

        /** @var d3DicHandler|MockObject $sut */
        $sut = $this->getMockBuilder(d3DicHandler::class)
            ->onlyMethods(['d3GetConfig', 'cacheFileExists'])
            ->getMock();
        $sut->method('d3GetConfig')->willReturn($configMock);
        $sut->method('cacheFileExists')->willReturn($cacheFileExist);

        $this->assertSame(
            $expected,
            $this->callMethod(
                $sut,
                'd3UseCachedContainer'
            )
        );
    }

    public function canUseCachedContainerDataProvider(): Generator
    {
        yield "not productive"  => [false, 0, true, false];
        yield 'is debug'        => [true, 1, true, true];
        yield 'no cache file'   => [true, 0, false, false];
        yield 'can use cached'  => [true, 0, true, true];
    }

    /**
     * @test
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

    /**
     * @test
     * @return void
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\d3DicHandler::getPhpDumper
     */
    public function canGetPhpDumper(): void
    {
        /** @var ContainerBuilder|MockObject $containerBuilderMock */
        $containerBuilderMock = $this->getMockBuilder(ContainerBuilder::class)
            ->onlyMethods(['isCompiled'])
            ->getMock();
        $containerBuilderMock->method('isCompiled')->willReturn(true);

        $this->assertInstanceOf(
            PhpDumper::class,
            $this->callMethod(
                new d3DicHandler(),
                'getPhpDumper',
                [$containerBuilderMock]
            )
        );
    }
}
