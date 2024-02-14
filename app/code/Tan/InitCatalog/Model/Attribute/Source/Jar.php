<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/05/2024
 */

declare(strict_types=1);

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
                ['label' => __('946ml'), 'value' => 'one-liter'],
                ['label' => __('473ml'), 'value' => 'half-liter'],
                ['label' => __('60ml'), 'value' => 'baby']
            ];
        }
        return $this->_options;
    }
}

