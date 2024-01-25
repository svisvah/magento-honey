<?php

namespace Vendor\Contactus\Controller\Adminhtml\Contactus;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Vendor\Contactus\Controller\Adminhtml\Contactus
 */

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vendor_Contactus::base');
        $resultPage->addBreadcrumb(__('Contactus'), __('Contactus'));
        $resultPage->addBreadcrumb(__('Manage Contactus'), __('Manage Contactus'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Contactus'));
        return $resultPage;
    }
}