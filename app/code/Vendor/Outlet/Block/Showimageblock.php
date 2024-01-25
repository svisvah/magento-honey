<?php
// app/code/Turiknox/SampleImageUploader/Block/Adminhtml/Image/Showimages.php

namespace Vendor\Outlet\Block;

use Magento\Backend\Block\Template;
use Vendor\Outlet\Model\Image as ImageModel;
use Magento\Backend\Block\Template\Context;

class Showimageblock extends Template
{
    protected $imageModel;

    public function __construct(
        Context $context,
        ImageModel $imageModel,
        array $data = []
    ) {
        $this->imageModel = $imageModel;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getImages()
    {
        $imageId = 19; 
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

    public function getDescription()
    {
        $imageId = 19; // Hardcoded image ID
        $imageModel = $this->imageModel->load($imageId);
        $imageDescription = $imageModel->getDescription();
        return $imageDescription;
    }
}
