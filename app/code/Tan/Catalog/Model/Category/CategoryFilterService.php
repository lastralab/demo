<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/06/2024
 */

namespace Tan\Catalog\Model\Category;

use \Magento\Catalog\Api\CategoryListInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use \Magento\Framework\Api\SearchCriteriaInterface;
use \Magento\Framework\Api\Search\FilterGroup;
use \Magento\Framework\Api\FilterBuilder;
use \Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface as Logger;

class CategoryFilterService
{
    /**
     * @var Logger
     */
    protected Logger $logger;

    protected $categoryList;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @param CategoryListInterface $categoryList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param Logger $logger
     */
    public function __construct(
        CategoryListInterface $categoryList,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        Logger $logger
    ) {
        $this->categoryList = $categoryList;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->logger = $logger;
    }

    /**
     * @param string $categoryName
     * @return int|false
     */
    public function getCategoryIdByName(string $categoryName): int|false
    {
        $filter = $this->filterBuilder
            ->setField(CategoryInterface::KEY_NAME)
            ->setConditionType('like')
            ->setValue($categoryName)
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter($filter)
            ->create();

        $items = $this->categoryList->getList($searchCriteria)->getItems();
        $categoryId = !empty($items) ? $items[0]->getId() : false;

        if (count($items) == 0 || $categoryId === null) {
            return false;
        }
        return $categoryId;
    }
}
