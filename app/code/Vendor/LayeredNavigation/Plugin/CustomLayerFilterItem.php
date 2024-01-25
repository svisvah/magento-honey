<?php

namespace Vendor\LayeredNavigation\Plugin;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\Layer\Filter\Item as LayerFilterItem;

class CustomLayerFilterItem
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CustomerSession $customerSession
    ) {
        $this->customerSession = $customerSession;
    }

    /**
     * @param LayerFilterItem $subject
     * @param bool $result
     * @return bool
     */
    public function afterIsVisible(LayerFilterItem $subject, $result)
    {
        $customerGroupId = $this->customerSession->getCustomerGroupId();

        // Modify this condition based on your customer group logic
        if ($subject->getFilter()->getRequestVar() == 'honey_product_type') {
            if ($customerGroupId == 2 && $subject->getValue() == 'retail') {
                return false; // Hide 'Retail' option for customer group 2
            } elseif ($customerGroupId != 2 && $subject->getValue() == 'wholesale') {
                return false; // Hide 'Wholesale' option for other customer groups
            }
        }

        return $result;
    }
}