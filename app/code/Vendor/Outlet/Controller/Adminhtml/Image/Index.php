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

class Index extends Image
{
    /** Execute
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Vendor_Outlet::image');
        $resultPage->getConfig()->getTitle()->prepend(__('Outlets'));
        $resultPage->addBreadcrumb(__('Outlets'), __('Outlets'));
        return $resultPage;
    }
}
