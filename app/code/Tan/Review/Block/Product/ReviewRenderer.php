<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/13/2024
 */

declare(strict_types=1);

namespace Tan\Review\Block\Product;

use Magento\Review\Block\Product\ReviewRenderer as ParentRenderer;

class ReviewRenderer extends ParentRenderer
{
    /**
     * Array of available template name
     *
     * @var array
     */
    protected $_availableTemplates = [
        self::FULL_VIEW => 'Tan_Review::helper/summary.phtml',
        self::SHORT_VIEW => 'Tan_Review::helper/summary_short.phtml',
    ];
}
