<?php

namespace Vendor\AdminProductAlert\Controller\Adminhtml\Grid;


use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Vendor\AdminProductAlert\Model\ResourceModel\Grid\CollectionFactory;
use Magento\Framework\View\Result\PageFactory;
use Vendor\AdminProductAlert\Model\Grid as ProductAlertStockModel;
use Magento\Customer\Model\CustomerFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


class Massnotify extends Action
{
    /**
     * @var Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    protected $resultPageFactory;
    protected $customerFactory;
    protected $productFactory;
    protected $transportBuilder;
    protected $request;
    protected $messageManager;
    protected $storeManager;
    protected $scopeConfig;


    /**
     * Dependency Initilization
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        PageFactory $resultPageFactory,
        CustomerFactory $customerFactory,
        ProductFactory $productFactory,
        TransportBuilder $transportBuilder,
        RequestInterface $request,
        ManagerInterface $messageManager,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerFactory = $customerFactory;
        $this->productFactory = $productFactory;
        $this->transportBuilder = $transportBuilder;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Provides content
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $collection = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection as $model) {
            $alertStockId = $model->getData('alert_stock_id');

            // Load the product_alert_stock model
            $productAlertStockModel = $this->_objectManager->create(ProductAlertStockModel::class);
            $productAlertStockModel->load($alertStockId);

            // Check if the row exists
            if ($productAlertStockModel->getId()) {
                // Get the row values
                $productId = $productAlertStockModel->getProductId();
                $customerId = $productAlertStockModel->getCustomerId();
                $sendCount = $productAlertStockModel->getSendCount();
                $status = $productAlertStockModel->getStatus();
                // Get customer email using customerId
                $customer = $this->customerFactory->create()->load($customerId);
                $customerEmail = $customer->getEmail();
                $customername = $customer->getFirstname() . ' ' . $customer->getLastname();

                // Get product stock status using productId
                $product = $this->productFactory->create()->load($productId);
                $productid = $product->getId();
                $productStockStatus = $product->isAvailable() ? 'In Stock' : 'Out of Stock';
                $specialprice = $product->getPriceInfo()->getPrice('special_price')->getAmount()->getValue();
                $regularprice = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
                if ($specialprice < 0) {
                    $pricestatement = "Price ₹" . $regularprice;
                } else {
                    $pricestatement = "Special Price ₹" . $specialprice . " Regular Price ₹" . $regularprice;
                }
                // Check conditions
                if ($sendCount == 0 && $status == 0 && $productStockStatus == 'In Stock') {
                    // Trigger email
                    $emailSent = $this->sendProductAlertEmail($customerEmail, $product, $customername, $pricestatement, $productid);

                    if ($emailSent) {
                        // Update send_count, status, and send_date
                        try {
                            $productAlertStockModel->setSendCount($sendCount + 1);
                            $productAlertStockModel->setStatus(1);
                            $productAlertStockModel->setSendDate(date('Y-m-d H:i:s'));
                            $productAlertStockModel->save();

                            // Add success message
                            $this->messageManager->addSuccessMessage(__('Email sent successfully.'));
                        } catch (\Exception $e) {
                            // Add error message if updating values fails
                            $this->messageManager->addErrorMessage(__('Error updating values: %1', $e->getMessage()));
                        }
                    } else {
                        // Add error message if email sending fails
                        $this->messageManager->addErrorMessage(__('Error sending email.'));
                    }
                } else {
                    // Add error message
                    $this->messageManager->addErrorMessage(__('Conditions not met for triggering email.'));
                }
            } else {
                // Add error message
                $this->messageManager->addErrorMessage(__('Product Alert Stock with ID %1 not found.', $alertStockId));
            }

           
            
        }
        $this->_redirect('*/*/index');

            return;
    }
    protected function sendProductAlertEmail($customerEmail, $product, $customername, $pricestatement, $productid)
    {
        try {
            $templateId = $this->scopeConfig->getValue('custom_section/outofstock_group/outofstock_template');
            $templateVars = [
                'customer_name' => $customername,
                'product' => $product->getName(),
                'product_image' => $product->getImageUrl(),
                'product_url' => $product->getProductUrl(),
                'special_price' => $product->getSpecialPrice(),
                'regular_price' => $product->getRegularPrice(),
                'price_statement' => $pricestatement,

                'unsubscribe_link' => $this->storeManager->getStore()->getBaseUrl() . "/productalert/unsubscrible/email/product/" . $productid . "/",
                'unsubscribe_all' => $this->storeManager->getStore()->getBaseUrl() . "/productalert/unsubscrible/stockAll/"

            ];

            $storeId = $this->request->getParam('store_id', 0);

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId) // Replace with your template ID
                ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
                ->setTemplateVars($templateVars)
                ->setFrom(['email' => 'vishva.eod@gmail.com', 'name' => 'Owner'])
                ->addTo($customerEmail)
                ->getTransport();

            $transport->sendMessage();
            return true;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error sending email: %1', $e->getMessage()));
            return false;
        }
    }
}
