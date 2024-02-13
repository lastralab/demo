<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/05/2024
 */

declare(strict_types=1);

namespace Tan\InitCatalog\Setup\Patch\Data;

use Magento\Bundle\Api\Data\LinkInterface;
use Magento\Bundle\Api\Data\OptionInterface;
use Magento\Catalog\Api\Data\ProductCustomOptionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Tan\Catalog\Model\Category\CategoryFilterService;
use Magento\Framework\Filesystem\Io\File as AssetRepository;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Model\ResourceModel\Product as Resource;
use Magento\Framework\App\State;
use Magento\Catalog\Api\Data\ProductExtensionInterface;

#[\AllowDynamicProperties]
class CreateProducts implements DataPatchInterface, PatchRevertableInterface
{
    private const DEMO_IMAGES_SKU = 'salsa-verde';
    private const DEMO_IMAGES_JAR = 'half-liter';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var CategoryLinkManagementInterface
     */
    private CategoryLinkManagementInterface $categoryLinkManagement;

    /**
     * @var CategoryFilterService
     */
    private CategoryFilterService $categoryFilterService;

    /**
     * @var AssetRepository
     */
    private AssetRepository $assetRepository;

    /**
     * @var State
     */
    private State $state;

    /**
     * @var OptionInterface
     */
    private OptionInterface $optionInterface;

    /**
     * @var LinkInterface
     */
    private LinkInterface $linkInterface;

    public const PRODUCTS = [
        'Salsa Verde' => [
            'type' => 'variation',
            'sku' => self::DEMO_IMAGES_SKU,
            'options' => [],
            'description' => 'Ideally for chilaquiles, enchiladas and/or eat with chips',
            'categories' => ['Mexican', "Satan's favorites"],
            'pungency' => ['mild', 'satan-s-kiss', 'spicy'],
            'jar' => ['one-liter', 'half-liter', 'baby'],
            'vegan' => 1,
            'signature' => 1,
            'increments' => 15.00,
            'price' => 15.00,
            'weight' => 1.50,
            'visibility' => Product\Visibility::VISIBILITY_BOTH
        ],
        'Salsa Chipotle' => [
            'type' => 'variation',
            'sku' => 'salsa-chipotle',
            'options' => [],
            'description' => 'Ideally for tuna flautas',
            'categories' => ['Mexican', 'Cute salsas'],
            'pungency' => ['spicy'],
            'jar' => ['one-liter', 'half-liter', 'baby'],
            'vegan' => 0,
            'signature' => 1,
            'increments' => 10.00,
            'price' => 10.00,
            'weight' => 1.50
        ],
        'Sesame Sauce' => [
            'type' => 'simple',
            'sku' => 'sesame-sauce',
            'description' => 'Ideally for sashimi',
            'categories' => ['Other', 'For fish'],
            'pungency' => 'satan-s-kiss',
            'jar' => 'baby',
            'vegan' => 1,
            'signature' => 1,
            'price' => 15.00,
            'weight' => 1.50
        ],
        'Carrot Ginger Dressing' => [
            'type' => 'simple',
            'sku' => 'carrot-dressing',
            'description' => 'Ideally for lettuce salad',
            'categories' => ['Other', 'Sweet Hot'],
            'pungency' => 'mild',
            'jar' => 'baby',
            'vegan' => 1,
            'signature' => 1,
            'price' => 15.00,
            'weight' => 1.50
        ],
        'Sampler Pack' => [
            'type' => 'bundle',
            'sku' => 'sampler-bundle',
            'dynamic_sku' => 0,
            'skus' => [
                'carrot-dressing',
                'sesame-sauce',
                'salsa-chipotle-baby-spicy',
                'salsa-verde-baby-spicy'
            ],
            'dynamic_price' => 0,
            'description' => 'Give them a chance.',
            'categories' => ['Samplers'],
            'jar' => 'baby',
            'vegan' => 0,
            'signature' => 1,
            'price' => 40.00,
            'weight' => 1.50
        ],
        'Recipes' => [
            'type' => 'virtual',
            'sku' => 'recipes',
            'description' => 'Videos of all the recipes (English language)',
            'categories' => ['Specials'],
            'price' => 66.60
        ],
    ];

    /**
     * @var DirectoryList
     */
    private DirectoryList $directoryList;

