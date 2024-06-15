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

use d3DIContainerCache;
use Exception;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class d3DicHandler implements d3DicHandlerInterface
{
    protected static Container|null $_instance = null;

    public static array $circularReferenceMethodNames = [
        'getViewConfig',
    ];

    /**
     * @return Container
     * @throws d3DicException
     */
    public static function getInstance(): Container
    {
        return oxNew(d3DicHandler::class)->createInstance();
    }

    /**
     * get instance
     * @throws d3DicException
     */
    public static function getUncompiledInstance(): Container
    {
        return oxNew(d3DicHandler::class)->createInstance(false);
    }

    public static function removeInstance(): void
    {
        self::$_instance = null;
    }

    public function createInstance(bool $compiled = true): Container
    {
        try {
            $functionName = $this->getFunctionNameFromTrace();
            if (in_array(strtolower($functionName), array_map('strtolower', self::$circularReferenceMethodNames))) {
                throw oxNew(Exception::class, 'method ' . $functionName . " can't use DIC due the danger of circular reference");
            }

            if (null == self::$_instance) {
                self::$_instance = $this->buildContainer($compiled);
            }
        } catch (Exception $exception) {
            throw new d3DicException($exception);
        }

        return self::$_instance;
    }

    protected function getFunctionNameFromTrace()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $caller = $trace[1];
        return $caller['function'];
    }

    public function d3GetConfig(): Config
    {
        return Registry::getConfig();
    }

    public function d3GetCacheFilePath(): string
    {
        return $this->d3GetConfig()->getConfigParam('sCompileDir').'/d3DicContainer_'.Registry::getConfig()->getShopId().'.php';
    }

    public function d3GetCacheContainer(): Container
    {
        require_once $this->d3GetCacheFilePath();
        return oxNew(d3DIContainerCache::class);
    }

    public function d3GetFileLoader(ContainerBuilder $container): YamlFileLoader
    {
        /** @var YamlFileLoader $fileLoader */
        $fileLoader = oxNew(
            YamlFileLoader::class,
            $container,
            oxNew(FileLocator::class, d3DicUtilities::getVendorDir())
        );

        return $fileLoader;
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     * @throws Exception
     */
    public function loadFiles(ContainerBuilder $container): void
    {
        $loader = $this->d3GetFileLoader($container);

        $fileContainer = oxNew(definitionFileContainer::class);
        foreach ($fileContainer->getYamlDefinitions() as $file) {
            $fullPath = d3DicUtilities::getVendorDir().$file;
            if (is_file($fullPath)) {
                $loader->load($file);
            }
        }
    }

    protected function cacheFileExists(): bool
    {
        return file_exists($this->d3GetCacheFilePath());
    }

    /**
     * @param bool $compileAndDump
     * @return Container
     * @throws Exception
     */
    public function buildContainer(bool $compileAndDump = true): Container
    {
        startProfile(__METHOD__);

        if ($this->d3UseCachedContainer()) {
            $container = $this->d3GetCacheContainer();
        } else {
            $container = $this->getContainerBuilder();
            $this->loadFiles($container);

            if ($compileAndDump) {
                $container->compile();
                $dumper = $this->getPhpDumper($container);
                file_put_contents($this->d3GetCacheFilePath(), $dumper->dump(['class' => 'd3DIContainerCache']));
            }
        }

        stopProfile(__METHOD__);

        return $container;
    }

    protected function d3UseCachedContainer(): bool
    {
        $config = $this->d3GetConfig();

        return $config->isProductiveMode()
//            && !$config->getConfigParam('iDebug')
            && $this->cacheFileExists();
    }

    public function getContainerBuilder(): ContainerBuilder
    {
        return oxNew(ContainerBuilder::class);
    }

    public function getPhpDumper(ContainerBuilder $containerBuilder): PhpDumper
    {
        return new PhpDumper($containerBuilder);
    }
}
