<?php

namespace Vendor\AdminProductAlert\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Sku extends Column
{
    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                // Assuming that the SKU is stored in the 'sku' field
                $sku = $item['sku'];
                $item[$this->getData('name')] = $sku;
            }
        }

        return $dataSource;
    }
}
