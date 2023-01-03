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

class definitionFileContainer
{
    public const TYPE_YAML = 'yml';

    protected $definitionFiles = [
        self::TYPE_YAML => []
    ];

    protected $allowedTypes = [
        self::TYPE_YAML
    ];

    public function __construct()
    {
        $this->addYamlDefinitions('d3/modcfg/Config/services.yaml');
    }

    public function addDefinitions($definitionFile, $type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new \InvalidArgumentException('invalid definition file type');
        }

        $this->definitionFiles[$type][md5($definitionFile)] = $definitionFile;
    }

    public function addYamlDefinitions($definitionFile)
    {
        $this->addDefinitions($definitionFile, self::TYPE_YAML);
    }

    public function getDefinitions($type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new \InvalidArgumentException('invalid definition file type');
        }

        return $this->definitionFiles[$type];
    }

    public function getYamlDefinitions()
    {
        return $this->getDefinitions(self::TYPE_YAML);
    }

    /**
     * @param string $definitionFile
     * @return bool
     */
    public function has($definitionFile): bool
    {
        return isset($this->definitionFiles[md5($definitionFile)]);
    }

    public function getAll()
    {
        return $this->definitionFiles;
    }

    public function clear()
    {
        $this->definitionFiles = [];
    }
}