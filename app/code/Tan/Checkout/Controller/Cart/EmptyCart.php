<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/09/2024
 */
declare(strict_types=1);

namespace Tan\Checkout\Controller\Cart;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;
use Psr\Log\LoggerInterface;

class EmptyCart extends Action
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * EmptyCart constructor.
     *
     * @param Context $context
     * @param Session $session
     * @param JsonFactory $jsonFactory
     * @param Data $jsonHelper
     * @param LoggerInterface $logger
     * @param Cart $cart
     */
    public function __construct(
        Context $context,
        Session $session,
        JsonFactory $jsonFactory,
        Data $jsonHelper,
        LoggerInterface $logger,
        Cart $cart
    ) {
        $this->checkoutSession = $session;
        $this->jsonFactory = $jsonFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->cart = $cart;
        parent::__construct($context);
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $response = [
            'errors' => false
        ];

        if ($this->getRequest()->isAjax()) {
            try {
                $this->cart->truncate()->save();
                $response['message'] = __('Empty Cart.');
            } catch (\Exception $e) {
                $response = [
                    'errors' => true,
                    'message' => __('Something went wrong. Please reload the page.')
                ];
                $this->logger->critical($e);
            }
        } else {
            $response = [
                'errors' => true,
                'message' => __('Ajax error. Please reload the page.')
            ];
        }
        /** @var Raw $resultRaw */
        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData($response);
    }
}
