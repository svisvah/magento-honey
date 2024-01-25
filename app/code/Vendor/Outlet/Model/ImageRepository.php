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

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\Outlet\Api\ImageRepositoryInterface;
use Vendor\Outlet\Api\Data\ImageInterface;
use Vendor\Outlet\Api\Data\ImageInterfaceFactory;
use Vendor\Outlet\Model\ResourceModel\Image as ResourceImage;
use Vendor\Outlet\Model\ResourceModel\Image\CollectionFactory as ImageCollectionFactory;

class ImageRepository implements ImageRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];
    /**
     * @var ResourceImage
     */
    protected $resource;

    /**
     * @var ImageCollectionFactory
     */
    protected $imageCollectionFactory;

    /**
     * @var ImageInterfaceFactory
     */
    protected $imageInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @param ResourceImage $resource
     * @param ImageCollectionFactory $imageCollectionFactory
     * @param ImageInterfaceFactory $imageInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceImage $resource,
        ImageCollectionFactory $imageCollectionFactory,
        ImageInterfaceFactory $imageInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->imageCollectionFactory = $imageCollectionFactory;
        $this->imageInterfaceFactory = $imageInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /** Save Image
     *
     * @param ImageInterface $image
     * @return ImageInterface
     * @throws CouldNotSaveException
     */
    public function save(ImageInterface $image)
    {
        try {
            /** @var ImageInterface|\Magento\Framework\Model\AbstractModel $image */
            $this->resource->save($image);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the image: %1',
                $exception->getMessage()
            ));
        }
        return $image;
    }

    /**
     * Get image record
     *
     * @param $imageId
     * @return int
     * @throws NoSuchEntityException
     */
    public function getById($imageId)
    {
        if (!isset($this->instances[$imageId])) {
            $image = $this->imageInterfaceFactory->create();
            $this->resource->load($image, $imageId);
            if (!$image->getId()) {
                throw new NoSuchEntityException(__('Requested image doesn\'t exist'));
            }
            $this->instances[$imageId] = $image;
        }
        return $this->instances[$imageId];
    }

    /** Delete
     *
     * @param ImageInterface $image
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(ImageInterface $image)
    {
        /** @var \Vendor\Outlet\Api\Data\ImageInterface|\Magento\Framework\Model\AbstractModel $image */
        $id = $image->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($image);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove image %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /** Delete by Id
     *
     * @param $imageId
     * @return bool
     */
    public function deleteById($imageId)
    {
        $image = $this->getById($imageId);
        return $this->delete($image);
    }
}
