<?php

namespace Vendor\Contactus\Controller\Adminhtml\Contactus;

use Magento\Backend\App\Action;

/**
 * Class Edit
 * @package Vendor\Contactus\Controller\Adminhtml\Contactus
 */

class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Vendor_Contactus::save';
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Vendor\Contactus\Model\Contactus
     */
    protected $model;
    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Vendor\Contactus\Model\Contactus $model
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Vendor\Contactus\Model\Contactus $model,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->model = $model;
        parent::__construct($context);
    }
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vendor_Contactus::base')
            ->addBreadcrumb(__('Contactus'), __('Contactus'))
            ->addBreadcrumb(__('Manage Contactus'), __('Manage Contactus'));
        return $resultPage;
    }
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');

        if ($id) {
            $this->model->load($id);
            if (!$this->model->getId()) {
                $this->messageManager
                ->addError(__('This record no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $resultPage = $this->_initAction();

        $resultPage->addBreadcrumb(
            $id ? __('Edit Contactus') : __('New Contactus'),
            $id ? __('Edit Contactus') : __('New Contactus')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Contactus'));
        $resultPage->getConfig()->getTitle()
            ->prepend($this->model->getId() ? __('Edit Contactus') : __('New Contactus'));
        return $resultPage;
    }
}