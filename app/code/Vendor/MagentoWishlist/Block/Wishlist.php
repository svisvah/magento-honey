<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Vendor\MagentoWishlist\Block;

use Magento\Customer\Model\Context as CustomerContext;

/**
 * Add product to wishlist
 *
 * @api
 * @since 100.1.1
 */
class Wishlist extends \Magento\Catalog\Block\Product\ProductList\Item\Block
{
    /**
     * @var \Magento\Wishlist\Model\Wishlist
     */
    protected $wishlist;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * Wishlist constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Wishlist\Model\Wishlist $wishlist
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Wishlist\Model\Wishlist $wishlist,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        $this->wishlist = $wishlist;
        $this->httpContext = $httpContext;
        parent::__construct($context, $data);
    }

    /**
     * Get customer ID of the currently logged-in person
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH, 0);
    }

    /**
     * Get wishlist items by customer ID

     * @param int $productId
     * @param int $customerId
     * @return \Magento\Wishlist\Model\ResourceModel\Item\Collection|null
     */
    public function getWishlistByProductId($productId, $customerId)
    {

        if ($customerId) {
            $wishlist = $this->wishlist->loadByCustomerId($customerId)->getItemCollection();
            $productIds = [];
            foreach ($wishlist as $item) {
                $productIds[] = $item->getProductId();
            }

            return in_array($productId, $productIds);
        }

        return false;
    }

    /**
     * Get Wishlist Helper
     *
     * @return \Magento\Wishlist\Helper\Data
     * @since 100.1.1
     */
    public function getWishlistHelper()
    {
        return $this->_wishlistHelper;
    }
}
