<?php

namespace Tan\ApplyCoupon\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Tan\ApplyCoupon\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

class SendMail extends Action
{
    protected $resultPageFactory;
    protected $transportBuilder;
    protected $inlineTranslation;
    private $helper;
    protected $storeManager;

    public function __construct(
        StateInterface $inlineTranslation,
        Context $context,
        Data $helper,
        PageFactory $resultPageFactory,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager
    )
    {
        $this->inlineTranslation = $inlineTranslation;
        $this->resultPageFactory = $resultPageFactory;
        $this->transportBuilder = $transportBuilder;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $postName = $this->getRequest()->getParam('name');
            $postEmail = $this->getRequest()->getParam('email');
            $postCoupon = $this->getRequest()->getParam('coupon');
            $postCouponLink = $this->getRequest()->getParam('coupon_link');
            $postWithCouponLink = $this->getRequest()->getParam('couponwith_link');
            $comment = $this->getRequest()->getParam('send_message');
            $linkdrop = $this->getRequest()->getParam('withredirect');
            if ($linkdrop == "with") {
                $templateVars['couponLink'] = $postWithCouponLink;
            }
            else{
                $templateVars['couponLink'] = $postCouponLink;
            }
            $toName = $postName;
            $toEmail = $postEmail;
            $templateId = $this->helper->getEmailTemplate();
            $templateVars['couponCode'] = $postCoupon;
            $templateVars['comment'] = $comment;
            $emailSender = $this->helper->getEmailSender();
            try {
                $this->notify($toName, $toEmail, $templateId, $templateVars, $emailSender);
                $this->messageManager->addSuccessMessage(__("Coupon Link is sent to " . $postEmail));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            }

            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('applycoupon/index/');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('applycoupon/index/');
        }
    }

    public function notify($name, $email, $templateId, $templateVars, $emailSender)
    {
        $receiverInfo = [
            'name' => $name,
            'email' => $email
        ];

        try {
            $this->inlineTranslation->suspend();
            $this->generateTemplate($templateVars, $receiverInfo, $templateId, $emailSender);
            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $this;
    }

    public function generateTemplate($templateVars, $receiverInfo, $templateId, $emailSender)
    {
        $this->transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFrom($emailSender)
            ->addTo($receiverInfo['email'], $receiverInfo['name']);

        return $this;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tan_ApplyCoupon::coupon');
    }
}
