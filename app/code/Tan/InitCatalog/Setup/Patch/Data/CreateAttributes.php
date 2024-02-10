<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/05/2024
 */

declare(strict_types=1);

namespace Tan\InitCatalog\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Validator\ValidateException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class CreateAttributes implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    private const ATTRIBUTES =
        [
            'varchar' => ['pungency', 'jar'],
            'int' => ['vegan', 'signature']
        ];

    const SOURCE_PATH = 'Model\Attribute\Source';

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var EavSetup
     */
    private EavSetup $setup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $setupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param EavSetup $setup
     * @param EavSetupFactory $setupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Logger $logger,
        StoreManagerInterface $storeManager,
        EavSetup $setup,
        EavSetupFactory $setupFactory
    ) {
        $this->storeManager = $storeManager;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
        $this->setup = $setup;
        $this->setupFactory = $setupFactory;
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
        return [CreateCategories::class];
    }

    /**
     * @return void
     */
    public function apply():void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //$this->revert();
        try {
            foreach (self::ATTRIBUTES as $type => $attributes) {
                foreach ($attributes as $code) {
                    if (!$this->setup->getIdByCode(Category::ENTITY, $code)) {
                        $this->createAttribute($type, $code);
                        $this->logger
                            ->debug('[Tan_InitCatalog] Created Attribute: ' . $code);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error(
                '[Tan_InitCatalog] Error: ' . $e->getMessage(),
                ['patch' => 'CreateAttributes']
            );
            return;
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @param string $type
     * @param string $code
     * @return void
     * @throws LocalizedException
     * @throws ValidateException
     */
    private function createAttribute(string $type, string $code): void
    {
        $label = $type == 'int' ? 'Is ' . ucfirst($code) : ucfirst($code);
        $input = $type == 'int' ? 'boolean' : 'select';

        $source = match ($code) {
            'pungency' => \Tan\InitCatalog\Model\Attribute\Source\Pungency::class,
            'jar' => \Tan\InitCatalog\Model\Attribute\Source\Jar::class,
            default => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
        };

        $eavSetup = $this->setupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(Product::ENTITY, $code, [
            'type' => $type,
            'backend' => '',
            'frontend' => '',
            'label' => $label,
            'input' => $input,
            'source' => $source,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'sort_order' => 50,
            'user_defined' => true,
            'default' => '',
            'searchable' => true,
            'filterable' => true,
            'comparable' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'is_html_allowed_on_frontend' => true,
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => '',
            'group' => 'General'
        ]);
    }

    /**
     * @return void
     */
    public function revert():void
    {
        /** Run this method after deleting patch from patch_list */
        try {
            foreach (self::ATTRIBUTES as $type => $attributes) {
                foreach ($attributes as $code) {
                    $entityId = $this->setup->getIdByCode(Category::ENTITY, $code);
                    if ($entityId !== null) {
                        $this->setup->delete(
                            $this->setupFactory->create()->getAttribute(Category::ENTITY, $entityId)
                        );
                        $this->logger
                            ->debug('[Tan_InitCatalog] Deleted Attribute: ' . $code);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error(
                '[Tan_InitCatalog] Error: ' . $e->getMessage(),
                ['patch' => 'CreateAttributes']);
        }
    }
}
