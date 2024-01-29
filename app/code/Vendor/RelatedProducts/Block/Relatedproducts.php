<?php

namespace Vendor\RelatedProducts\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\View\Element\Template;


class Relatedproducts extends Template
{
    protected $productRepository;
    protected $registry;
    protected $categoryRepository;
    protected $scopeConfig;
    protected $customerSession;
    protected $imageHelper;
    protected $productFactory;
    protected $wishlist;
    protected $_reviewFactory;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        Registry $registry,
        CategoryRepository $categoryRepository,
        ScopeConfigInterface $scopeConfig,
        Image $imageHelper,
        ProductFactory $productFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Wishlist\Model\Wishlist $wishlist,
        \Magento\Review\Model\RatingFactory $reviewFactory,

        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->registry = $registry;
        $this->categoryRepository = $categoryRepository;
        $this->scopeConfig = $scopeConfig;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->_eavConfig = $eavConfig;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->customerSession = $customerSession;
        $this->_ratingFactory = $ratingFactory;
        $this->wishlist = $wishlist;
        $this->_reviewFactory = $reviewFactory;
    }

    public function getCurrentProductId()
    {
        $currentProduct = $this->registry->registry('current_product');

        if ($currentProduct) {
            return $currentProduct->getId();
        }

        return null;
    }

    public function getProductCategories()
    {
        $productId = $this->getCurrentProductId();

        $category = $this->registry->registry('current_category');
        if ($category == null) {
            if ($productId) {
                $product = $this->productRepository->getById($productId);
                $categoryIds = $product->getCategoryIds();

                $categories = [];
                foreach ($categoryIds as $categoryId) {
                    $category = $this->categoryRepository->get($categoryId);
                    $categories[] = $category->getName();
                }

                return $categories;
            }

            return [];
        } else {
            $categories[] = $category->getName();
            return $categories;
        }
    }

    public function getProductCategoriesId()
    {

        $productId = $this->getCurrentProductId();
        $category = $this->registry->registry('current_category');
        if ($category == null) {
            if ($productId) {
                $product = $this->productRepository->getById($productId);
                $categoryIds = $product->getCategoryIds();


                return $categoryIds;
            }

            return [];
        } else {
            $categoryIds[] = $category->getId();
            return $categoryIds;
        }
    }



    public function getExcludedCategories()
    {
        $excludeCategories = $this->scopeConfig->getValue(
            'related_section/related_group/exclude_category',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $excludeCategoryIds = explode(',', $excludeCategories);
        $categoryNames = [];

        foreach ($excludeCategoryIds as $categoryId) {
            try {
                $category = $this->categoryRepository->get($categoryId);
                $categoryNames[] = $category->getName();
            } catch (\Exception $e) {
                // Handle exception if category is not found
                $this->_logger->error("Category with ID $categoryId not found.");
            }
        }

        return $categoryNames;
    }


    public function getExcludedCategoriesId()
    {
        $excludeCategories = $this->scopeConfig->getValue(
            'related_section/related_group/exclude_category',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $excludeCategoryIds = explode(',', $excludeCategories);


        return $excludeCategoryIds;
    }

    public function getRelatedCategories()
    {
        $ProductCategories = $this->getProductCategories();
        $ExcludedCategories = $this->getExcludedCategories();

        $filtered_array = array_diff($ProductCategories, $ExcludedCategories);

        return $filtered_array;
    }

    public function getRelatedCategoriesId()
    {
        $ProductCategories = $this->getProductCategoriesId();
        $ExcludedCategories = $this->getExcludedCategoriesId();

        $filtered_array = array_diff($ProductCategories, $ExcludedCategories);

        return $filtered_array;
    }

    public function getProductCollectionByCategories()
    {
        $ids[] = $this->getRelatedCategoriesId();
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);
        return $collection;
    }

    public function getEnableProductRelate()
    {
        $enableProductRelate = $this->scopeConfig->getValue(
            'related_section/related_group/enable_related_product',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $enableProductRelate;
    }

    public function getProductLimit()
    {
        $productLimit = $this->scopeConfig->getValue(
            'related_section/related_group/product_limit',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if($productLimit>10)
        {
            return 10;
        }
        return $productLimit;
    }
    public function getRelatedProductsIds()
    {
        $productCollection = $this->getProductCollectionByCategories();
        $productIds = [];
        foreach ($productCollection as $productColl) {
            $productIds[] = $productColl->getId();
        }
        $currentProductId[] = $this->getCurrentProductId();

        $product_filter_ids = array_diff($productIds, $currentProductId);

        return $product_filter_ids;
    }


    public function getCustomerGroup()
    {
        $customerGroupId = $this->customerSession->getCustomerGroupId();
        return $customerGroupId;
    }

    public function getFilteredProducts()
    {
        $productCollection = $this->getProductCollectionByCategories();
        $attributeCode = "honey_product_type";
        $attribute = $this->_eavConfig->getAttribute('catalog_product', $attributeCode);
        $options = $attribute->getSource()->getAllOptions();
        $wholesale = $retail = $all = 0;

        foreach ($options as $option) {
            if ($option['value'] > 0) {
                if ($option['label'] == "Wholesale") {
                    $wholesale = $option['value'];
                } elseif ($option['label'] == "Retail") {
                    $retail = $option['value'];
                } else {
                    $all = $option['value'];
                }
            }
        }


        $customerGroupId = $this->customerSession->getCustomerGroupId();
        $customerGroupId = ($customerGroupId) ? $customerGroupId : 0;
        if ($customerGroupId == 2) {

            $productCollection->addAttributeToFilter('honey_product_type', ['neq' => $retail]);
        } else {
            $productCollection->addAttributeToFilter('honey_product_type', ['neq' => $wholesale]);
        }
        return $productCollection;
    }
    public function getFilteredProductIds()
    {
        $filteredProductCollection = $this->getFilteredProducts();
        $currentProductId = $this->getCurrentProductId();

        $productIds = [];
        foreach ($filteredProductCollection as $filterProducts) {
            if ($filterProducts->getId() != $currentProductId) {
                $productIds[] = $filterProducts->getId();
            }
        }

        return $productIds;
    }

    public function getProductImageUrl($id)
    {
        try {
            $product = $this->productFactory->create()->load($id);
        } catch (\Exception $e) {
            return 'Data not found';
        }
        $url = $this->imageHelper->init($product, 'product_small_image')->getUrl();
        return $url;
    }

    public function getItems()
    {
        $filteredProductIds = $this->getFilteredProductIds();

        $collection = $this->_productCollectionFactory->create();
        $collection->addIdFilter($filteredProductIds);

        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->load();
        return $collection;
    }
    public function getOldPrice($productId)
    {
        try {
            $product = $this->productFactory->create()->load($productId);
            return $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSpecialPrice($productId)
    {
        try {
            $product = $this->productFactory->create()->load($productId);
            return $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }
    public function getDiscountPercentage($productId)
    {
        try {
            $product = $this->productFactory->create()->load($productId);
            $oldPrice = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
            $specialPrice = $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();

            if ($oldPrice > 0 && $specialPrice < $oldPrice) {
                $discountPercentage = round((($oldPrice - $specialPrice) / $oldPrice) * 100);
                return $discountPercentage;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getRatingSummary($productId)
    {
        $product = $this->productFactory->create()->load($productId);
        $storeId = $this->_storeManager->getStore()->getId();
        $rating = $this->_reviewFactory->create()->getEntitySummary($product, $storeId);
        return $rating ? $rating->getRatingSummary() : 0;
    }
    public function getWishlistByProductId($productId)
    {

        $customerId = $this->customerSession->getCustomerId();

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
}
