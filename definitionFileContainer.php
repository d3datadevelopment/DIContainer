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

use Assert\Assert;

class definitionFileContainer
{
    public const TYPE_YAML = 'yml';

    protected array $definitionFiles = [
        self::TYPE_YAML => [],
    ];

    protected array $allowedTypes = [
        self::TYPE_YAML,
    ];

    public function __construct()
    {
        /** keep clear */
    }

    public function addDefinitions(string $definitionFile, string $type): void
    {
        Assert::that($type)->inArray($this->allowedTypes, 'invalid definition file type');
        Assert::that(rtrim(dirname(__FILE__, 3).'/').$definitionFile)->file('invalid definition file');

        $this->definitionFiles[$type][md5($definitionFile)] = $definitionFile;
    }

    public function addYamlDefinitions(string $definitionFile): void
    {
        $this->addDefinitions($definitionFile, self::TYPE_YAML);
    }

    public function getDefinitions(string $type): array
    {
        Assert::that($type)->inArray($this->allowedTypes, 'invalid definition file type');

        return $this->definitionFiles[$type];
    }

    public function getYamlDefinitions(): array
    {
        return $this->getDefinitions(self::TYPE_YAML);
    }

    /**
     * @return array[]
     */
    public function getAll(): array
    {
        return $this->definitionFiles;
    }

    public function clear(): void
    {
        $this->definitionFiles = [];
    }
}
