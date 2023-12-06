<?php

namespace WholesaleCustomer\ProductRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NotFoundException;

class RedirectObserver implements ObserverInterface
{
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ChangeGroup constructor.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param ProductRepository $productRepository
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        ProductRepository $productRepository
    ) {
        $this->context = $context;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
    }

    /**
     * Observer execute method.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        // Check if the customer is not logged in or the customer ID is not 2
        if ($this->customerSession->getCustomerGroupId() != 2) {
            // Get the product from the observer
            $product = $observer->getEvent()->getProduct();

            // Check if the product is not null
            if ($product && $product->getId()) {
                // Check the value of the custom attribute 'wholesale_visibility'
                $wholesaleVisibility = $product->getCustomAttribute('wholesale_visibility');

                // Check if the customer is not wholesale and the product's wholesale visibility is 1
                if ($wholesaleVisibility->getValue() == 1) {
                    // Throw a 404 error
                    throw new NotFoundException(__('Page not found'));
                }
            }
        }
    }
}
