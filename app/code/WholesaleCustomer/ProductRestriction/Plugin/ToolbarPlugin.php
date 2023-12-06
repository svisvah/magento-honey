<?php

namespace WholesaleCustomer\ProductRestriction\Plugin;

use Magento\Catalog\Block\Product\ProductList\Toolbar as Subject;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class ToolbarPlugin
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * ChangeGroup constructor.
     *
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CustomerSession $customerSession
    ) {
        $this->customerSession = $customerSession;
    }
    /**
     * Retrieve the discount percentage for the current product
     *
     * @param \Magento\CatalogWidget\Model\Rule $subject
     * @param bool $result
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return bool
     */
    public function afterSetCollection(Subject $subject, $result, ProductCollection $collection)
    {
        $customerGroupId = $this->customerSession->getCustomerGroupId();

        // For not-logged-in customers, Magento uses the customer group with ID 0 (NOT LOGGED IN)
        $customerGroupId = ($customerGroupId) ? $customerGroupId : 0;

        // Check customer group and modify the product collection accordingly
        if ($customerGroupId != 2) {
            $collection->addAttributeToFilter('wholesale_visibility', ['neq' => 1]);
        }
        return $result;
    }
}
