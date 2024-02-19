<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/19/2024
 */

declare(strict_types=1);

namespace Tan\Cms\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\HTTP\Client\Curl as Request;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Serialize\SerializerInterface as Serializer;
use Tan\Backend\Helper\Data as WeatherConfig;

class Message implements ArgumentInterface
{
    /** @var RemoteAddress */
    private $remoteAddress;

    /** @var Request  */
    private Request $request;

    /** @var Serializer  */
    private Serializer $serializer;
    private WeatherConfig $weatherData;

    /**
     * @param RemoteAddress $remoteAddress
     * @param Request $request
     * @param Serializer $serializer
     * @param WeatherConfig $weatherData
     */
    public function __construct(
        RemoteAddress $remoteAddress,
        Request $request,
        Serializer $serializer,
        WeatherConfig $weatherData
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->weatherData = $weatherData;
    }

    /**
     * @returns string
     */
    public function getMessage(): string
    {
        $ip = $this->remoteAddress->getRemoteAddress();
        $data = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=" . $ip));

        if (isset($data['geoplugin_status']) && $data['geoplugin_status'] !== 200) {
            $ip = $this->weatherData->getGeneralConfig('default_ip');
            $data = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=" . $ip));
        }

        $lat = $data['geoplugin_latitude'];
        $lon = $data['geoplugin_longitude'];
        $city = $data['geoplugin_city'];
        $state = $data['geoplugin_region'];
        $this->request->get("https://api.open-meteo.com/v1/forecast?latitude=" . $lat . "&longitude=" . $lon . "&current=temperature_2m");
        $result = $this->serializer->serialize(json_decode($this->request->getBody()));
        $celsius = $this->serializer->unserialize($result)['current']['temperature_2m'];

        $degrees = match ($this->getShowUnit()) {
            0 => round($celsius) . "°C",
            1 => round($celsius * 9 / 5 + 32) . "°F",
            default => round($celsius) . "°C / " . round($celsius * 9 / 5 + 32) . "°F",
        };

        return $city . ', ' . $state . ": " . $degrees;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->weatherData->getGeneralConfig('enabled');
    }

    /**
     * @return int
     */
    private function getShowUnit(): int
    {
        return (int) $this->weatherData->getGeneralConfig('show_unit');
    }
}
