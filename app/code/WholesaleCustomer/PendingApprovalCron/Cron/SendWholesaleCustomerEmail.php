<?php

namespace WholesaleCustomer\PendingApprovalCron\Cron;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class SendWholesaleCustomerEmail
{
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * SendWholesaleCustomerEmail constructor.
     *
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerFactory $customerFactory
     * @param DateTime $dateTime
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        CustomerFactory $customerFactory,
        DateTime $dateTime,
        TimezoneInterface $timezone
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->customerFactory = $customerFactory;
        $this->dateTime = $dateTime;
        $this->timezone = $timezone;
    }

    /**
     * Execute the cron job
     */
    public function execute()
    {
        $currentTimestamp = $this->dateTime->gmtTimestamp();

        // Calculate timestamp for 48 hours ago
        $twoDaysAgoTimestamp = $currentTimestamp - (48 * 3600);

        // Get customer collection without filters
        $customerCollection = $this->customerFactory->create()->getCollection();

        // HTML for the customer table
        $tableHtml = '<table border="1">
            <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
            </tr>';

        foreach ($customerCollection as $customer) {
            // Load customer by ID to get specific attribute values
            $customerModel = $this->customerFactory->create()->load($customer->getId());

            // Use if conditions to filter customers
            if ($customerModel->getData('want_to_become_wholesale_customer') == 1 &&
                $customerModel->getData('approve_as_wholesale_customer') == 0 &&
                strtotime($customerModel->getData('created_at')) <= $twoDaysAgoTimestamp
            ) {
                $tableHtml .= '<tr>
                    <td>' . $customer->getId() . '</td>
                    <td>' . $customer->getName() . '</td>
                    <td>' . $customer->getEmail() . '</td>
                </tr>';
            }
        }

        $tableHtml .= '</table>';

        // Fetch values from system.xml
        $storeId = 0; // Set your desired store ID here
        $recipientEmail = $this->scopeConfig->
        getValue('custom_section/custom_group/wholesale_pending_email', ScopeInterface::SCOPE_STORE);
        $emailTemplateId = $this->scopeConfig->
        getValue('custom_section/custom_group/wholesale_customer_pending_template', ScopeInterface::SCOPE_STORE);

        // Template variables and options
        $templateVars = ['customer_table' => $tableHtml];
        $templateOptions = ['area' => 'frontend', 'store' => $storeId];

        // Create email transport
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($emailTemplateId)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom(['email' => 'vishva.eod@gmail.com', 'name' => 'Owner'])
            ->addTo($recipientEmail)
            ->getTransport();

        // Send the email
        $transport->sendMessage();
    }
}