    /**
     * private Resource $resource;
     *
     * /**
     * @param State $state
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param ProductFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryLinkManagementInterface $categoryLinkManagement
     * @param CategoryFilterService $categoryFilterService
     * @param AssetRepository $assetRepository
     * @param OptionInterface $optionInterface
     * @param DirectoryList $directoryList
     * @param LinkInterface $linkInterface
     * @param Resource $resource
     */
    public function __construct(
        State $state,
        ModuleDataSetupInterface $moduleDataSetup,
        Logger $logger,
        StoreManagerInterface $storeManager,
        ProductFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        CategoryLinkManagementInterface $categoryLinkManagement,
        CategoryFilterService $categoryFilterService,
        AssetRepository $assetRepository,
        OptionInterface $optionInterface,
        DirectoryList $directoryList,
        LinkInterface $linkInterface,
        Resource $resource
    ) {
        $this->storeManager = $storeManager;
        $this->productFactory = $productFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->categoryFilterService = $categoryFilterService;
        $this->assetRepository = $assetRepository;
        $this->_options = [];
        $this->state = $state;
        $this->optionInterface = $optionInterface;
        $this->linkInterface = $linkInterface;
        $this->resource = $resource;
        $this->directoryList = $directoryList;
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
        return [CreateAttributes::class];
    }

