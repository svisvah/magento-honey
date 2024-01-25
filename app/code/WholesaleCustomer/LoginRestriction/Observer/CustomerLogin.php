<?php

namespace WholesaleCustomer\LoginRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Response\Http;

class CustomerLogin implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var Http
     */
    protected $response;

    /**
     * ChangeGroup constructor.
     *
     * @param LoggerInterface $logger
     * @param CustomerSession $customerSession
     * @param ManagerInterface $messageManager
     * @param Http $response
     */
    public function __construct(
        LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession,
        ManagerInterface $messageManager,
        Http $response
    ) {
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->response = $response;
    }

    /**
     * Observer execute method.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //$customer = $this->customerSession->getCustomer();

        $event = $observer->getEvent();
        $customer = $event->getCustomer();

        // Get the value of the custom attribute
        $wholesaleValue = $customer->getData('want_to_become_wholesale_customer');

        $approveValue = $customer->getData('approve_as_wholesale_customer');
        $approve = ($approveValue ? 'Yes' : 'No');
        // Store the value in a variable
        $customAttributeValue = ($wholesaleValue ? 'Yes' : 'No');

        $this->logger->info("Customer Logged In");

        if ($customAttributeValue === 'Yes') {
            if ($approve == 'No') {
                $this->messageManager
                ->addNoticeMessage(__('Admin approval is required to become a wholesale customer, we would notify with
                approval link within 48 hours.'));
                $this->customerSession->logout(); // Log out the user
            } else {
                $redirectUrl = '/customer/account/';
                $this->response->setRedirect($redirectUrl);

                $this->logger->info("Logging as wholesale customer: " . $customer->getName());
            }
        } else {
            $this->logger->info("Customer Name: " . $customer->getName());
        }
    }
}


