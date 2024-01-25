<?php

namespace Vendor\Contactus\Model\ResourceModel;

/**
 * Class Contactus
 * @package Vendor\Contactus\Model\ResourceModel
 */

class Contactus extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('Vendor_contactus', 'id');
    }
}