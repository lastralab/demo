<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/05/2024
 */

namespace Tan\InitCatalog\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Jar extends AbstractSource
{
    /**
     * Get all options
     *
     * @retrun array
     */
    public function getAllOptions(): array
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('946 ml'), 'value' => 'one-liter'],
                ['label' => __('473 ml'), 'value' => 'half-liter'],
                ['label' => __('60 ml'), 'value' => 'baby']
            ];
        }
        return $this->_options;
    }
}

