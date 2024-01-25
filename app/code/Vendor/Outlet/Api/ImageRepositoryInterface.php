<?php
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Vendor\Outlet\Api;

use Vendor\Outlet\Api\Data\ImageInterface;

/**
 * @api
 */
interface ImageRepositoryInterface
{
    /**
     * Save page.
     *
     * @param ImageInterface $image
     * @return ImageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(ImageInterface $image);

    /**
     * Retrieve Image.
     *
     * @param int $imageId
     * @return ImageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($imageId);

    /**
     * Delete image.
     *
     * @param ImageInterface $image
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(ImageInterface $image);

    /**
     * Delete image by ID.
     *
     * @param int $imageId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($imageId);
}
