<?php
namespace Vendor\Outlet\Controller\Adminhtml\Image;

use Vendor\Outlet\Controller\Adminhtml\Image;
use Magento\Framework\Controller\ResultFactory;

class Showimages extends Image
{
    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $resultRaw->setContents($this->_view->getLayout()
            ->createBlock(\Vendor\Outlet\Block\Adminhtml\Image\Showimages::class)
            ->setTemplate('Vendor_Outlet::image/showimages.phtml')
            ->toHtml());
        return $resultRaw;
    }
}
