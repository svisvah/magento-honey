<?php
// @codingStandardsIgnoreFile
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Vendor\Outlet\Model\ResourceModel\Image;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'image_id';

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        $this->_init(
            'Vendor\Outlet\Model\Image',
            'Vendor\Outlet\Model\ResourceModel\Image'
        );
    }
}
