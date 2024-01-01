<?php
namespace Vendor\Stock\Api\Data;

interface GridInterface
{
    const ALERT_ID = 'alert_stock_id';
    const CUSTOMER_ID = 'customer_id';
    const PRODUCT_ID = 'product_id';
    const WEBSITE_ID = 'website_id';
    const STORE_ID = 'store_id';
    const ADD_DATE = 'add_date';
    const SEND_DATE = 'send_date';
    const SEND_COUNT = 'send_count';
    const STATUS = 'status';

    /**
     * Get Log Id.
     *
     * @return int
     */
    public function getAlertStockId();

    /**
     * Set Log Id.
     */
    public function setAlertStockId($alertStockId);

    /**
     * Get Admin Name.
     *
     * @return string
     */
    public function getCustomerId();

    /**
     * Set Admin Name.
     */
    public function setCustomerId($customerId);

    /**
     * Get Admin Email.
     *
     * @return string
     */
    public function getProductId();

    /**
     * Set Admin Email.
     */
    public function setProductId($productId);

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
    public function getWebsiteId();

    /**
     * Set Admin Email.
     */
    public function setWebsiteId($websiteId);

    /**
     * Get Customer Name.
     *
     * @return string
     */
    public function getStoreId();

    /**
     * Set Customer Name.
     */
    public function setStoreId($storeId);

    /**
     * Get Customer Email.
     *
     * @return string
     */
    public function getAddDate();

    /**
     * Set Customer Email.
     */
    public function setAddDate($addDate);

    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getSendDate();

    /**
     * Set Approved Time.
     */
    public function setSendDate($sendDate);

    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getSendCount();

    /**
     * Set Approved Time.
     */
    public function setSendCount($sendCount);
    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set Approved Time.
     */
    public function setStatus($status);
    
}
