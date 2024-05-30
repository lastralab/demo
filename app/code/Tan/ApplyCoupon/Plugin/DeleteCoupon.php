<?php

namespace Tan\ApplyCoupon\Plugin;

use \Tan\ApplyCoupon\Model\ApplyCouponFactory;
use Tan\ApplyCoupon\Helper\Data;

class DeleteCoupon
{
    protected $postFactory;
    private $helper;
    public function __construct(
        Data $helper,
        ApplyCouponFactory $postFactory
    ) {
        $this->helper = $helper;
        $this->postFactory = $postFactory;
    }
    public function afterExecute(\Magento\SalesRule\Controller\Adminhtml\Promo\Quote\Delete $subject, $result)
    {
        if (!$this->helper->getEnabled()) {
            return $result;
        }
        $postData = $subject->getRequest()->getParam('id');
        $linkCoupon = $this->postFactory->create();
        if ($postData) {
            $linkCoupon->load($postData);
            $linkCoupon->delete();
        }
        return  $result;
    }
}
