<?php
namespace Tan\ApplyCoupon\Model;

use Tan\ApplyCoupon\Api\Data\ApplyCouponInterface;
use Magento\Framework\Model\AbstractModel;

class ApplyCoupon extends AbstractModel implements ApplyCouponInterface
{
    protected function _construct()
    {
        $this->_init('Tan\ApplyCoupon\Model\ResourceModel\ApplyCoupon');
    }
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    public function setRuleName($ruleName)
    {
        return $this->setData(self::RULE_NAME, $ruleName);
    }

    public function getRuleName()
    {
        return $this->getData(self::RULE_NAME);
    }

    public function getCouponCode()
    {
        return $this->getData(self::COUPON_CODE);
    }

    public function setCouponCode($couponCode)
    {
        return $this->setData(self::COUPON_CODE, $couponCode);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    public function getCodeId()
    {
        return $this->getData(self::CODE_ID);
    }

    public function setCodeId($codeId)
    {
        return $this->setData(self::CODE_ID, $codeId);
    }

    public function getViews()
    {
        return $this->getData(self::VIEWS);
    }

    public function setViews($views)
    {
        return $this->setData(self::VIEWS, $views);
    }

    public function getCustomerGroup()
    {
        return $this->getData(self::CUSTOMER_GROUP);
    }

    public function setCustomerGroup($customerGroup)
    {
        return $this->setData(self::CUSTOMER_GROUP, $customerGroup);
    }
}
