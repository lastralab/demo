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

class AccountLink extends Link
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
            try {
                $html = '
                    <li class="my-account-top-link">
                        <a href="' . $this->getBaseUrl() . $this->getPath() . '" title="' . $this->escapeHtml($this->getLabel()) . '">
                             <i class="fa-solid fa-user"></i>
                         </a>
                    </li>
                ';
            } catch
                (\Exception $e) {
                $this->_logger->error($e->getMessage());
            }
        }

        return $html;
    }
}
