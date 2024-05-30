<?php
namespace Tan\ApplyCoupon\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const APPLY_COUPON_LINK_ENABLE = 'applycouponlink/general/enabled';
    const APPLY_COUPON_LINK_MSG = 'applycouponlink/general/addmsg';
    const APPLY_COUPON_LINK_MSG_SUCCESS = 'applycouponlink/general/addmsgsucces';
    const APPLY_COUPON_LINK_MSG_FAIL = 'applycouponlink/general/addmsgfail';
    const APPLY_COUPON_LINK_MSG_SUCCESS_IMG = 'applycouponlink/general/succesimg';
    const APPLY_COUPON_LINK_MSG_SUCCESS_DECS = 'applycouponlink/general/successDescription';
    const APPLY_COUPON_LINK_MSG_FAIL_IMG = 'applycouponlink/general/failimg';
    const APPLY_COUPON_LINK_MSG_FAIL_DECS = 'applycouponlink/general/failDescription';
    const APPLY_COUPON_LINK_EMAIL_SENDER= 'applycouponlink/notification/identity';
    const APPLY_COUPON_LINK_EMAIL_TEMPLATES = 'applycouponlink/notification/templates';

    private $storeManager;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getEnabled()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getMsg()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_MSG,
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getMsgSuccess()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_MSG_SUCCESS,
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getMsgFail()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_MSG_FAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getMsgSuccessImg()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_MSG_SUCCESS_IMG,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getMsgFailImg()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_MSG_FAIL_IMG,
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getEmailSender()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getEmailTemplate()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_EMAIL_TEMPLATES,
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getSuccessDescription()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_MSG_SUCCESS_DECS,
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getFailureDescription()
    {
        return $this->scopeConfig->getValue(
            self::APPLY_COUPON_LINK_MSG_FAIL_DECS,
            ScopeInterface::SCOPE_STORE
        );
    }
}
