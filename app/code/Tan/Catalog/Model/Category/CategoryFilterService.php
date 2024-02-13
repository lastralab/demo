<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/06/2024
 */

declare(strict_types=1);

namespace Tan\Catalog\Model\Category;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as Logger;

class CategoryFilterService
{
    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $collectionFactory
     * @param Logger $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $collectionFactory,
        Logger $logger
    ) {
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param string $categoryName
     * @return int
     */
    public function getCategoryIdByName(string $categoryName): int
    {
        try {
            $items = $this->collectionFactory->create()
                ->addAttributeToSelect('*')
                ->setStore($this->storeManager->getStore()->getId());
            foreach ($items as $item) {
                if ($item->getName() == $categoryName) {
//                    $this->logger->debug(
//                        '[Tan_Catalog] Category Filtered['. $item->getId().']: ' . $item->getName());
                    return intval($item->getId());
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('[Tan_Catalog] Category Filter Service error: ' . $e->getMessage());
        }
        return 0;
    }
}
