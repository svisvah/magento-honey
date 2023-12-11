<?php

namespace WholesaleCustomer\LoginRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AdminhtmlCustomerSaveAfterObserver implements ObserverInterface
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var AuthSession
     */
    private $authSession;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        DateTime $dateTime,
        AuthSession $authSession,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->dateTime = $dateTime;
        $this->authSession = $authSession;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $observer->getCustomer();
        $customerId = $customer->getId();
        $enablelog = $this->scopeConfig->getValue('custom_section/approve_log_group/enable_approve_log');
        if ($customer->getOrigData('approve_as_wholesale_customer') != $customer->getData('approve_as_wholesale_customer')) {
            // Get the currently logged-in admin's user ID
            $adminUserId = $this->authSession->getUser()->getId();
            if ($enablelog == 1 || $enablelog == 'Yes') {

                if ($adminUserId) {
                    // Get admin data
                    $adminName = $this->authSession->getUser()->getUsername(); // Replace with the actual method to get admin name
                    $adminEmail = $this->authSession->getUser()->getEmail(); // Replace with the actual method to get admin email
                    $adminRole = $this->authSession->getUser()->getRole();
                    $adminRoleName = $adminRole->getRoleName();

                    // Get customer data
                    $customerName = $customer->getName();
                    $customerEmail = $customer->getEmail();

                    // Get approval time
                    $approvalTime = $this->dateTime->gmtDate();

                    // Log values for verification
                    $this->logger->info("Admin Name: $adminName");
                    $this->logger->info("Admin Email: $adminEmail");
                    $this->logger->info("Admin Role:   $adminRoleName");
                    $this->logger->info("Customer Name: $customerName");
                    $this->logger->info("Customer Email: $customerEmail");
                    $this->logger->info("Approval Time: $approvalTime");

                    // Save data to custom table using ObjectManager
                    $gridModel = $objectManager->create(\WholesaleCustomer\ApproveLog\Model\Grid::class);
                    $gridModel->setAdminName($adminName)
                        ->setAdminEmail($adminEmail)
                        ->setAdminRole($adminRoleName)
                        ->setCustomerName($customerName)
                        ->setCustomerEmail($customerEmail)
                        ->setApprovedTime($approvalTime)
                        ->save();
                }
            }
        }
    }
}
