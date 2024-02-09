<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/09/2024
 */

namespace Tan\Checkout\Plugin\Checkout\Model;

use Magento\Checkout\Block\Cart\Sidebar;
use Magento\Framework\UrlInterface;

class ClearCart
{
    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * Plugin constructor.
     * @param UrlInterface $url
     */
    public function __construct(
        UrlInterface $url
    ) {
        $this->url = $url;
    }

    /**
     * @param Sidebar $subject
     * @param array $result
     * @return array
     */
    public function afterGetConfig(
        Sidebar $subject,
        array   $result
    ) {
        $result['emptyMiniCart'] = $this->url->getUrl('minicart/cart/EmptyCart');
        return $result;
    }
}
