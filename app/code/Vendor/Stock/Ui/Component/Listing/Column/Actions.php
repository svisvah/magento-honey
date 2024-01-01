<?php
namespace Vendor\Stock\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    const URL_PATH_NOTIFY = 'stock/grid/notify';

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['alert_stock_id'])) {
                    $item[$this->getData('name')]['view'] = [
                        'href' => $this->context->getUrl(self::URL_PATH_NOTIFY, [
                            'alert_stock_id' => $item['alert_stock_id'],
                        ]),
                        'label' => __('Notify'),
                        'hidden' => false,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
