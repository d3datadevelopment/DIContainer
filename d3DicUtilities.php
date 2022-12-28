<?php

/**
 * This Software is the property of Data Development and is protected
 * by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 * https://www.d3data.de
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 * @link      http://www.oxidmodule.com
 */

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
}
