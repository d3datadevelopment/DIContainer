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

use InvalidArgumentException;

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
        $this->addYamlDefinitions('d3/modcfg/Config/services.yaml');
    }

    /**
     * @param string $definitionFile
     * @param string $type
     *
     * @return void
     */
    public function addDefinitions(string $definitionFile, string $type): void
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new InvalidArgumentException( 'invalid definition file type');
        }

        $this->definitionFiles[$type][md5($definitionFile)] = $definitionFile;
    }

    /**
     * @param string $definitionFile
     *
     * @return void
     */
    public function addYamlDefinitions(string $definitionFile): void
    {
        $this->addDefinitions($definitionFile, self::TYPE_YAML);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getDefinitions(string $type): array
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new InvalidArgumentException( 'invalid definition file type');
        }

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
     * @param string $definitionFile
     * @return bool
     */
    public function has(string $definitionFile): bool
    {
        return isset($this->definitionFiles[md5($definitionFile)]);
    }

    public function getAll(): array
    {
        return $this->definitionFiles;
    }

    public function clear(): void
    {
        $this->definitionFiles = [];
    }
}