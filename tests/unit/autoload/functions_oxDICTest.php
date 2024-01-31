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

use D3\TestingTools\Development\CanAccessRestricted;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class functions_oxDICTest extends TestCase
{
    use CanAccessRestricted;

    /**
     * @test
     * @return void
     * @throws Exception
     */
    public function d3GetOxidDICTest(): void
    {
        $this->assertInstanceOf(
            ContainerBuilder::class,
            d3GetOxidDIC()
        );
    }
}
