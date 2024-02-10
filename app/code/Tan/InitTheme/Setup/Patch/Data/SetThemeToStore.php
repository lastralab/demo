<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/06/2024
 */

declare(strict_types=1);

namespace Tan\InitTheme\Setup\Patch\Data;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Theme\Model\ResourceModel\Theme\Collection;
use Magento\Theme\Model\Config;
use Magento\Theme\Model\Theme;
use Psr\Log\LoggerInterface as Logger;
use Tan\InitConfig\Setup\Patch\Data\BaseConfiguration;

class SetThemeToStore implements DataPatchInterface, PatchRevertableInterface
{
    private const THEME_DESIGN_PATH = 'design/theme/theme_id';

    private const THEME_NAME = 'Tan/theme';

    private const DEFAULT_THEME_NAME = 'Magento/blank';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var WriterInterface
     */
    private WriterInterface $writer;

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Logger $logger
     * @param WriterInterface $writer
     * @param Collection $collection
     * @param Config $config
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Logger $logger,
        WriterInterface $writer,
        Collection $collection,
        Config $config
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
        $this->writer = $writer;
        $this->collection = $collection;
        $this->config = $config;
    }

    /**
     * @return array|string[]
     */
    public function getAliases():array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies():array
    {
        return [BaseConfiguration::class];
    }

    /**
     * @return void
     */
    public function apply():void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //$this->revert();
        try {
            $themes = $this->collection->loadRegisteredThemes();
            foreach ($themes as $theme) {
                /** @var Theme $theme */
                if ($theme->getCode() == self::THEME_NAME) {
                    $this->config->assignToStore(
                        $theme,
                        [Store::DEFAULT_STORE_ID],
                        ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                    );
                    $this->writer->save(
                        self::THEME_DESIGN_PATH, $theme->getThemeId(),
                        ScopeInterface::SCOPE_STORES,
                        Store::DEFAULT_STORE_ID
                    );
                }
            }
        } catch (\Exception $e) {
            $this->logger->error(
                '[Tan_InitTheme] Error: ' . $e->getMessage(),
                ['patch' => 'SetThemeToStore']
            );
            return;
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @return void
     */
    public function revert():void
    {
        /** Run this method after deleting patch from patch_list */
        try {
            $themes = $this->collection->loadRegisteredThemes();
            foreach ($themes as $theme) {
                /** @var Theme $theme */
                if ($theme->getCode() == self::DEFAULT_THEME_NAME) {
                    $this->config->assignToStore(
                        $theme,
                        [Store::DEFAULT_STORE_ID],
                        ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                    );
                }
            }
            $this->writer->delete(self::THEME_DESIGN_PATH);
        } catch (\Exception $e) {
            $this->logger->error(
                '[Tan_InitTheme] Error: ' . $e->getMessage(),
                ['patch' => 'SetThemeToStore', 'method' => 'revert()']
            );
        }
    }
}
