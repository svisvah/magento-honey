<?php

/**
 * Grid Grid Collection.
 *
 * @category  Vendor
 * @package   Vendor_Grid
 * @author    Vendor
 * @copyright Copyright (c) 2010-2017 Vendor Software Private Limited (https://Vendor.com)
 * @license   https://store.Vendor.com/license.html
 */
namespace Vendor\Stock\Model\ResourceModel\Grid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'alert_stock_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'Vendor\Stock\Model\Grid',
            'Vendor\Stock\Model\ResourceModel\Grid'
        );
    }
}
