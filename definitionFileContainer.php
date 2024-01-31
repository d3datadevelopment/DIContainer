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

use Assert\Assert;
use Assert\InvalidArgumentException;

class definitionFileContainer
{
    public const TYPE_YAML = 'yml';

    protected array $definitionFiles = [
        self::TYPE_YAML => []
    ];

    protected array $allowedTypes = [
        self::TYPE_YAML
    ];

    public function __construct()
    {
    }

    /**
     * @param $definitionFile
     * @param $type
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function addDefinitions($definitionFile, $type): void
    {
        Assert::that($type)->inArray($this->allowedTypes, 'invalid definition file type');
        Assert::that(rtrim(dirname(__FILE__, 3).'/').$definitionFile)->file('invalid definition file');

        $this->definitionFiles[$type][md5($definitionFile)] = $definitionFile;
    }

    public function addYamlDefinitions($definitionFile): void
    {
        $this->addDefinitions($definitionFile, self::TYPE_YAML);
    }

    /**
     * @param $type
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function getDefinitions($type): array
    {
        Assert::that($type)->inArray($this->allowedTypes, 'invalid definition file type');

        return $this->definitionFiles[$type];
    }

    /**
     * @return array
     */
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