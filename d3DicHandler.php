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

use d3DIContainerCache;
use Exception;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Facts\Config\ConfigFile;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class d3DicHandler implements d3DicHandlerInterface
{
    protected static Container|null $_instance = null;

    public static array $circularReferenceMethodNames = [
        'getViewConfig'
    ];

    /**
     * get instance
     *
     * @return Container
     * @throws Exception
     */
    public static function getInstance(): Container
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $caller = $trace[1];
        $functionName = $caller['function'];

        if (in_array(strtolower($functionName), array_map('strtolower', self::$circularReferenceMethodNames)))
        {
            throw oxNew(Exception::class, 'method '.$functionName." can't use DIC due the danger of circular reference");
        }

        if (null == self::$_instance) {
            $oDicHandler = oxNew(d3DicHandler::class);
            self::$_instance = $oDicHandler->buildContainer();
        }

        return self::$_instance;
    }

    /**
     * get instance
     *
     * @return Container
     * @throws Exception
     */
    public static function getUncompiledInstance(): Container
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $caller = $trace[1];
        $functionName = $caller['function'];

        if (in_array(strtolower($functionName), array_map('strtolower', self::$circularReferenceMethodNames)))
        {
            throw oxNew(Exception::class, 'method '.$functionName." can't use DIC due the danger of circular reference");
        }

        $oDicHandler = oxNew(d3DicHandler::class);
        self::$_instance = $oDicHandler->buildContainer(false);

        return self::$_instance;
    }

    public static function removeInstance(): void
    {
        self::$_instance = null;
    }

    /**
     * @return Config
     */
    public function d3GetConfig(): Config
    {
        return Registry::getConfig();
    }

    public function d3GetCacheFilePath(): string
    {
        return $this->d3GetConfig()->getConfigParam('sCompileDir').'/d3DicContainer_'.Registry::getConfig()->getShopId().'.php';
    }

    /**
     * @return Container
     */
    public function d3GetCacheContainer(): Container
    {
        require_once $this->d3GetCacheFilePath();
        return oxNew(d3DIContainerCache::class);
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return YamlFileLoader
     */
    public function d3GetFileLoader(ContainerBuilder $container): YamlFileLoader
    {
        /** @var YamlFileLoader $fileLoader */
        $fileLoader = oxNew(YamlFileLoader::class,
            $container,
            oxNew(FileLocator::class, d3DicUtilities::getVendorDir())
        );

        return $fileLoader;
    }

    /**
     * @param ContainerBuilder $container
     *
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

    /**
     * @return bool
     */
    protected function isNotInTest(): bool
    {
        return false == defined('OXID_PHP_UNIT') || true == defined('D3_MODCFG_TEST');
    }

    /**
     * @return bool
     */
    protected function cacheFileExists(): bool
    {
        return file_exists($this->d3GetCacheFilePath());
    }

    /**
     * @param bool $compileAndDump
     *
     * @return Container
     * @throws Exception
     */
    public function buildContainer(bool $compileAndDump = true): Container
    {
        if ((bool) Registry::get( ConfigFile::class)->getVar( 'iDebug')) startProfile(__METHOD__);

        $config = $this->d3GetConfig();

        if ( $config->isProductiveMode()
             && ! $config->getConfigParam( 'iDebug' )
            && $this->isNotInTest()
            && $this->cacheFileExists()
        ) {
            $container = $this->d3GetCacheContainer();
        } else {
            $container = $this->getContainerBuilder();
            $this->loadFiles($container);

            if ($compileAndDump) {
                $container->compile();

                if ($this->isNotInTest()) {
                    $dumper = new PhpDumper($container);
                    file_put_contents($this->d3GetCacheFilePath(), $dumper->dump(array('class' => 'd3DIContainerCache')));
                }
            }
        }

        if ((bool) Registry::get( ConfigFile::class)->getVar( 'iDebug')) stopProfile(__METHOD__);

        return $container;
    }

    public function getContainerBuilder(): ContainerBuilder
    {
        return oxNew(ContainerBuilder::class);
    }

    /**
     * clone
     */
    public function __clone() {}

    /**
     * constructor
     */
    public function __construct() {}
}
