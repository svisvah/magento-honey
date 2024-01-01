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
        // Filter the product_alert_stock collection
        $productAlertStockCollection = $this->_objectManager->create(ProductAlertStockModel::class)
            ->getCollection()
            ->addFieldToFilter('send_count', 0)
            ->addFieldToFilter('status', 0);

        // Further filtering based on product stock status
        $productAlertStockCollection->addFieldToFilter(
            'main_table.product_id',
            ['in' => $this->productFactory->create()->getCollection()->addFieldToFilter('is_available', 1)->getAllIds()]
        );

        // Iterate through filtered collection
        foreach ($productAlertStockCollection as $productAlertStockModel) {
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
                $this->sendProductAlertEmail($customerEmail, $product);

                // Update send_count, status, and send_date
                $productAlertStockModel->setSendCount($sendCount + 1);
                $productAlertStockModel->setStatus(1);
                $productAlertStockModel->setSendDate(date('Y-m-d H:i:s'));
                $productAlertStockModel->save();
            }
        }

        // Check if at least one customer met the condition
        if ($productAlertStockCollection->getSize() > 0) {
            // Add success message
            $this->messageManager->addSuccessMessage(__('Email sent successfully.'));
        } else {
            // Add error message
            $this->messageManager->addErrorMessage(__('No customers met the condition for triggering email.'));
        }

        // Redirect to the grid page
        $this->_redirect('*/*/index');

        return;
    }

    protected function sendProductAlertEmail($customerEmail, $product)
    {
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
    }
}
