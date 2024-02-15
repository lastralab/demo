<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/13/2024
 */

declare(strict_types=1);

namespace Tan\LayeredNavigation\Block\Navigation;

use Magento\LayeredNavigation\Block\Navigation\State as NativeState;

/**
 *
 * @api
 * @since 100.0.2
 */
class State extends NativeState
{
    /**
     * @var string
     */
    protected $_template = 'Tan_LayeredNavigation::layer/state.phtml';

}
