<?php
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Vendor\Outlet\Controller\Adminhtml\Image;

use Vendor\Outlet\Controller\Adminhtml\Image;

class Edit extends Image
{
    /** Exceute
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $imageId = $this->getRequest()->getParam('image_id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vendor_Outlet::image')
            ->addBreadcrumb(__('Outlets'), __('Outlets'))
            ->addBreadcrumb(__('Manage Outlets'), __('Manage Outlets'));

        if ($imageId === null) {
            $resultPage->addBreadcrumb(__('New Outlet'), __('New Image'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Outlet'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Outlet'), __('Edit Outlet'));
            
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Outlet'));
        }
        return $resultPage;
    }
}
