<?php

namespace Tan\ApplyCoupon\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Tan\ApplyCoupon\Model\ResourceModel\ApplyCoupon\CollectionFactory;
use Tan\ApplyCoupon\Model\ApplyCouponFactory;

class MassDelete extends Action
{
    protected $filter;
    protected $applyCouponFactory;
    protected $collectionFactory;

    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        Context $context,
        ApplyCouponFactory $applyCouponFactory
    ) {
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->applyCouponFactory = $applyCouponFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $logCollection = $this->filter->getCollection($this->collectionFactory->create());
            foreach ($logCollection->getData() as $item) {
                $rowData = $this->applyCouponFactory->create();
                $rowData->load($item['entity_id']);
                $rowData->delete();
            }
            $this->messageManager->addSuccessMessage(__('Coupon Code Link Record Deleted Successfully'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('applycoupon/index/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tan_ApplyCoupon::coupon');
    }
}
