<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/19/2024
 */

declare(strict_types=1);

namespace Tan\Backend\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class ShowUnit extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Celsius'), 'value' => 0],
                ['label' => __('Farenheit'), 'value' => 1],
                ['label' => __('Both'), 'value' => 2]
            ];
        }
        return $this->_options;
    }
}
