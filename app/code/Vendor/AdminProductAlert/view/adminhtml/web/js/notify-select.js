define([
    'jquery',
    'uiRegistry',
    'Magento_Ui/js/modal/alert',
], function ($, uiRegistry, alert) {
    'use strict';

    return function (config, element) {
        var gridName = 'stock_record_grid_list.stock_record_grid_list_data_source'; // Your grid data source name

        $(element).on('click', function () {
            var selectedItems = uiRegistry.get(gridName).selected();

            if (selectedItems.length === 0) {
                alert({
                    title: 'No Rows Selected',
                    content: 'Please select rows before clicking Notify Select.',
                });
                return;
            }

            // Print selected row data
            selectedItems.forEach(function (item) {
                console.log(item.data);
                
            });
        });
    };
});
