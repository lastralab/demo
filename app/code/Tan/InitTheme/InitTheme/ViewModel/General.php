<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/09/2024
 */

namespace Tan\InitTheme\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface as Logger;

class General implements ArgumentInterface
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeInterface;

    /**
     * @var Logger
     */
    private Logger $logger;

    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeInterface,
        Logger $logger
    ) {
        $this->storeManager = $storeManager;
        $this->scopeInterface = $scopeInterface;
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getStoreName(): string
    {
        $name = $this->storeManager->getDefaultStoreView()->getName();
        try {
            $name = $this->scopeInterface->getValue('general/store_information/name', ScopeInterface::SCOPE_STORE);
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitTheme] getStoreName() Error: ' . $e->getMessage());
        }
        return $name;
    }
}
