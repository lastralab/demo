<?php
namespace Tan\ApplyCoupon\Model;

use Magento\Framework\Event\ObserverInterface;
use Tan\ApplyCoupon\Helper\Data;
use Magento\Checkout\Model\Session;

class ApplyCouponLink implements ObserverInterface
{
    private $checkoutSession;
    private $helper;

    public function __construct(
        Data $helper,
        Session $checkoutSession
    ) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helper->getEnabled()) {
            $linkCouponCode =  $this->checkoutSession->getLinkCoupon();
            if ($linkCouponCode != null) {
                $this->checkoutSession->getQuote()->setCouponCode($linkCouponCode)->save();
            } else {
                $this->checkoutSession->getQuote()->setCouponCode('')->save();
            }
        }
    }
}
