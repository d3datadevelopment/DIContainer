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

namespace D3\DIContainerHandler;

class d3DicUtilities
{
    /**
     * @param string|null $additional
     *
     */
    public static function getServiceId(string $classNameSpace, string $additional = null): string
    {
        return strtolower(
            ($additional ? $additional.'.' : '').
            $classNameSpace
        );
    }

    public static function getArgumentId(string $classNamespace, string $argumentName): string
    {
        return strtolower(
            $classNamespace.
            '.args.' .
            $argumentName
        );
    }

    public static function getVendorDir(): string
    {
        return rtrim(dirname(__FILE__, 3), '/') . '/';
    }
}
