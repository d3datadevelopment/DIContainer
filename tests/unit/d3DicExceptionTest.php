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

namespace D3\DIContainerHandler\tests;

use D3\DIContainerHandler\d3DicException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class d3DicExceptionTest extends TestCase
{
    /**
     * @test
     * @return void
     * @covers \D3\DIContainerHandler\d3DicException::__construct
     */
    public function canConstruct()
    {
        $previousMessage = 'previousMessage';
        $previousCode = 123;
        $previous = new InvalidArgumentException($previousMessage, $previousCode);

        $exception = new d3DicException($previous);

        $this->assertSame(
            $previous,
            $exception->getPrevious()
        );
        $this->assertSame(
            $previousMessage,
            $exception->getMessage()
        );
        $this->assertSame(
            $previousCode,
            $exception->getCode()
        );
    }
}
