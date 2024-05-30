<?php
namespace Tan\ApplyCoupon\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\Data\Form\FormKey;
use Tan\ApplyCoupon\Helper\Data;

class SocialShare extends Column
{

    private $urlBuilder;
    private $formKey;
    private $helper;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        FormKey $formKey,
        Data $helper,
        array $components = [],
        array $data = []
    ) {
        $this->helper = $helper;
        $this->urlBuilder = $urlBuilder;
        $this->formKey = $formKey;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    $item[$name . '_html'] = "<button class='button'><span>".__("Share Link")."</span></button>";
                    $item[$name . '_title'] = __('Share Coupon Code Link to Social Media');
                    $item[$name . '_entity_id'] = $item['entity_id'];
                    $item[$name . '_coupon'] = $item['coupon_code'];
                    $item[$name . '_couponlink'] = $item['link_without_redirection'];
                    $item[$name . '_formkry'] = $this->formKey->getFormKey();
                    $item[$name . '_fbappid'] = $this->helper->getFbAppId();
                }
            }
        }
        return $dataSource;
    }
}
