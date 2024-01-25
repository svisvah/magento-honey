<?php

namespace WholesaleCustomer\ProductRestriction\Plugin;

use Magento\Catalog\Block\Product\ProductList\Toolbar as Subject;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Block\Product\ProductList\Toolbar as Productdata;

class ToolbarPlugin
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    protected $eavConfig;

    /**
     * ChangeGroup constructor.
     *
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CustomerSession $customerSession,
        \Magento\Eav\Model\Config $eavConfig,
    ) {
        $this->customerSession = $customerSession;
        $this->eavConfig = $eavConfig;
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
        $attributeCode = "honey_product_type";
        $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
        $options = $attribute->getSource()->getAllOptions();
        $wholesale=$retail=$all=0;

        foreach ($options as $option) {
            if ($option['value'] > 0) {
                if($option['label']=="Wholesale")
                {
                    $wholesale=$option['value'];
                }
                elseif($option['label']=="Retail")
                {
                    $retail=$option['value'];
                }
                else
                {
                    $all=$option['value'];
                }

            }
        }
        $customerGroupId = $this->customerSession->getCustomerGroupId();

        // For not-logged-in customers, Magento uses the customer group with ID 0 (NOT LOGGED IN)
        $customerGroupId = ($customerGroupId) ? $customerGroupId : 0;

        // Check customer group and modify the product collection accordingly
         
        if ($customerGroupId == 2) {

            $collection->addAttributeToFilter('honey_product_type', ['neq' => $retail]);
        }
        else
        {
            $collection->addAttributeToFilter('honey_product_type', ['neq' => $wholesale]);

        }
        return $result;
    }
    public function aroundSetCollection(Productdata $subject, \Closure $proceed, $collection)
    {
        $currentOrder = $subject->getCurrentOrder();
        if ($currentOrder) {
            if ($currentOrder == "newest_product") {
                $direction = $subject->getCurrentDirection();
                $collection->getSelect()->order('created_at ' . $direction);
            }
            return $proceed($collection);
        }
    }
}
