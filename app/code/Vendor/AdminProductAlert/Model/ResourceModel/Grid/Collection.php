<?php

namespace Vendor\AdminProductAlert\Model\ResourceModel\Grid;

use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @param EntityFactoryInterface $entityFactory,
     * @param LoggerInterface        $logger,
     * @param FetchStrategyInterface $fetchStrategy,
     * @param ManagerInterface       $eventManager,
     * @param StoreManagerInterface  $storeManager,
     * @param AdapterInterface       $connection,
     * @param AbstractDb             $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->_init(
            \Vendor\AdminProductAlert\Model\Grid::class,
            \Vendor\AdminProductAlert\Model\ResourceModel\Grid::class
        );
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    protected function _renderFiltersBefore()
    {
        $productTable = $this->getTable('catalog_product_entity');
        $customerTable = $this->getTable('customer_entity');
        $productNameTable=$this->getTable('catalog_product_entity_varchar');
        

        $this->getSelect()->joinLeft(
            ['productTable' => $productTable],
            'main_table.product_id = productTable.entity_id',
            ['sku']
        )->joinLeft(
            ['customerTable' => $customerTable],
            'main_table.customer_id = customerTable.entity_id',
            ['email']
        )->joinLeft(
            ['productNameTable'=>$productNameTable],
            'productTable.entity_id = productNameTable.entity_id',
            ['value'] 
        )->where('productNameTable.attribute_id=73 AND productNameTable.store_id=0');
        
        parent::_renderFiltersBefore();
    }
}

