<?php

namespace WholesaleCustomer\ApproveLog\Model;

use WholesaleCustomer\ApproveLog\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    const LOG_ID = 'log_id';
    const ADMIN_NAME = 'admin_name';
    const ADMIN_EMAIL = 'admin_email';
    const ADMIN_ROLE = 'admin_role';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_EMAIL = 'customer_email';
    const APPROVED_TIME = 'approved_time';

    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'wholesale_customer_log';

    /**
     * @var string
     */
    protected $_cacheTag = 'wholesale_customer_log';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'wholesale_customer_log';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('WholesaleCustomer\ApproveLog\Model\ResourceModel\Grid');
    }

    /**
     * Get Log Id.
     *
     * @return int
     */
    public function getLogId()
    {
        return $this->getData(self::LOG_ID);
    }

    /**
     * Set Log Id.
     */
    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * Get Admin Name.
     *
     * @return string
     */
    public function getAdminName()
    {
        return $this->getData(self::ADMIN_NAME);
    }

    /**
     * Set Admin Name.
     */
    public function setAdminName($adminName)
    {
        return $this->setData(self::ADMIN_NAME, $adminName);
    }

    /**
     * Get Admin Email.
     *
     * @return string
     */
    public function getAdminEmail()
    {
        return $this->getData(self::ADMIN_EMAIL);
    }

    /**
     * Set Admin Email.
     */
    public function setAdminEmail($adminEmail)
    {
        return $this->setData(self::ADMIN_EMAIL, $adminEmail);
    }
    /**
     * Get Admin Role.
     *
     * @return string
     */
    public function getAdminRole()
    {
        return $this->getData(self::ADMIN_ROLE);
    }

    /**
     * Set Admin Email.
     */
    public function setAdminRole($adminRole)
    {
        return $this->setData(self::ADMIN_ROLE, $adminRole);
    }


    /**
     * Get Customer Name.
     *
     * @return string
     */
    public function getCustomerName()
    {
        return $this->getData(self::CUSTOMER_NAME);
    }

    /**
     * Set Customer Name.
     */
    public function setCustomerName($customerName)
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    /**
     * Get Customer Email.
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * Set Customer Email.
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getApprovedTime()
    {
        return $this->getData(self::APPROVED_TIME);
    }

    /**
     * Set Approved Time.
     */
    public function setApprovedTime($approvedTime)
    {
        return $this->setData(self::APPROVED_TIME, $approvedTime);
    }
}
