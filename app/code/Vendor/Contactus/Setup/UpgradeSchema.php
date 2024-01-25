<?php

namespace Vendor\Contactus\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
   
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
		$installer = $setup;
        $installer->startSetup();

		if(version_compare($context->getVersion(), '1.0.1', '<')) {
		$table = $setup->getTable('Vendor_contactus');

        $setup->getConnection()
            ->addIndex(
                $table,
                $setup->getIdxName(
                    $table,
                    ['name', 'email', 'telephone', 'comment'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name', 'email', 'telephone', 'comment'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
		}
	}
}