<?
namespace WholesaleCustomer\LoginRestriction\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Updatecustomattributes extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $customerId = $this->getRequest()->getParam('customer_id');
        $approveAsWholesale = $this->getRequest()->getParam('approve_as_wholesale_customer');
        $notifyCustomer = $this->getRequest()->getParam('notify_customer');

        // Update customer attributes in your preferred way
        $customer = $this->_objectManager->create(\Magento\Customer\Model\Customer::class)->load($customerId);
        $customer->setCustomAttribute('approve_as_wholesale_customer', $approveAsWholesale);
        $customer->setCustomAttribute('notify_customer', $notifyCustomer);
        $customer->save();

        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
