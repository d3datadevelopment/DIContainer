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

use D3\DIContainerHandler\d3DicHandler;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @return   ContainerBuilder
 */
function d3GetOxidDIC(): ContainerBuilder
{
    return d3DicHandler::getInstance();
}
