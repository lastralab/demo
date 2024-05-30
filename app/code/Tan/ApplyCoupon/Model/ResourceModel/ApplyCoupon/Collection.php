<?php
namespace Tan\ApplyCoupon\Model\ResourceModel\ApplyCoupon;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'tan_apply_coupon_collection';
    protected $_eventObject = 'tan_apply_coupon_collection';

    protected function _construct()
    {
        $this->_init('Tan\ApplyCoupon\Model\ApplyCoupon', 'Tan\ApplyCoupon\Model\ResourceModel\ApplyCoupon');
    }
}
