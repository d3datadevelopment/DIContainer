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

use D3\DIContainerHandler\d3DicException;
use D3\DIContainerHandler\d3DicHandler;
use Symfony\Component\DependencyInjection\Container;

/**
 * @return Container
 * @throws d3DicException
 */
function d3GetOxidDIC_withExceptions(): Container
{
    return d3DicHandler::getInstance();
}

/**
 * @return Container
 */
function d3GetOxidDIC(): Container
{
    try {
        return d3GetOxidDIC_withExceptions();
    // @codeCoverageIgnoreStart
    } catch (d3DicException $exception) {
        trigger_error($exception->getMessage(), E_USER_ERROR);
    }
    // @codeCoverageIgnoreEnd
}
