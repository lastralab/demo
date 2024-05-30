<?php
namespace Tan\ApplyCoupon\Model\ApplyCoupon\Source;

use Magento\Framework\Data\OptionSourceInterface;
use \Tan\ApplyCoupon\Model\ApplyCoupon;

class Status implements OptionSourceInterface
{
    protected $coupon;

    public function __construct(ApplyCoupon $coupon)
    {
        $this-> coupon = $coupon;
    }
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->getOptionArray();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

    public static function getOptionArray()
    {
        return [1 => __('Enabled'), 0 => __('Disabled')];
    }
}
