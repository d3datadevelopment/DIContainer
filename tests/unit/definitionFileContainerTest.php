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

use Assert\InvalidArgumentException;
use D3\DIContainerHandler\definitionFileContainer;
use D3\TestingTools\Development\CanAccessRestricted;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class definitionFileContainerTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @test
     *
     * @throws ReflectionException
     * @dataProvider addDefinitionsTestDataProvider
     * @covers       \D3\DIContainerHandler\definitionFileContainer::addDefinitions
     * @covers       \D3\DIContainerHandler\definitionFileContainer::__construct
     */
    public function addDefinitionsTest(string $file, string $type, int $sumand, bool $expectException): void
    {
        $sut = oxNew(definitionFileContainer::class);
        $sut->clear();

        $currentCount = count($sut->getAll());

        if ($expectException) {
            $this->expectException(InvalidArgumentException::class);
        }

        $this->callMethod(
            $sut,
            'addDefinitions',
            [$file, $type]
        );

        $this->assertCount(
            $currentCount + $sumand,
            $sut->getAll()
        );
    }

    public function addDefinitionsTestDataProvider(): Generator
    {
        yield 'invalid file'    => ['foo.txt', definitionFileContainer::TYPE_YAML, 0, true];
        yield 'invalid type'    => ['d3/modcfg/Config/services.yaml', 'txt', 0, true];
        yield 'ok'              => ['d3/modcfg/Config/services.yaml', definitionFileContainer::TYPE_YAML, 1, false];
    }

    /**
     * @test
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\definitionFileContainer::addYamlDefinitions
     */
    public function addYamlDefinitionsTest(): void
    {
        $sut = $this->getMockBuilder(definitionFileContainer::class)
            ->onlyMethods(['addDefinitions'])
            ->getMock();
        $sut->expects($this->once())->method('addDefinitions');

        $this->callMethod(
            $sut,
            'addYamlDefinitions',
            ['d3/modcfg/Config/services.yaml']
        );
    }

    /**
     * @test
     *
     *
     * @throws ReflectionException
     * @covers       \D3\DIContainerHandler\definitionFileContainer::getDefinitions
     * @dataProvider getDefinitionsTestDataProvider
     */
    public function getDefinitionsTest(string $type, bool $expectException): void
    {
        $sut = oxNew(definitionFileContainer::class);

        if ($expectException) {
            $this->expectException(InvalidArgumentException::class);
        }

        $definitions = $this->callMethod(
            $sut,
            'getDefinitions',
            [$type]
        );

        $this->assertIsArray($definitions);
    }

    public function getDefinitionsTestDataProvider(): Generator
    {
        yield 'type ok' => [definitionFileContainer::TYPE_YAML, false];
        yield 'type not ok' => ['txt', true];
    }

    /**
     * @test
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\definitionFileContainer::getYamlDefinitions
     */
    public function getYamlDefinitionsTest(): void
    {
        $sut = $this->getMockBuilder(definitionFileContainer::class)
            ->onlyMethods(['getDefinitions'])
            ->getMock();
        $sut->expects($this->once())->method('getDefinitions');

        $this->callMethod(
            $sut,
            'getYamlDefinitions'
        );
    }

    /**
     * @test
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\definitionFileContainer::getAll
     */
    public function getAllTest(): void
    {
        $fixture = ['foo'];

        $sut = oxNew(definitionFileContainer::class);
        $this->setValue(
            $sut,
            'definitionFiles',
            $fixture
        );

        $this->assertSame(
            $fixture,
            $this->callMethod(
                $sut,
                'getAll'
            )
        );
    }

    /**
     * @test
     * @throws ReflectionException
     * @covers \D3\DIContainerHandler\definitionFileContainer::clear
     */
    public function clearTest(): void
    {
        $sut = oxNew(definitionFileContainer::class);
        $sut->addYamlDefinitions('d3/modcfg/Config/services.yaml');
        $sut->clear();

        $this->assertCount(
            0,
            (array) $this->callMethod(
                $sut,
                'getAll'
            )
        );
    }
}
