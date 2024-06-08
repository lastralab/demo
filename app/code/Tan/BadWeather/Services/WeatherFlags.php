<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/29/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Services;

use Magento\Framework\FlagManager;
use Psr\Log\LoggerInterface as Logger;

class WeatherFlags
{
    const FLAG_CODE_TEMPERATURE = '_weather_temperature';
    const FLAG_CODE_CUSTOMER_IP = '_remote_address';

    private FlagManager $flagManager;
    private Logger $logger;

    /**
     * @param FlagManager $flagManager
     * @param Logger $logger
     */
    public function __construct(
        FlagManager $flagManager,
        Logger $logger
    ) {
        $this->flagManager = $flagManager;
        $this->logger = $logger;
    }

    /**
     * Set Configuration Value
     * @param mixed $customerId
     * @param mixed $ip
     * @return void
     */
    public function setCustomerIp(mixed $customerId, mixed $ip):void
    {
        $flagCode = $customerId . self::FLAG_CODE_CUSTOMER_IP;
        $this->flagManager->saveFlag($flagCode, $ip);
    }

    /**
     * Get Configuration Value From Flag By Code
     *
     * @param mixed $customerId
     * @return mixed
     */
    public function getCustomerIp(mixed $customerId):mixed
    {
        $flagCode = $customerId . self::FLAG_CODE_CUSTOMER_IP;
        return $this->flagManager->getFlagData($flagCode);
    }

    /**
     * Set Configuration Value
     * @param mixed $customerId
     * @param mixed $temperature
     * @return void
     */
    public function setTemperature(mixed $customerId, mixed $temperature):void
    {
        if ($temperature !== null) {
            $flagCode = $customerId . self::FLAG_CODE_TEMPERATURE;
            $this->flagManager->saveFlag($flagCode, $temperature);
        } else {
            $this->logger->error('[Tan_BadWeather] WeatherFlags Error: Temperature is empty');
        }
    }

    /**
     * Get Configuration Value From Flag By Code
     *
     * @param int $customerId
     * @return mixed
     */
    public function getTemperature(int $customerId):mixed
    {
        $flagCode = $customerId . self::FLAG_CODE_TEMPERATURE;
        return $this->flagManager->getFlagData($flagCode);
    }
}
