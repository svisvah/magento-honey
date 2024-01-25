<?php
// app/code/Turiknox/SampleImageUploader/Block/Adminhtml/Image/Showimages.php

namespace Vendor\Outlet\Block\Adminhtml\Image;

use Magento\Backend\Block\Template;
use Vendor\Outlet\Model\Image as ImageModel;
use Magento\Backend\Block\Template\Context;

class Showimageblock extends Template
{
    /**
     * @var ImageModel $imageModel
     */
    protected $imageModel;

    /**
     * @param Context $context
     * @param ImageModel $imageModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        ImageModel $imageModel,
        array $data = []
    ) {
        $this->imageModel = $imageModel;
        parent::__construct($context, $data);
    }

    /**
     * Get Images
     *
     * @param array $outletId
     * @return array
     */
    public function getImages($outletId)
    {
        $imageId = $outletId;
        $imageModel = $this->imageModel->load($imageId);
        $imagelinks = [];

        $images = json_decode($imageModel->getImage(), true);
        $i = 0;
        $imagecount = count($images);

        foreach ($images as $ima) {
            if ($i < $imagecount) {
                $imagelinks[$i] = "http://new.magento.com/media/sampleimageuploader/images/image" . $ima;
                $i++;
            }
        }

        return $imagelinks;
    }

    /**
     * Get Description
     *
     * @param array $outletId
     * @return array
     */
    public function getDescription($outletId)
    {
        $imageId = $outletId; // Hardcoded image ID
        $imageModel = $this->imageModel->load($imageId);
        $imageDescription = $imageModel->getDescription();
        return $imageDescription;
    }
}
