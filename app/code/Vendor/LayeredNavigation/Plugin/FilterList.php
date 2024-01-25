<?php

namespace Vendor\LayeredNavigation\Plugin;

class FilterList
{
    protected $customerSession;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerSession = $customerSession;
    }

    public function afterGetFilters(
        \Magento\Catalog\Model\Layer\FilterList $subject,
        $filters
    ) {
        $attributeCode = 'honey_product_type';

        if ($this->customerSession->getCustomerGroupId() == 2) {
            // Customer group is wholesale, remove the "Retail" option
            foreach ($filters as $filter) {
                if ($filter->getRequestVar() == $attributeCode) {
                    $options = $filter->getItems();
                    foreach ($options as $option) {
                        // Replace 'Retail' with the actual option label you want to remove
                        if ($option->getLabel() == 'Retail') {
                            unset($options[$option->getValue()]);
                        }
                    }

                    // Update the filter items
                    $filter->setItems($options);
                }
            }
        } else {
            // Customer group is not wholesale, remove the "Wholesale" option
            foreach ($filters as $filter) {
                if ($filter->getRequestVar() == $attributeCode) {
                    $options = $filter->getItems();
                    foreach ($options as $option) {
                        // Replace 'Wholesale' with the actual option label you want to remove
                        if ($option->getLabel() == 'Wholesale') {
                            unset($options[$option->getValue()]);
                        }
                    }

                    // Update the filter items
                    $filter->setItems($options);
                }
            }
        }

        return $filters;
    }
}
