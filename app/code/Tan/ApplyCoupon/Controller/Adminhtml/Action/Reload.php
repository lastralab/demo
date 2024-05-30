<?php

namespace Tan\ApplyCoupon\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tan\ApplyCoupon\Model\ApplyCoupon;
use Magento\SalesRule\Model\ResourceModel\Coupon\CollectionFactory;
use Magento\SalesRule\Model\Rule;

class Reload extends Action
{
    private $pageFactory;
    private $applyCoupon;
    private $salesRuleCoupon;
    private $saleRule;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ApplyCoupon $applyCoupon,
        CollectionFactory $salesRuleCoupon,
        Rule $saleRule
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->applyCoupon = $applyCoupon;
        $this->salesRuleCoupon = $salesRuleCoupon;
        $this->saleRule = $saleRule;
    }

    public function execute()
    {
        try {
            $coupons = $this->getCouponsList();
            $data = [];
                foreach ($coupons as $coupon) {
                    $rule = $this->saleRule->load($coupon['rule_id']);
                    $fromDate = $rule->getFromDate();
                    $toDate = $rule->getToDate();
                    $toDate = ($toDate) ? $toDate : date('Y-m-d');
                    $fromDate = ($fromDate) ? $fromDate : date('Y-m-d');
                    if (strtotime(date('Y-m-d')) <= strtotime($toDate)
                        && strtotime(date('Y-m-d')) >= strtotime($fromDate)
                        && $rule->getIsActive()) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }
                    $data['coupon_code'] = $coupon['code'];
                    $data['rule_name'] = $rule['name'];
                    $data['status'] = $status;
                    $data['website_id'] = $rule->getWebsiteIds();
                    $data['code_id'] = $coupon['coupon_id'];
                    $data['customer_group'] = $rule->getCustomerGroupIds();

                    $collection = $this->applyCoupon->getCollection()
                        ->addFieldToFilter("code_id", $coupon['coupon_id'])->load();
                    if ($collection->getData()) {
                        foreach ($collection->getData() as $item) {
                            $record = $this->applyCoupon->load($item['entity_id']);
                            $record->setRuleName($rule['name']);
                            $record->setCouponCode($coupon['code']);
                            $record->setStatus($status);
                            $record->setWebsiteId($rule->getWebsiteIds());
                            $record->setCodeId($coupon['coupon_id']);
                            $record->setViews($item['link_view']);
                            $record->setCustomerGroup($rule->getCustomerGroupIds());
                            $record->save();
                        }
                    } else {
                        $this->applyCoupon->setData($data)->save();
                    }
                }
                $collection = $this->applyCoupon->getCollection()->load();
                if($collection->getData()){
                    foreach ($collection->getData() as $data){
                        $ruleColl = $this->salesRuleCoupon->create()
                                    ->addFieldToFilter('coupon_id',$data['code_id']);
                        if(empty($ruleColl->getData())){
                            $delete = $this->applyCoupon->load($data['entity_id']);
                            $delete->delete();
                        }
                    }
                }
            $resultRedirect = $this->resultRedirectFactory->create();
            $url = $this->_url->getUrl('applycoupon/index/');
            $resultRedirect->setUrl($url);
            return $resultRedirect;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
    }

    public function getCouponsList()
    {
        $collection = $this->salesRuleCoupon->create();

        return $collection->getData();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tan_ApplyCoupon::coupon');
    }
}