    /**
     * @return void
     */
    public function apply():void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        try {
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
            foreach (self::PRODUCTS as $name => $entity) {
                $this->fetchOrCreateProduct($name, $entity);
            }
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['patch' => 'CreateProducts']);
            return;
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @param string $productName
     * @param array $entity
     * @return void
     */
    private function fetchOrCreateProduct(string $productName, array $entity): void
    {
        try {
            /** @var Product $prod */
            $prod = $this->productRepository->get($entity['sku']);
        } catch (NoSuchEntityException $e) {
            // Catch exception and continue
            $prod = false;
        }
        if (!$prod) {
            try {
                switch ($entity['type']) {
                    case 'simple':
                    case 'virtual':
                        $this->createProduct($productName, $entity);
//                        $this->logger->debug('[Tan_InitCatalog] Created product SKU: ' . $entity['sku']);
                        break;
                    case 'variation':
                        // Create simple products based on variation
                        foreach ($entity['jar'] as $option) {
                            $optionProduct = array_replace($entity, [
                                'sku' => $entity['sku'] . '-' . $option,
                            ]);
                            $productName = str_replace("-", " ", $optionProduct['sku']);
                            $this->createVariation($productName, $optionProduct, str_replace('-', ' ', $option));
//                            $this->logger->debug('[Tan_InitCatalog] Created variation SKU: ' . $entity['sku']);
                        }
                        break;
                    case 'bundle':
                        foreach ($entity['skus'] as $sku) {
                            $id = $this->productRepository->get($sku)->getId();
                            $this->_options[$entity['sku']][] = $id;
                        }
                        $this->createProduct($productName, $entity);
//                        $this->logger->debug('[Tan_InitCatalog] Created bundle SKU: ' . $entity['sku']);
                        break;
                }
            } catch (\Exception $e) {
                $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['sku' => $entity['sku']]);
            }
        } else {
            $this->logger->debug('[Tan_InitCatalog] Skipping existing product: ' . $productName, ['sku' => $entity['sku']]);
        }
    }

    /**
     * @param string $productName
     * @param array $entity
     * @return void
     */
    protected function createProduct(string $productName, array $entity): void
    {
        try {
            $product = $this->productFactory->create();
            $product->setName($productName);
            $product->setTypeId($entity['type']);
            $product->setAttributeSetId($product->getDefaultAttributeSetId());
            $product->setStatus(Product\Attribute\Source\Status::STATUS_ENABLED);
            $product->setSku($entity['sku']);
            if ($entity['type'] !== 'virtual' && $entity['type'] !== 'bundle') {
                $product->setCustomAttribute('description', $entity['description']);
                $product->setCustomAttribute('pungency', $entity['pungency']);
                $product->setCustomAttribute('signature', $entity['signature']);
                $product->setCustomAttribute('jar', $entity['jar']);
                $product->setCustomAttribute('vegan', $entity['vegan']);
                $product->setWeight($entity['weight']);
            }
            $product->setWebsiteIds([$this->storeManager->getDefaultStoreView()->getWebsiteId()]);
            $product->setUrlKey($entity['sku']);
            $product->setVisibility(Product\Visibility::VISIBILITY_BOTH);
            $product->setPrice($entity['price']);

            try {
                if (str_contains($entity['sku'], self::DEMO_IMAGES_SKU) && str_contains($entity['sku'], self::DEMO_IMAGES_JAR)) {
                    $product = $this->setImages($product, $entity['pungency']);
                }
            } catch (\Exception $e) {
                $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['sku' => $entity['sku']]);
            }

            $stock = [
                'qty' => 1000,
                'is_in_stock' => 1,
                'use_config_manage_stock' => 1,
                'manage_stock' => 1,
            ];
            $product->setStockData($stock);

            /** @var ProductExtensionInterface $extensionAttributes */
            $extensionAttributes = $product->getExtensionAttributes();

            if ($entity['type'] === 'bundle') {
                /** @var Product $product */
                $optionsData = [];
                $selectionsData = [];
                $options = [];
                $id = 1;
                $optionsMap = array_combine($this->_options[$entity['sku']], $entity['skus']);
                foreach ($optionsMap as $optionId => $optionSku) {
                    $data = [
                        'title' => ucfirst(str_replace($optionSku, '-', ' ')),
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_MULTIPLE,
                        'required' => 1,
                        'delete' => ''
                    ];
                    $optionsData[] = $data;
                    $option = $this->optionInterface->setData($data)->setSku($entity['sku'])->setOptionId($id);
                    $linkData = [
                        [
                            [
                                'product_id' => $optionId,
                                'selection_qty' => 1,
                                'selection_can_change_qty' => 0,
                                'delete' => ''
                            ]
                        ]
                    ];
                    $link = $this->linkInterface
                        ->setData($linkData)
                        ->setSku($optionSku)
                        ->setQty(1000)
                        ->setCanChangeQuantity(0);
                    $selectionsData[] = $linkData;
                    $option->setProductLinks([$link]);
                    $id++;
                }
                $product->setCustomAttribute('price_view', 1); // AS_LOW_AS
                $product->setBundleOptionsData($optionsData)->setBundleSelectionsData($selectionsData);
                $extensionAttributes->setBundleProductOptions($options);
                $product->setShipmentType(0);
            }
            $product->setExtensionAttributes($extensionAttributes);
            $product = $this->productRepository->save($product);

            $categoryIds = $this->getCategoryIds($entity['categories']);
            if (!empty($categoryIds)) {
                $this->categoryLinkManagement->assignProductToCategories($product->getSku(), $categoryIds);
//                $this->logger->debug('[Tan_InitCatalog] Assigned product to categories: ' . implode(', ', $categoryIds),
//                    ['sku' => $product->getSku()]
//                );
            } else {
                $this->logger->error('[Tan_InitCatalog] Category assignment issue, product not added: ' . $productName);
            }
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['name' => $productName]);
        }
    }

    /**
     * @param string $productName
     * @param array $entity
     * @param string $option
     * @return void
     */
    protected function createVariation(string $productName, array $entity, string $option): void
    {
        try {
            foreach ($entity['pungency'] as $pungency) {
                $simple = array_replace($entity,
                    ['type' => 'simple'],
                    ['sku' => $entity['sku'] . '-' . $pungency],
                    ['pungency' => $pungency],
                    ['jar' => $option],
                    ['visibility' => Product\Visibility::VISIBILITY_NOT_VISIBLE]
                );
                $this->createProduct(ucwords($productName) . ' ' . ucfirst($pungency), $simple);
//                $this->logger->debug('[Tan_InitCatalog] Created option product SKU: ' . $simple['sku']);
            }
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['name' => $productName]);
        }
    }

    /**
     * @param array $categories
     * @return array
     */
    private function getCategoryIds(array $categories): array
    {
        $categoryIds = [];
        try {
            foreach ($categories as $k => $parent) {
                $id = $this->categoryFilterService->getCategoryIdByName($parent);
                if ($id > 0) {
                    $categoryIds[] = $id;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['patch' => 'CreateProducts']);
        }
        return $categoryIds;
    }

    /**
     * @param Product $product
     * @param string $fileName
     * @return Product
     */
    private function setImages(Product $product, string $fileName): Product
    {
        $imageUrl = $this->storeManager->getStore()->getBaseUrl()
            . 'static/frontend/Tan/theme/en_US/images/' . $fileName . '.jpg';
        try {
            $tmpDir = $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
            $this->assetRepository->checkAndCreateFolder($tmpDir);
            $newImageFile = $tmpDir . basename($imageUrl);
            $imageFile = $this->assetRepository->read($imageUrl, $newImageFile);
            if ($imageFile) {
                $product->addImageToMediaGallery(
                    $newImageFile,
                    ['image', 'small_image', 'thumbnail'],
                    true
                );
//                $this->logger->debug('[Tan_InitCatalog] Image added: ' . $newImageFile);
            }
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->logger->error('[Tan_InitCatalog] Error: ' . $e->getMessage(), ['imageUrl' => $imageUrl]);
        }
        return $product;
    }

    /**
     * @return void
     */
    public function revert():void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        foreach (self::PRODUCTS as $name => $entity) {
            $sku = $entity['sku'];
            if ($entity['type'] == 'variation') {
                $sku = '';
                foreach ($entity['jar'] as $jar) {
                    foreach ($entity['pungency'] as $pungency) {
                        $sku = $entity['sku'] . '-' . $jar . '-' . $pungency;
                        $this->deleteProduct($sku);
                    }
                }
            } else {
                $this->deleteProduct($sku);
            }
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @param string $sku
     * @return void
     */
    protected function deleteProduct(string $sku): void
    {
        try {
            $product = $this->productRepository->get($sku);
            $this->productRepository->delete($product);
            $this->logger->info('[Tan_InitCatalog] Deleted product: ' . $sku);
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitCatalog] Error deleting product: ' . $e->getMessage(),
                ['trace' => $e->getTraceAsString()]
            );
        }
    }
}
