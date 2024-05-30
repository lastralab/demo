<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/29/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Model\Queue;

use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Message\ManagerInterface;
use Tan\BadWeather\Services\WeatherFlags;
use Tan\BadWeather\Model\WeatherIntegration;

class Consumer
{
    const CONSUMER_NAME = 'updatecustomer.temperature';
    const QUEUE_NAME = 'updatecustomer.temperature';

    private WeatherFlags $weatherFlags;
    private ManagerInterface $messageManager;
    private JsonHelper $jsonHelper;
    private WeatherIntegration $weatherIntegration;

    /**
     * @param WeatherIntegration $weatherIntegration
     * @param JsonHelper $jsonHelper
     * @param ManagerInterface $messageManager
     * @param WeatherFlags $weatherFlags
     */
    public function __construct(
        WeatherIntegration  $weatherIntegration,
        JsonHelper $jsonHelper,
        ManagerInterface $messageManager,
        WeatherFlags $weatherFlags
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->messageManager = $messageManager;
        $this->weatherFlags = $weatherFlags;
        $this->weatherIntegration = $weatherIntegration;
    }

    /**
     * @param string $request
     * @return void
     */
    public function process(string $request): void
    {
        try {
            $data = $this->jsonHelper->jsonDecode($request, true);
            foreach ($data as $message) {
                $customerId = $message['customer_id'];
                $customerIp = $message['customer_ip'];
                $celsius = $this->weatherIntegration->getCurrentTemperature($customerIp);
                $this->weatherFlags->setCustomerIp($customerId, $customerIp);
                $this->weatherFlags->setTemperature($customerId, $celsius);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('[Tan_BadWeather] Consumer Error: ' . $e->getMessage());
        }
    }
}
