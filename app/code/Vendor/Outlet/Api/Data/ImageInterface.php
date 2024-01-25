<?php
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */

namespace Vendor\Outlet\Api\Data;

/**
 * @api
 */
interface ImageInterface
{
    public const IMAGE_ID          = 'image_id';
    public const IMAGE             = 'image';
    public const DESCRIPTION = 'description';
    public const STATUS = 'status';
    public const IMAGE_DESCRIPTION = 'image_description';


    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get image
     *
     * @return string
     */
    public function getImage();

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus();

      /**
     * Get status
     *
     * @return string
     */
    public function getImageDescription();

    /**
     * Set ID
     *
     * @param int $id
     * @return ImageInterface
     */
    public function setId($id);

    /**
     * Set image
     *
     * @param array $image
     * @return ImageInterface
     */
    public function setImage($image);

    /**
     * Set Description
     *
     * @param string $description
     * @return ImageInterface
     */
    public function setDescription($description);

    /**
     * Set Status
     *
     * @param string $status
     * @return ImageInterface
     */
    public function setStatus($status);

     /**
     * Set Status
     *
     * @param string $status
     * @return ImageInterface
     */
    public function setImageDescription($imageDescription);
}
