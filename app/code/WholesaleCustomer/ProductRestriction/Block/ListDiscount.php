<?php

namespace WholesaleCustomer\ProductRestriction\Block;

use Magento\Catalog\Block\Product\ListProduct as ListProduct;

class ListDiscount extends ListProduct
{
    /**
     * Retrieve the discount percentage for the current product
     *
     * @return float
     */
    public function getDiscountPercentage()
    {
        $product = $this->getProduct();
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
        $logger->debug('Block Override Test');

        if ($product->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            // If the product is configurable, get the first child product
            $childProducts = $product->getTypeInstance()->getUsedProducts($product);
            $firstChildProduct = reset($childProducts);

            $price = $firstChildProduct->getPrice();
            $specialPrice = $firstChildProduct->getSpecialPrice();

            if ($specialPrice !== null && $specialPrice < $price) {
                $discount = (($price - $specialPrice) / $price) * 100;
                return round($discount, 2);
            }
        } else {
            // For other product types, calculate discount as before
            $price = $product->getPrice();
            $specialPrice = $product->getSpecialPrice();

            if ($specialPrice !== null && $specialPrice < $price) {
                $discount = (($price - $specialPrice) / $price) * 100;
                return round($discount, 2);
            }
        }

        return 0; // No discount
    }
}
