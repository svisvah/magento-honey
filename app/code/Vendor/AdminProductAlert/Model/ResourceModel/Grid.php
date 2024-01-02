<?php
/**
 * Eodform ResourceModel.
 *
 * @category    Vendor
 * @package     Vendor_Grid
 * @author      Vendor Software Private Limited
 */
namespace Vendor\AdminProductAlert\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Eodform mysql resource.
 */
class Grid extends AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'alert_stock_id';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Eodform constructor.
     *
     * @param Context                                    $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param string|null                                $resourcePrefix
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('product_alert_stock', 'alert_stock_id');
    }
}
