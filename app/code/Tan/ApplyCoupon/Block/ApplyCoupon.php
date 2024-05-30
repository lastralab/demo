<?php
namespace Tan\ApplyCoupon\Block;

use Tan\ApplyCoupon\Helper\Data;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Cms\Model\Template\FilterProvider;

class ApplyCoupon extends Template
{
    protected $coreSession;
    private $helper;
    protected $filterProvider;

    public function __construct(
        Data $helper,
        Context $context,
        SessionManagerInterface $coreSession,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->coreSession = $coreSession;
        $this->filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCouponCode()
    {
        return $this->coreSession->getPopup();
    }
    public function getHelper()
    {
        return $this->helper;
    }
    public function getRemovePopup()
    {
        return $this->coreSession->unsPopup();
    }
    public function getContents($content)
    {
        return $this->filterProvider->getPageFilter()->filter($content);
    }
}
