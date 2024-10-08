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

namespace D3\DIContainerHandler\tests\unit\autoload;

use D3\DIContainerHandler\d3DicException;
use D3\TestingTools\Development\CanAccessRestricted;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class functions_oxDICTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @test
     * @throws Exception
     * @covers ::d3GetOxidDIC_withExceptions()
     */
    public function d3GetOxidDIC_withExceptionsTest(): void
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

        $this->assertInstanceOf(
            ContainerBuilder::class,
            d3GetOxidDIC_withExceptions()
        );
    }

    /**
     * @test
     * @throws Exception
     * @covers ::d3GetOxidDIC()
     */
    public function d3GetOxidDICTest(): void
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

        $this->assertInstanceOf(
            ContainerBuilder::class,
            d3GetOxidDIC()
        );
    }
}
