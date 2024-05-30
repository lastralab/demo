<?php
namespace Tan\ApplyCoupon\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\Data\Form\FormKey;

class SendMail extends Column
{

    private $urlBuilder;
    private $formKey;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        FormKey $formKey,
        array $components = [],
        array $data = []
    ) {
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
                    $item[$name . '_html'] = "<button class='button'><span>".__("Send Email")."</span></button>";
                    $item[$name . '_title'] = __('Send Coupon Code Link to Email');
                    $item[$name . '_entity_id'] = $item['entity_id'];
                    $item[$name . '_coupon'] = $item['coupon_code'];
                    $item[$name . '_couponlink'] = $item['link_without_redirection'];
                    $item[$name . '_couponwithlink'] = $item['link_with_redirection'];
                    $item[$name . '_formkry'] = $this->formKey->getFormKey();
                    $item[$name . '_formaction'] = $this->urlBuilder->getUrl('applycoupon/action/sendmail');
                }
            }
        }
        return $dataSource;
    }
}
