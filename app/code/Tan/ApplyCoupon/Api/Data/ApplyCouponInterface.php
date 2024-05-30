<?php

namespace Tan\ApplyCoupon\Api\Data;

interface ApplyCouponInterface
{
    const ENTITY_ID = 'entity_id';
    const RULE_NAME = 'rule_name';
    const CODE_ID = 'code_id';
    const COUPON_CODE = 'coupon_code';
    const STATUS = 'status';
    const WEBSITE_ID = 'website_id';
    const VIEWS = 'views';
    const CUSTOMER_GROUP = 'customer_group';

    public function getEntityId();

    public function setEntityId($entityId);

    public function getRuleName();

    public function setRuleName($ruleName);

    public function getCouponCode();

    public function setCouponCode($couponCode);

    public function getStatus();

    public function setStatus($status);

    public function getWebsiteId();

    public function setWebsiteId($websiteId);

    public function getCodeId();

    public function setCodeId($codeId);

    public function getViews();

    public function setViews($views);

    public function getCustomerGroup();

    public function setCustomerGroup($customerGroup);
}
