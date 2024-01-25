<?php

namespace Vendor\Contactus\Model;

/**
 * Class Contactus
 * @package Vendor\Contactus\Model
 */

class Contactus extends \Magento\Framework\Model\AbstractModel
{       
    protected function _construct()
    {
        $this->_init('Vendor\Contactus\Model\ResourceModel\Contactus');
    }
}