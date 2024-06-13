<?php
/**
 * Created by PhpStorm
 * User: taniamolina
 * Date: 8/2/23
 * project: yorknow
 */
declare(strict_types=1);


namespace Tan\TagLine\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class TagLineAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /**
     * TagLineAttribute constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory          $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        try {
            $eavSetup->addAttribute(
                Product::ENTITY,
                'tag_line',
                [
                    'type' => 'text',
                    'label' => 'Tag Line',
                    'input' => 'textarea',
                    'required' => false,
                    'sort_order' => 75,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'wysiwyg_enabled' => false,
                    'is_html_allowed_on_front' => false,
                    'group' => 'Product Details',
                    'used_in_product_listing' => false,
                    'user_defined' => true,
                    'unique' => false,
                    'store_label' => 'Tag Line',
                    'backend' => \Tan\TagLine\Model\Attribute\Backend\TagLine::class,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'is_searchable_in_grid' => false
                ]
            );
        } catch (LocalizedException|\Zend_Validate_Exception $e) {
            echo $e->getMessage();
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->removeAttribute(Product::ENTITY, 'tag_line');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
