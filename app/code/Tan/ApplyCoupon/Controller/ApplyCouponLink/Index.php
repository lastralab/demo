<?php

namespace Tan\ApplyCoupon\Controller\ApplyCouponLink;

use Tan\ApplyCoupon\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Checkout\Model\Session;
use Tan\ApplyCoupon\Model\ApplyCouponFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\SalesRule\Model\Coupon;
use Magento\SalesRule\Model\Rule;
use Magento\Customer\Model\Session as CustomerSession;

class Index extends Action
{
    protected $checkoutSession;
    private $helper;
    protected $messageManager;
    protected $applyCouponFactory;
    protected $storeManager;
    protected $coreSession;
    protected $coupon;
    protected $saleRule;
    protected $customerSession;

    public function __construct(
        Data $helper,
        StoreManagerInterface $storeManager,
        Context $context,
        Session $checkoutSession,
        ApplyCouponFactory $applyCouponFactory,
        ManagerInterface $messageManager,
        SessionManagerInterface $coreSession,
        Coupon $coupon,
        Rule $saleRule,
        CustomerSession $customerSession
    ) {
        $this->coreSession = $coreSession;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->applyCouponFactory = $applyCouponFactory;
        $this->checkoutSession = $checkoutSession;
        $this->messageManager = $messageManager;
        $this->resultFactory = $context->getResultFactory();
        $this->coupon = $coupon;
        $this->saleRule = $saleRule;
        $this->customerSession = $customerSession;
        return parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->helper->getEnabled()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $url = $this->_url->getBaseUrl();
            return $resultRedirect->setUrl($url);
        }
        $couponCode = $this->getRequest()->getParam('coupon_code');
        $couponCodeRedirect = $this->getRequest()->getParam('redirect_url');
        $redirectUrl = '';

        if($couponCodeRedirect == 'no'){
            $redirectUrl = 'no';
        }

        if ($couponCode) {
            try {
                $cAvailable = 0;
                $applyCoupon = $this->applyCouponFactory->create();
                $webSitesId = $this->storeManager->getStore()->getWebsiteId();
                $collection = $applyCoupon->getCollection();
                $customerGroup = 0;
                if($this->customerSession->isLoggedIn()){
                    $customerGroup = $this->customerSession->getCustomer()->getGroupId();
                }
                $customerGroupId = in_array($customerGroup,$this->getValidCoupon($couponCode));
                foreach ($collection->getData() as $data) {
                    if ($data['coupon_code'] == $couponCode && $data['status'] == 1 &&
                        $data['website_id'] == $webSitesId ) {
                        if($customerGroupId){
                            $cId = $data['entity_id'];
                            $views = $data['link_view'];
                            $cAvailable = 1;
                            if($data['redirect_url'] && $redirectUrl == ''){
                                $redirectUrl = $data['redirect_url'];
                            }
                        }else{
                            $cId = $data['entity_id'];
                            $views = $data['link_view'];
                            $cAvailable = 0;
                            if($data['redirect_url'] && $redirectUrl == ''){
                                $redirectUrl = $data['redirect_url'];
                            }
                        }
                    }
                }
                if($cAvailable == 1){
                    $views++;
                    $recored = $this->applyCouponFactory->create()->load($cId);
                    $recored->setData('link_view',$views);
                    $recored->save();
                }
                if ($cAvailable == 1) {
                    $this->coreSession->setPopup(1);
                    $this->checkoutSession->setLinkCoupon($couponCode);
                    $this->checkoutSession->getQuote()->setCouponCode($couponCode)
                        ->collectTotals()
                        ->save();
                } else {
                    $this->checkoutSession->getQuote()->setCouponCode('')
                        ->collectTotals()
                        ->save();
                    $this->checkoutSession->unsLinkCoupon();
                    $this->coreSession->setPopup(2);
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $url = $this->_url->getBaseUrl();

        if ($redirectUrl == 'no' || $redirectUrl == '') {
            $resultRedirect->setUrl($url);
        } else {
            $resultRedirect->setUrl($redirectUrl);
        }
        return $resultRedirect;
    }
    public function getValidCoupon($couponCode)
    {
        $ruleId =   $this->coupon->loadByCode($couponCode)->getRuleId();
        $rule = $this->saleRule->load($ruleId);
        return $rule->getCustomerGroupIds();
    }
}
