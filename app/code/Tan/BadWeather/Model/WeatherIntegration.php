<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/06/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Model;

use Magento\Framework\HTTP\Client\Curl as Request;
use Psr\Log\LoggerInterface;
use Tan\Backend\Helper\Data as WeatherConfig;
use Magento\Framework\Serialize\Serializer\Json as Serializer;
use Psr\Log\LoggerInterface as Logger;

class WeatherIntegration
{
    private WeatherConfig $weatherConfig;
    private Request $request;
    private Serializer $serializer;
    private Logger $logger;

    /**
     * @param WeatherConfig $weatherConfig
     * @param Request $request
     * @param Serializer $serializer
     * @param Logger $logger
     */
    public function __construct(
        WeatherConfig $weatherConfig,
        Request $request,
        Serializer  $serializer,
        Logger $logger
    ) {
        $this->weatherConfig = $weatherConfig;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }
    /**
     * @param mixed $ip
     * @return mixed $celsius
     */
    public function getCurrentTemperature(mixed $ip): mixed
    {
        $ip = empty($ip) || '127.0.0.1' ? '67.43.13.30' : $ip; // Use backup IP
        try {
            $data = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=" . $ip));

            if (isset($data['geoplugin_status']) && $data['geoplugin_status'] !== 200) {
                $data = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=" . $ip));
            }

            $lat = $data['geoplugin_latitude'];
            $lon = $data['geoplugin_longitude'];
            $this->request->get("https://api.open-meteo.com/v1/forecast?latitude=" . $lat . "&longitude=" . $lon . "&current=temperature_2m");
            $result = $this->serializer->serialize(json_decode($this->request->getBody()));
            return $this->serializer->unserialize($result)['current']['temperature_2m'];
        } catch (\Exception $e) {
            $this->logger->error('[Tan_BadWeather] Error: ' . $e->getMessage());
            return null;
        }
    }
}
