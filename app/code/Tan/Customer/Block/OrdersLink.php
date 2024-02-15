<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/10/2024
 */

declare(strict_types=1);

namespace Tan\Customer\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Html\Link;
use Magento\Customer\Model\Session;

class OrdersLink extends Link
{
    private Session $session;

    /**
     * @param Session $session
     * @param Context $context
     * @param array $data
     */
    public function __construct(Session $session, Context $context, array $data)
    {
        $this->session = $session;
        parent::__construct($context, $data);
    }
    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        $html = '';
        if ($this->getTemplate()) {
            return parent::_toHtml();
        }

        if ($this->session->getCustomerId() !== null) {
            $html = '<div class="my-orders-top-link"><button type="button">
                    <a href="'. $this->getBaseUrl() . $this->getPath() . '" title="Order History" class="action">'
                . $this->escapeHtml($this->getLabel()) .
                '</a></button>
                </div>
            ';
        }
        return $html;
    }
}
