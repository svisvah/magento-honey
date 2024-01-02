<?php

namespace WholesaleCustomer\LoginRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use WholesaleCustomer\ApproveLog\Model\GridFactory;

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

    /**
     * @var GridFactory
     */
    private $gridFactory;

    /**
     * Constructor.
     *
     * @param DateTime $dateTime
     * @param AuthSession $authSession
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param GridFactory $gridFactory
     */
    public function __construct(
        DateTime $dateTime,
        AuthSession $authSession,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        GridFactory $gridFactory
    ) {
        $this->dateTime = $dateTime;
        $this->authSession = $authSession;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->gridFactory = $gridFactory;
    }

    /**
     * Execute observer.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getCustomer();
        $customerId = $customer->getId();
        $enableLog = $this->scopeConfig->getValue('custom_section/approve_log_group/enable_approve_log');

        if ($customer->getOrigData('approve_as_wholesale_customer') != $customer->
        getData('approve_as_wholesale_customer')) {
            // Get the currently logged-in admin's user ID
            $adminUserId = $this->authSession->getUser()->getId();

            if ($enableLog == 1 || $enableLog == 'Yes') {
                if ($adminUserId) {
                    $adminUser = $this->authSession->getUser();

                    // Get admin data
                    $adminName = $adminUser->getUsername();
                    $adminEmail = $adminUser->getEmail();
                    $adminRole = $adminUser->getRole();
                    $adminRoleName = $adminRole->getRoleName();

                    // Get customer data
                    $customerName = $customer->getName();
                    $customerEmail = $customer->getEmail();

                    // Get approval time
                    $approvalTime = $this->dateTime->gmtDate();

                    // Log values for verification
                    $this->logger->info("Admin Name: $adminName");
                    $this->logger->info("Admin Email: $adminEmail");
                    $this->logger->info("Admin Role: $adminRoleName");
                    $this->logger->info("Customer Name: $customerName");
                    $this->logger->info("Customer Email: $customerEmail");
                    $this->logger->info("Approval Time: $approvalTime");

                    // Save data to custom table using factory
                    $gridModel = $this->gridFactory->create();
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
