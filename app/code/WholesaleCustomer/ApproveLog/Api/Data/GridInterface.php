<?php
namespace WholesaleCustomer\ApproveLog\Api\Data;

interface GridInterface
{
    const LOG_ID = 'log_id';
    const ADMIN_NAME = 'admin_name';
    const ADMIN_EMAIL = 'admin_email';
    const ADMIN_ROLE = 'admin_role';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_EMAIL = 'customer_email';
    const APPROVED_TIME = 'approved_time';

    /**
     * Get Log Id.
     *
     * @return int
     */
    public function getLogId();

    /**
     * Set Log Id.
     */
    public function setLogId($logId);

    /**
     * Get Admin Name.
     *
     * @return string
     */
    public function getAdminName();

    /**
     * Set Admin Name.
     */
    public function setAdminName($adminName);

    /**
     * Get Admin Email.
     *
     * @return string
     */
    public function getAdminEmail();

    /**
     * Set Admin Email.
     */
    public function setAdminEmail($adminEmail);

    /**
     * Get Customer Name.
     *
     * @return string
     */
    /**
     * Get Admin Email.
     *
     * @return string
     */
    public function getAdminRole();

    /**
     * Set Admin Email.
     */
    public function setAdminRole($adminRole);

    /**
     * Get Customer Name.
     *
     * @return string
     */
    public function getCustomerName();

    /**
     * Set Customer Name.
     */
    public function setCustomerName($customerName);

    /**
     * Get Customer Email.
     *
     * @return string
     */
    public function getCustomerEmail();

    /**
     * Set Customer Email.
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getApprovedTime();

    /**
     * Set Approved Time.
     */
    public function setApprovedTime($approvedTime);
}
