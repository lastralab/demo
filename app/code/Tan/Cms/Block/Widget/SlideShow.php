<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 06/06/2024
 */

declare(strict_types=1);

namespace Tan\Cms\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class SlideShow extends Template implements BlockInterface
{
    protected $_template = "widget/slideshow.phtml";
}
