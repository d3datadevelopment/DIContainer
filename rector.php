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

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\FunctionLike\MixedTypeRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/.',
    ]);

    $rectorConfig->bootstrapFiles([
        __DIR__.'/../../oxid-esales/oxideshop-ce/source/oxfunctions.php',
        __DIR__.'/../../oxid-esales/oxideshop-ce/source/overridablefunctions.php',
    ]);

    $rectorConfig->skip(
        [
            MixedTypeRector::class,                             // shouldn't remove argument annotations
        ]
    );

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_80,     // lowest possible PHP version for this plugin is 8.0
        SetList::TYPE_DECLARATION,
        SetList::INSTANCEOF,
        SetList::EARLY_RETURN,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
    ]);

    $rectorConfig->importNames();
};
