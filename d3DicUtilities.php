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

namespace D3\DIContainerHandler;

class d3DicUtilities
{
    /**
     * @param $classNameSpace
     * @param bool $additional
     * @return string
     */
    public static function getServiceId($classNameSpace, $additional = false)
    {
        return strtolower(
            ($additional ? $additional.'.' : '').
            $classNameSpace
        );
    }

    /**
     * @param $classNameSpace
     * @param $argumentName
     * @return string
     */
    public static function getArgumentId($classNamespace, $argumentName)
    {
        return strtolower(
            $classNamespace.
            '.args.' .
            $argumentName
        );
    }

    /**
     * @return string
     */
    public static function getVendorDir(): string
    {
        return rtrim(dirname(dirname(dirname(__FILE__))), '/').'/';
    }
}
