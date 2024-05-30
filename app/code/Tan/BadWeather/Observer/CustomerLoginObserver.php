<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/06/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Observer;

use Magento\Customer\Model\Customer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Json\Helper\Data;
use Tan\BadWeather\Model\Queue\Consumer;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class CustomerLoginObserver implements ObserverInterface
{
    private PublisherInterface $publisher;
    private ManagerInterface $manager;
    private Data $jsonHelper;
    private RemoteAddress $remoteAddress;

    /**
     * @param PublisherInterface $publisher
     * @param ManagerInterface $manager
     * @param Data $jsonHelper
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        PublisherInterface $publisher,
        ManagerInterface $manager,
        Data $jsonHelper,
        RemoteAddress  $remoteAddress
    ) {
        $this->publisher = $publisher;
        $this->manager = $manager;
        $this->jsonHelper = $jsonHelper;
        $this->remoteAddress = $remoteAddress;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var Customer $customer */
        $customer = $observer->getData('customer');
        $data[] = [
            'customer_id' => $customer->getId(),
            'customer_ip' => $this->remoteAddress->getRemoteAddress()
        ];
        $this->publisher->publish(
            Consumer::CONSUMER_NAME,
            $this->jsonHelper->jsonEncode($data)
        );
    }
}

