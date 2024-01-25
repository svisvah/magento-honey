<?php

namespace Vendor\Contactus\Model\ResourceModel\Contactus;

use \Vendor\Contactus\Model\ResourceModel\AbstractCollection;

/**
 * Class Collection
 * @package Vendor\Contactus\Model\ResourceModel\Contactus
 */

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_previewFlag;
    protected function _construct()
    {
        $this->_init(
            'Vendor\Contactus\Model\Contactus',
            'Vendor\Contactus\Model\ResourceModel\Contactus'
        );
        $this->_map['fields']['id'] = 'main_table.id';
    }
}