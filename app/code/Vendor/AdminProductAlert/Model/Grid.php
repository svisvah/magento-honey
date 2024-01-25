<?php

namespace Vendor\AdminProductAlert\Model;

use Vendor\AdminProductAlert\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
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
     * CMS page cache tag.
     */
    const CACHE_TAG = 'product_alert_stock';

    /**
     * @var string
     */
    protected $_cacheTag = 'product_alert_stock';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'product_alert_stock';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Vendor\AdminProductAlert\Model\ResourceModel\Grid');
    }

    /**
     * Get Log Id.
     *
     * @return int
     */
    public function getAlertStockId()
    {
        return $this->getData(self::ALERT_ID);
    }

    /**
     * Set Log Id.
     */
    public function setAlertStockId($alertStockId)
    {
        return $this->setData(self::ALERT_ID, $alertStockId);
    }

    /**
     * Get Admin Name.
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set Admin Name.
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get Admin Email.
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set Admin Email.
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }
    /**
     * Get Admin Role.
     *
     * @return string
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * Set Admin Email.
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }


    /**
     * Get Customer Name.
     *
     * @return string
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set Customer Name.
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get Customer Email.
     *
     * @return string
     */
    public function getAddDate()
    {
        return $this->getData(self::ADD_DATE);
    }

    /**
     * Set Customer Email.
     */
    public function setAddDate($addDate)
    {
        return $this->setData(self::ADD_DATE, $addDate);
    }

    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getSendDate()
    {
        return $this->getData(self::SEND_DATE);
    }

    /**
     * Set Approved Time.
     */
    public function setSendDate($sendDate)
    {
        return $this->setData(self::SEND_DATE, $sendDate);
    }

    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getSendCount()
    {
        return $this->getData(self::SEND_COUNT);
    }

    /**
     * Set Approved Time.
     */
    public function setSendCount($sendCount)
    {
        return $this->setData(self::SEND_COUNT, $sendCount);
    }

    /**
     * Get Approved Time.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set Approved Time.
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
