<?php

namespace WholesaleCustomer\ApproveAPI\Model\Api;

use Psr\Log\LoggerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class ApproveAPI
{
    protected $logger;
    protected $customerRepository;
    protected $customerFactory;
    protected $request;
    protected $storeManager;
    protected $transportBuilder;
    protected $inlineTranslation;
    protected $scopeConfig;
    protected $encryptor;

    public function __construct(
        LoggerInterface $logger,
        CustomerRepositoryInterface $customerRepository,
        CustomerFactory $customerFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor
    ) {
        $this->logger = $logger;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    /**
     * @inheritdoc
     */


    public function getData($value)
    {
//         $customerId = 12;
//         $customerIdString = (string)$customerId;
//         $value = $this->encryptor->encrypt($customerIdString);
//         $this->logger->info("Encrypted customer id: " . $value);
// echo $value;
// exit();

        // $decrypt = $this->encryptor->decrypt($value);
        // $this->logger->info("Decrypted: " . $decrypt);
        // // echo $decrypt;
        // // exit();
        // $customerId = $decrypt;

        $response = ['success' => false];

        try {
            
            $customer = $this->customerFactory->create()->load($value);
            $customerRepo = $this->customerRepository->getById($value);
            if ($customer->getData() != null) {
                $wholesale = $customer->getData('want_to_become_wholesale_customer');
                if ($wholesale == 1) {
                    $approve = $customer->getData('approve_as_wholesale_customer');
                    $notify = $customer->getData('notify_customer');

                    if ($approve == null && $notify == null || $approve == 0 && $notify == 0) {
                        $customerRepo->setCustomAttribute('approve_as_wholesale_customer', '1');
                        $customerRepo->setCustomAttribute('notify_customer', '1');
                        $customerRepo->setGroupId(2);
                        $this->customerRepository->save($customerRepo);
                        $onRegisterEmailValue = $this->scopeConfig->getValue('custom_section/custom_group/on_register_email');

                        // Split the comma-separated string into an array of email addresses
                        $emailAddresses = explode(',', $onRegisterEmailValue);
                        // Trim spaces from each email address
                        $emailAddresses = array_map('trim', $emailAddresses);
                        $this->sendWelcomeEmail($customer, $emailAddresses);

                        $response = ['success' => true, 'message' => "Successfully saved"];
                    } else {
                        $response = ['success' => true, 'message' => "Customer already approved "];
                    }
                } else {
                    $response = ['success' => true, 'message' => "Customer is not a wholesaler "];
                }
            } else {
                $response = ['success' => true, 'message' => $customer];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }

        $returnArray = json_encode($response);

        return $returnArray;
    }
    protected function sendWelcomeEmail($customer, $emailAddresses)
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
            // $this->messageManager->addSuccessMessage(__('Customer Notification email sent successfully.'));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            // $this->messageManager->addErrorMessage(__('An error occurred while sending the welcome email.'));
        }
    }
}
