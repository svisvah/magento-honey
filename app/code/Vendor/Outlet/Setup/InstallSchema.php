<?php
/*
 * Vendor_Outlet

 * @category   Turiknox
 * @package    Vendor_Outlet
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-sample-imageuploader/blob/master/LICENSE.md
 * @version    1.0.0
 */

namespace Vendor\Outlet\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('outlets');

        if (!$installer->tableExists('outlets')) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'image_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Image ID'
                )
                ->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    255,
                    array(
                        'nullable'  => false,
                    ),
                    'Image'
                )->addColumn(
                    'description',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'Description',
                    ['nullable' => false],
                    'Post'
                )->addColumn(
                    'status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [],
                    'Status'
                )->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                    ],
                    'Creation Time'
                )->addColumn(
                    'update_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Updated Time'
                )->setComment(
                    'Row Data Table'
                );

            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
