<?php

namespace Vendor\AdminProductAlert\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class ProductName extends Column
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
                // Assuming that the product name is stored in the 'product_name' field
                $productName = $item['product_name'];
                $item[$this->getData('name')] = $productName;
            }
        }

        return $dataSource;
    }
}
