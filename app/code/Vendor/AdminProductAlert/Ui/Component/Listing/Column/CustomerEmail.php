<?php

namespace Vendor\AdminProductAlert\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class CustomerEmail extends Column
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
                // Assuming that the customer email is stored in the 'customer_email' field
                $customerEmail = $item['customer_email'];
                $item[$this->getData('name')] = $customerEmail;
            }
        }

        return $dataSource;
    }
}