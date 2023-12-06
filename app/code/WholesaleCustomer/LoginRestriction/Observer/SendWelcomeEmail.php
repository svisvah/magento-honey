<?php

namespace WholesaleCustomer\LoginRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SendWelcomeEmail implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Welcome Email constructor.
     *
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param MessageManager $messageManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CustomerRepositoryInterface $customerRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->messageManager = $messageManager;
        $this->customerRepository = $customerRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * CustomerRegister Observer.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        // Ensure we have a fully loaded customer object
        $customerId = $observer->getEvent()->getCustomer()->getId();
        $customer = $this->customerRepository->getById($customerId);

        // Check if the customer is a wholesale customer, approved, and notify_customer is true
        if ($customer->getGroupId() == 2 && $customer->getCustomAttribute('notify_customer')->getValue() == 1) {
            // Send the welcome email for wholesale customers
            $onRegisterEmailValue = $this->scopeConfig->getValue('custom_section/custom_group/on_register_email');

            // Split the comma-separated string into an array of email addresses
            $emailAddresses = explode(',', $onRegisterEmailValue);
            // Trim spaces from each email address
            $emailAddresses = array_map('trim', $emailAddresses);

            $this->sendWelcomeEmail($customer, $emailAddresses);
        }
    }

    /**
     * Sends a custom welcome email to the customer.
     *
     * @param Customer $customer The customer object.
     * @param string|array $emailAddresses The email addresses for CC.
     *
     * @return void
     */
    protected function sendWelcomeEmail(Customer $customer, $emailAddresses)
    {
        try {
            // Your existing code
            $store = $this->storeManager->getStore();
            $storeId = $store->getId();

            $templateId = $this->scopeConfig->getValue('custom_section/custom_group/register_email_template');
                //$templateId = 1
            ; // Replace with the actual template ID

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
                ->addCc($emailAddresses)
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccessMessage(__('Customer Notification email sent successfully.'));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('An error occurred while sending the welcome email.'));
        }
    }
}
