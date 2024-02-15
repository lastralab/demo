<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/13/2024
 */

declare(strict_types=1);

namespace Tan\Review\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Review\Helper\Data;
use Magento\Review\Model\RatingFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class Form extends \Magento\Review\Block\Form
{
    private Context $context;
    private Data $reviewData;
    private RatingFactory $ratingFactory;
    private Session $session;
    private CollectionFactory $collectionFactory;

    /**
     * @param Context $context
     * @param EncoderInterface $urlEncoder
     * @param Data $reviewData
     * @param ProductRepositoryInterface $productRepository
     * @param RatingFactory $ratingFactory
     * @param ManagerInterface $messageManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Url $customerUrl
     * @param Session $session
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        EncoderInterface $urlEncoder,
        Data $reviewData,
        ProductRepositoryInterface  $productRepository,
        RatingFactory $ratingFactory,
        ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context  $httpContext,
        Url $customerUrl,
        Session $session,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $reviewData,
            $productRepository,
            $ratingFactory,
            $messageManager,
            $httpContext,
            $customerUrl,
            $data
        );
        $this->session = $session;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param string $productSku
     * @return bool
     */
    public function getAllowWriteReview(string $productSku): bool
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('customer_id', $this->session->getCustomerId());

        foreach ($collection as $order) {
            /** @var Order $order */
            if ($order->getStatus() == Order::STATE_COMPLETE) {
                $items = $order->getAllItems();
                foreach ($items as $item) {
                    $sku = explode('-', $productSku);
                    $mainProduct = isset($sku[1]) ? $sku[0] . '-' . $sku[1] : $sku[0];
                    if (str_contains($item->getSku(), $mainProduct)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
