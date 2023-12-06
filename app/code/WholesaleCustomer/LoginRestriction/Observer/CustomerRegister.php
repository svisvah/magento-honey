<?php

namespace WholesaleCustomer\LoginRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Customer\Model\ResourceModel\CustomerFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CustomerRegister implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var MessageManager
     */
    protected $messageManager;
    /**
     * @var CustomerFactory
     */
    protected $customerFactory;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * Register constructor.
     *
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param StoreManagerInterface $storeManager
     * @param MessageManager $messageManager
     * @param CustomerFactory $customerFactory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        MessageManager $messageManager,
        CustomerFactory $customerFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->messageManager = $messageManager;
        $this->customerFactory = $customerFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * CustomerRegister Observer.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /** @var CustomerInterface $customer */
        $customer = $observer->getEvent()->getCustomer();

        // Get the custom attribute value using getCustomAttribute
        $wholesaleAttribute = $customer->getCustomAttribute('want_to_become_wholesale_customer');

        // Check if the attribute is present and is an object
        if ($wholesaleAttribute && method_exists($wholesaleAttribute, 'getValue')) {
            $wholesaleValue = $wholesaleAttribute->getValue();
        } else {
            $wholesaleValue = null;
        }

        if ($wholesaleValue) {
            // Get customer name or email as a fallback
            $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
            if (empty(trim($customerName))) {
                $customerName = $customer->getEmail();
            }

            $this->logger->info("Registered as a wholesale customer: " . $customerName);

            // Save customer details in the database
            // $this->saveCustomerDetails($customer);

            // Send custom email
            $this->sendCustomEmail($customer);
            $onApproveEmailValue = $this->scopeConfig->getValue('custom_section/custom_group/on_approve_email');
            // Split the comma-separated string into an array of email addresses
            $emailAddresses = explode(',', $onApproveEmailValue);
            // Trim spaces from each email address
            $emailAddresses = array_map('trim', $emailAddresses);

            foreach ($emailAddresses as $toEmail) {
                $this->sendCustomAdminEmail($toEmail, $observer->getEvent()->getCustomer());
            }
            // Display a message for customer approval
            $this->messageManager->addNoticeMessage(
                __('Customer approval is required. We will send you an email within 48 hours.')
            );
        } else {
            // Get customer name or email as a fallback
            $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
            if (empty(trim($customerName))) {
                $customerName = $customer->getEmail();
            }

            $this->logger->info("Registered as a regular customer: " . $customerName);
        }
    }
    /**
     * Sends a custom email to the customer.
     *
     * @param CustomerInterface $customer
     */
    protected function sendCustomEmail(CustomerInterface $customer)
    {
        try {

            $store = $this->storeManager->getStore();
            $storeId = $store->getId();
            $templateId = $this->scopeConfig->getValue('custom_section/custom_group/customer_approve_email');
            // echo $templateid;
            // exit();

            // $templateId = 2; // Replace with the actual ID of your custom email template
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
                ->setTemplateVars([
                    'customer' => $customer,
                    'email' => $customer->getEmail(),
                    'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                    'password' => 'Password you set when creating account',

                ])
                ->setFrom(['name' => 'Owner', 'email' => 'vishva.eod@gmail.com'])
                ->addTo($customer->getEmail(), $customer->getFirstname() . ' ' . $customer->getLastname())

                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->logger->info("Custom welcome email sent successfully for " . $customer->getEmail());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
    /**
     * Sends a custom email to the admin.
     *
     * @param string $toEmail
     * @param CustomerInterface $customer
     */
    protected function sendCustomAdminEmail($toEmail, $customer)
    {
        try {
            $store = $this->storeManager->getStore();
            $storeId = $store->getId();
            $templateId = $this->scopeConfig->getValue('custom_section/custom_group/register_email_to_admin');
            //$templateId = 3; // Replace with the actual ID of your custom email template
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
                ->setTemplateVars([
                    'customer' => $customer,
                    'email' => $customer->getEmail(),
                    'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                    'password' => 'Password you set when creating account',

                ])
                ->setFrom(['name' => 'Owner', 'email' => 'vishva.eod@gmail.com'])
                ->addTo($toEmail)

                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->logger->info("Custom welcome email sent successfully for " . $customer->getEmail());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
