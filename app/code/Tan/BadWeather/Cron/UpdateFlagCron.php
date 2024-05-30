<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/06/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Cron;

use Magento\Customer\Model\Customer;
use Tan\BadWeather\Model\WeatherIntegration;
use Tan\BadWeather\Model\WeatherIntegration as ManagerInterface;
use Tan\BadWeather\Services\WeatherFlags;
use Magento\Customer\Model\ResourceModel\Online\Grid\Collection;

class UpdateFlagCron
{
    private Collection $customerCollection;
    private WeatherFlags $weatherFlags;
    private ManagerInterface $weatherIntegration;

    /**
     * @param Collection $customerCollection
     * @param WeatherFlags $weatherFlags
     * @param ManagerInterface $weatherIntegration
     */
    public function __construct(
        Collection $customerCollection,
        WeatherFlags $weatherFlags,
        WeatherIntegration $weatherIntegration
    ) {
        $this->customerCollection = $customerCollection;
        $this->weatherFlags = $weatherFlags;
        $this->weatherIntegration = $weatherIntegration;
    }
    /**
     * @return void
     */
    public function execute(): void
    {
        $loggedIn = $this->customerCollection->getItems();
        foreach ($loggedIn as $customer) {
            /** @var Customer $customer */
            // TODO verify
            $ip = $this->weatherFlags->getCustomerIp($customer->getId());
            $celsius = $this->weatherIntegration->getCurrentTemperature($ip);
            $this->weatherFlags->setTemperature($customer->getId(), $celsius);
        }
    }
}
