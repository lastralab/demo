<?php
/**
 * Created by PhpStorm
 * Project: L'Astra Lab
 * User: tan
 * Date: 5/15/2019
 * @codingStandardsIgnoreFile
 */

declare(strict_types=1);

namespace Lastra\Testing\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
/** Your sources to debug */

use Tan\Catalog\Model\Category\CategoryFilterService as Test;
use Magento\Framework\View\Asset\Repository as Repository;
use Tan\InitCatalog\Setup\Patch\Data\CreateProducts;

class Debugger implements ArgumentInterface
{
    private $test;
    private $repository;

    /**
     * Debugger constructor.
     * @param Test $test
     * @param Repository $repository
     */
    public function __construct(Test $test, Repository $repository)
    {
        $this->repository = $repository;
        $this->test = $test;
    }

    /**
     * Add your debugging code here
     * @return array
     */
    public function startDebugging(): array
    {
        $html = [];
        try {
            $html = array_merge($html, ['Testing class' => Test::class]);
            foreach (CreateProducts::PRODUCTS as $product => $entity) {
                foreach ($entity['categories'] as $category) {
                    $theId = $this->test->getCategoryIdByName($category);
                    $html = array_merge($html, [$category => strval($theId)]);
                }
            }
        } catch (\Exception $e) {
            $html = ['Error' => $e->getMessage()];
        }
        return $html;
    }
}
