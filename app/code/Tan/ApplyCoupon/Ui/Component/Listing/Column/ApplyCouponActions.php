<?php
namespace Tan\ApplyCoupon\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ApplyCouponActions extends Column
{
    const COUPON_URL_PATH_DELETE = 'applycoupon/Action/delete';

    private $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::COUPON_URL_PATH_DELETE,
                            ['id' => $item['entity_id']]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.rule_name }"'),
                            'message' => __('Are you sure you want to delete the "${ $.$data.rule_name }" coupon code link record?')
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
