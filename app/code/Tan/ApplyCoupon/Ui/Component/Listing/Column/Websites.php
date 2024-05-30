<?php
namespace Tan\ApplyCoupon\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

class Websites extends Column
{
    private $storeManager;
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManager $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        try {
            if (isset($dataSource['data']['items'])) {
                foreach ($dataSource['data']['items'] as & $item) {
                    $website = $this->storeManager->getWebsite($item['website_id']);
                    if (isset($item['website_id'])) {
                        $item[$this->getData('name')] = $website->getName();
                    }
                }
            }
            return $dataSource;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
