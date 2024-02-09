<?php
/**
 * Created by PhpStorm
 * Project: LastraLab
 * User: taniamolina
 * Date: 5/15/21
 * @codingStandardsIgnoreFile
 */

namespace Lastra\Testing\Controller\Far;

use Magento\Framework\App\Action\HttpGetActionInterface;

class FarAway extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * View page action in /result/objects/page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        return $this->_pageFactory->create();
    }
}
