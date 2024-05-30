<?php
namespace Tan\ApplyCoupon\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class LinkWithRedirection extends Column
{
    const COUPON_LINK_URL_WITHOUT_REDIRECTION = 'applycoupon/applycouponlink/index';

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
                    if (trim($item['redirect_url'])) {
                        $item[$name] = $this->urlBuilder->getBaseUrl().
                            self::COUPON_LINK_URL_WITHOUT_REDIRECTION."/coupon_code/".
                            $item['coupon_code'].'/redirect_url/'.$item['redirect_url'];
                    } else {
                        $item[$name] = "redirect Url requird";
                    }
                }
            }
        }
        return $dataSource;
    }
}
