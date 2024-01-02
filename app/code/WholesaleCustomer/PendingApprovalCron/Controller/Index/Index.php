<?php

namespace WholesaleCustomer\PendingApprovalCron\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Index extends Action
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
     * Index constructor.
     *
     * @param Context $context
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerFactory $customerFactory
     * @param DateTime $dateTime
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        CustomerFactory $customerFactory,
        DateTime $dateTime,
        TimezoneInterface $timezone
    ) {
        parent::__construct($context);
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->customerFactory = $customerFactory;
        $this->dateTime = $dateTime;
        $this->timezone = $timezone;
    }

    /**
     * Execute action
     */
    public function execute()
    {
        $currentTimestamp = $this->dateTime->gmtTimestamp();

        // Calculate timestamp for 48 hours ago
        $twoDaysAgoTimestamp = $currentTimestamp - (48 * 3600);

        // Get customer collection without filters
        $customerCollection = $this->customerFactory->create()->getCollection();

        // Adjusted formatting for the table HTML
        $tableHtml = '<table border="1">'
            . '<tr>'
            . '<th>Customer ID</th>'
            . '<th>Customer Name</th>'
            . '<th>Customer Email</th>'
            . '</tr>';

        foreach ($customerCollection as $customer) {
            // Load customer by ID to get specific attribute values
            $customerModel = $this->customerFactory->create()->load($customer->getId());

            // Use if conditions to filter customers
            if ($customerModel->getData('want_to_become_wholesale_customer') == 1 &&
                $customerModel->getData('approve_as_wholesale_customer') == 0 &&
                strtotime($customerModel->getData('created_at')) <= $twoDaysAgoTimestamp
            ) {
                $tableHtml .= '<tr>'
                    . '<td>' . $customer->getId() . '</td>'
                    . '<td>' . $customer->getName() . '</td>'
                    . '<td>' . $customer->getEmail() . '</td>'
                    . '</tr>';
            }
        }

        $tableHtml .= '</table>';

        // Fetch values from system.xml
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $recipientEmail = $this->scopeConfig->
        getValue('custom_section/custom_group/wholesale_pending_email', ScopeInterface::SCOPE_STORE);
        $emailTemplateId = $this->scopeConfig->
        getValue('custom_section/custom_group/wholesale_customer_pending_template', ScopeInterface::SCOPE_STORE);

        // Suppress the warning for using 'echo'
        // phpcs:ignore
        echo $tableHtml;

        $templateVars = ['customer_table' => $tableHtml];

        $templateOptions = ['area' => 'frontend', 'store' => $storeId];

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($emailTemplateId)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom(['email' => 'vishva.eod@gmail.com', 'name' => 'Owner'])
            ->addTo($recipientEmail)
            ->getTransport();

        $transport->sendMessage();
    }
}
