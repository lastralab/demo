<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/05/2024
 */

namespace Tan\InitCatalog\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Pungency extends AbstractSource
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
                ['label' => __('Mild'), 'value' => 'mild'],
                ['label' => __('Spicy'), 'value' => 'spicy'],
                ['label' => __('Satan\'s Kiss'), 'value' => 'satan-s-kiss']
            ];
        }
        return $this->_options;
    }
}

