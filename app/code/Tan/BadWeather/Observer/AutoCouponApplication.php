<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/06/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Observer;

use Magento\Framework\Event\ObserverInterface;

class AutoCouponApplication implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // Logic to apply coupon code automatically
    }
}

