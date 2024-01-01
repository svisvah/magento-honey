<?php
namespace Vendor\Stock\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Vendor\Stock\Model\Grid as ProductAlertStockModel;
use Magento\Customer\Model\CustomerFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;

class Notify extends Action implements HttpPostActionInterface, HttpGetActionInterface
{
    protected $resultPageFactory;
    protected $customerFactory;
    protected $productFactory;
    protected $transportBuilder;
    protected $request;
    protected $messageManager;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CustomerFactory $customerFactory,
        ProductFactory $productFactory,
        TransportBuilder $transportBuilder,
        RequestInterface $request,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerFactory = $customerFactory;
        $this->productFactory = $productFactory;
        $this->transportBuilder = $transportBuilder;
        $this->request = $request;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        // Get the alert_stock_id parameter from the request
        $alertStockId = $this->getRequest()->getParam('alert_stock_id');

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

            // Get product stock status using productId
            $product = $this->productFactory->create()->load($productId);
            $productStockStatus = $product->isAvailable() ? 'In Stock' : 'Out of Stock';

            // Check conditions
            if ($sendCount == 0 && $status == 0 && $productStockStatus == 'In Stock') {
                // Trigger email
                $emailSent = $this->sendProductAlertEmail($customerEmail, $product);

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

        // Redirect to the grid page
        $this->_redirect('*/*/index');

        return;
    }

    protected function sendProductAlertEmail($customerEmail, $product)
    {
        try {
            $templateVars = [
                'product' => $product->getName(),
                'product_url' => $product->getProductUrl()
                // Add other product details as needed
            ];

            $storeId = $this->request->getParam('store_id', 0);

            $transport = $this->transportBuilder
                ->setTemplateIdentifier(2) // Replace with your template ID
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
