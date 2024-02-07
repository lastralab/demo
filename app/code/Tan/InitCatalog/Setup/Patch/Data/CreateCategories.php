<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/02/2024
 */

namespace Tan\InitCatalog\Setup\Patch\Data;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Catalog\Model\Category;
use Magento\Framework\Registry as CollectionFactory;

class CreateCategories implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    private const CATEGORIES =
        [
            'Mexican' => [
                'Satan\'s favorites',
                'Cute salsas'
            ],
            'Other' => [
                'Sweet Hot',
                'For fish'
            ],
            'Samplers' => [],
            'Specials' => []
        ];

    private const ROOT_PATH = '1/2/';

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Logger $logger,
        StoreManagerInterface $storeManager,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        CollectionFactory $collectionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->categoryFactory = $categoryFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
        $this->categoryRepository = $categoryRepository;
        $this->collectionFactory = $collectionFactory;
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
            foreach (self::CATEGORIES as $parent => $children) {
                $parentId = $this->fetchOrCreateProductCategory($parent, 'root');
                if (is_array($children) && count($children) > 0) {
                    foreach ($children as $k => $child) {
                        $id = $this->fetchOrCreateProductCategory((string)$child, $parentId);
                        $this->logger->info('[Tan_InitCatalog] Created category ID:' . $id);
                    }
                }
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), []);
            return;
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @param $categoryName
     * @param string $parent
     * @return string
     * @throws NoSuchEntityException
     */
    private function fetchOrCreateProductCategory($categoryName, string $parent = ''): string
    {
        $parentId = $parent == 'root' ? $this->storeManager->getStore()->getRootCategoryId() : $parent;

        $parentCategory = $this->categoryFactory->create()->load($parentId);

        $category = $this->categoryFactory->create();

        /** @var Category $cat */
        $cat = $category->getCollection()
            ->addAttributeToFilter('name', $categoryName)
            ->getFirstItem();

        if ($cat->getId() == null) {
            $path = $parentCategory->getPath();
            $category->setParentId($parentId)
                ->setName($categoryName)
                ->setIsActive(true);
            try {
                $category->setPath($path . '/' . $category->getId());
                $this->categoryRepository->save($category);
                return (string) $category->getId();
            } catch (\Exception $e) {
                $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['name' => $categoryName]);
            }
        } else {
            $this->logger->info('[Tan_InitCatalog] Found category ID: ' . $cat->getId(), ['patch' => 'CreateCategories']);
        }
        return (string) $cat->getId();
    }

    /**
     * @return void
     */
    public function revert():void
    {
        /** Run this method after deleting patch from patch_list */
        try {
            $allCategories = $this->categoryFactory->create()->getCollection();
            $this->collectionFactory->register("isSecureArea", true);
            foreach ($allCategories as $category) {
                /** @var Category $category */
                if (str_contains($category->getPath(), self::ROOT_PATH)) {
                    $this->logger->info('[Tan_InitCatalog] Deleting category ID:' . $category->getId());
                    $this->categoryRepository->delete($category);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['patch' => 'CreateCategories']);
        }
    }
}
