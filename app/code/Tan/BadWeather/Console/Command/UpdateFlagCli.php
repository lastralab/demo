<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/06/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Console\Command;

use Magento\Customer\Model\Customer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Model\ResourceModel\Online\Grid\Collection;
use Tan\BadWeather\Model\WeatherIntegration;
use Tan\BadWeather\Services\WeatherFlags;

class UpdateFlagCli extends Command
{
    private Collection $loggedIn;
    private WeatherFlags $weatherFlags;
    private WeatherIntegration $weatherIntegration;

    /**
     * @param Collection $loggedIn
     * @param WeatherFlags $weatherFlags
     * @param WeatherIntegration $weatherIntegration
     */
    public function __construct(
        Collection $loggedIn,
        WeatherFlags $weatherFlags,
        WeatherIntegration $weatherIntegration
    ) {
        parent::__construct();
        $this->loggedIn = $loggedIn;
        $this->weatherFlags = $weatherFlags;
        $this->weatherIntegration = $weatherIntegration;
    }
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('tan:temperature:update-flag')
            ->setDescription('Update customer temperature flag entries via CLI');
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $output->writeln("Updating customer temperature flag...");
        $collection = $this->loggedIn->getItems();
        foreach ($collection as $customer) {
            try {
                /** @var Customer $customer */
                $ip = $this->weatherFlags->getCustomerIp($customer->getDataByKey('customer_id'));
                if (!empty($ip)) {
                    $degrees = $this->weatherIntegration->getCurrentTemperature($ip);
                    $this->weatherFlags->setTemperature(
                        $customer->getDataByKey('customer_id'),
                        (float)$degrees
                    );
                    $output->writeln(sprintf(
                        '<info>%s</info>',
                        'Updated temperature: ' . $degrees . '. id: ' . $customer->getDataByKey('customer_id')
                    ));
                } else {
                    $output->writeln(sprintf(
                        '<error>%s</error>',
                        'Empty IP for customer ID: ' . $customer->getDataByKey('customer_id')
                    ));
                }
            } catch (\Exception $e) {
                $output->writeln(sprintf(
                    '<error>%s</error>',
                    $e->getMessage()
                ));
                $exitCode = 1;
            }
        }
        $output->writeln("Weather Flags updated.");
        return $exitCode;
    }
}

