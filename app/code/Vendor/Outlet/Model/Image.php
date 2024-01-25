<?php
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */

namespace Vendor\Outlet\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;
use Vendor\Outlet\Api\Data\ImageInterface;

class Image extends AbstractModel implements ImageInterface
{
    /**
     * Cache tag
     */
    public const CACHE_TAG = 'Vendor_Outlet_image';

    /**
     * @var UploaderPool
     */
    protected $uploaderPool;

    /**
     * Sliders constructor.
     * @param Context $context
     * @param Registry $registry
     * @param UploaderPool $uploaderPool
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        UploaderPool $uploaderPool,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->uploaderPool    = $uploaderPool;
    }

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Vendor\Outlet\Model\ResourceModel\Image');
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(ImageInterface::IMAGE);
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(ImageInterface::DESCRIPTION);
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(ImageInterface::STATUS);
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getImageDescription()
    {
        return $this->getData(ImageInterface::IMAGE_DESCRIPTION);
    }


    /**
     * Set Image.
     */
    public function setImage($image)
    {
        return $this->setData(ImageInterface::IMAGE, $image);
    }

    /**
     * Set Description.
     */
    public function setDescription($description)
    {
        return $this->setData(ImageInterface::DESCRIPTION, $description);
    }
    /**
     * Set Status.
     */
    public function setImageDescription($imageDescription)
    {
        return $this->setData(ImageInterface::STATUS, $imageDescription);
    }
    /**
     * Set Status.
     */
    public function setStatus($status)
    {
        return $this->setData(ImageInterface::STATUS, $status);
    }
    /**
     * Get image URL
     *
     * @return bool|string
     * @throws LocalizedException
     */
    public function getImageUrl()
    {
        $url = false;
        $image = $this->getImage();
        if ($image) {
            if (is_string($image)) {
                $uploader = $this->uploaderPool->getUploader('image');
                $url = $uploader->getBaseUrl() . $uploader->getBasePath() . $image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    /**
     * Get ImageBaseUrl
     *
     * @return string
     */
    public function getImagBaseUrl()
    {
        $url = false;
        $image = $this->getImage();
        if ($image) {
            if (is_string($image)) {
                $uploader = $this->uploaderPool->getUploader('image');
                $url = $uploader->getBaseUrl() . $uploader->getBasePath();
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }
}
