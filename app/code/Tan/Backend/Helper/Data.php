<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/19/2024
 */

declare(strict_types=1);

namespace Tan\Backend\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_TAN_DEMO_GENERAL = 'weather/general/';

    /**
     * @param string $field
     * @param null $storeId
     * @return mixed
     */
    protected function getConfigValue(string $field, $storeId = null): mixed
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig(string $code, $storeId = null): mixed
    {
        return $this->getConfigValue(self::XML_PATH_TAN_DEMO_GENERAL . $code, $storeId);
    }
}
