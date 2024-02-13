<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/06/2024
 */


namespace Tan\InitConfig\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Psr\Log\LoggerInterface as Logger;

class BaseConfiguration implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * Get base_url from magento-root-directory/.env file and comment lines for production if needed
     */
    private const BASE_CONFIGURATION = [
        'web/unsecure/base_url' => 'https://app.magento.test/',
        'web/secure/base_url' => 'https://app.magento.test/',
        'web/secure/offloader_header' => 'X-Forwarded-Proto',
        'web/secure/use_in_frontend' => 1,
        'web/secure/use_in_adminhtml' => 1,
        'web/seo/use_rewrites' => 1,
        'system/full_page_cache/caching_application' => 2,
        'system/full_page_cache/ttl' => 604800,
        'catalog/search/enable_eav_indexer' => 1,
        'dev/static/sign' => 0,
        'design/head/default_title' => 0,
        'design/header/welcome' => 'This is a fake website!',
        'general/store_information/name' => 'La Cucharita',
        'design/footer/copyright' => 'Copyright © 2024-present Tanismo. All rights reserved.',
        'catalog/review/allow_guest' => 0
    ];

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
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Logger $logger
     * @param WriterInterface $writer
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Logger $logger,
        WriterInterface $writer
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
        $this->writer = $writer;
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
        return [];
    }

    /**
     * @return void
     */
    public function apply():void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //$this->revert();
        try {
            foreach (self::BASE_CONFIGURATION as $path => $value) {
                $this->writer->save($path, $value);
            }
        } catch (\Exception $e) {
            $this->logger->error(
                '[Tan_InitConfig] Error: ' . $e->getMessage(),
                ['patch' => 'BaseConfiguration']
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
            foreach (self::BASE_CONFIGURATION as $path => $value) {
                $this->writer->delete($path);
            }
        } catch (\Exception $e) {
            $this->logger->error(
                '[Tan_InitConfig] Error: ' . $e->getMessage(),
                ['patch' => 'BaseConfiguration']
            );
        }
    }
}
