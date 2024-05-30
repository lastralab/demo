<?php

namespace Tan\ApplyCoupon\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Tan\ApplyCoupon\Model\ApplyCouponFactory;

class Delete extends Action
{
    private $coreRegistry;
    private $gridFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ApplyCouponFactory $gridFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->gridFactory = $gridFactory;
    }

    public function execute()
    {
        $rowId = (int)$this->getRequest()->getParam('id');
        try {
            $rowData = $this->gridFactory->create();
            if ($rowId) {
                $rowData->load($rowId);
                $rowData->delete();
                $this->messageManager->addSuccessMessage(__('Coupon Code Link Record Deleted Successfully'));
                $this->_redirect('applycoupon/index/index');
                return;
            }
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tan_ApplyCoupon::coupon');
    }
}
