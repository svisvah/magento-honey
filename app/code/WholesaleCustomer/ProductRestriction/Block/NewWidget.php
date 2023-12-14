<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace WholesaleCustomer\ProductRestriction\Block;

use Magento\Customer\Model\Session as CustomerSession;

/**
 * New products widget
 */
class NewWidget extends \Magento\Catalog\Block\Product\NewProduct implements \Magento\Widget\Block\BlockInterface
{
    /**
     * Display products type - all products
     */
    public const DISPLAY_TYPE_ALL_PRODUCTS = 'all_products';

    /**
     * Display products type - new products
     */
    public const DISPLAY_TYPE_NEW_PRODUCTS = 'new_products';

    /**
     * Default value whether show pager or not
     */
    private const DEFAULT_SHOW_PAGER = false;

    /**
     * Default value for products per page
     */
    private const DEFAULT_PRODUCTS_PER_PAGE = 5;

    /**
     * Name of request parameter for page number value
     *
     * @deprecated
     */
    public const PAGE_VAR_NAME = 'np'; // @deprecated

    /**
     * Instance of pager block
     *
     * @var \Magento\Catalog\Block\Product\Widget\Html\Pager
     */
    protected $_pager;
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    /**
     * NewWidget constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param CustomerSession $customerSession
     * @param array $data
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        CustomerSession $customerSession,
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        $this->customerSession = $customerSession;
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $data
        );
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    /**
     * Product collection initialize process
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|Object|\Magento\Framework\Data\Collection
     */
    protected function _getProductCollection()
    {
        switch ($this->getDisplayType()) {
            case self::DISPLAY_TYPE_NEW_PRODUCTS:
                $customerGroupId = $this->customerSession->getCustomerGroupId();
                $customerGroupId = ($customerGroupId) ? $customerGroupId : 0;
                $collection = parent::_getProductCollection();
                if ($customerGroupId != 2) {
                    $collection->addAttributeToFilter('wholesale_visibility', ['neq' => 1]);
                }
                $collection->setPageSize($this->getPageSize())
                    ->setCurPage($this->getCurrentPage());
                break;
            default:
                $collection = $this->_getRecentlyAddedProductsCollection();

                break;
        }
        return $collection;
    }

    /**
     * Prepare collection for recent product list
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|Object|\Magento\Framework\Data\Collection
     */
    protected function _getRecentlyAddedProductsCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */

        $customerGroupId = $this->customerSession->getCustomerGroupId();
        $customerGroupId = ($customerGroupId) ? $customerGroupId : 0;
        if ($customerGroupId != 2) {
            $collection->addAttributeToFilter('wholesale_visibility', ['neq' => 1]);
        }

        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getCurrentPage());

        return $collection;
    }

    /**
     * Get number of current page based on query value
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return abs((int)$this->getRequest()->getParam($this->getData('page_var_name')));
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array_merge(
            parent::getCacheKeyInfo(),
            [
                $this->getDisplayType(),
                $this->getProductsPerPage(),
                (int) $this->getRequest()->getParam($this->getData('page_var_name'), 1),
                $this->serializer->serialize($this->getRequest()->getParams())
            ]
        );
    }

    /**
     * Retrieve display type for products
     *
     * @return string
     */
    public function getDisplayType()
    {
        if (!$this->hasData('display_type')) {
            $this->setData('display_type', self::DISPLAY_TYPE_ALL_PRODUCTS);
        }
        return $this->getData('display_type');
    }

    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsCount()
    {
        if (!$this->hasData('products_count')) {
            return parent::getProductsCount();
        }
        return $this->getData('products_count');
    }

    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsPerPage()
    {
        if (!$this->hasData('products_per_page')) {
            $this->setData('products_per_page', self::DEFAULT_PRODUCTS_PER_PAGE);
        }
        return $this->getData('products_per_page');
    }

    /**
     * Return flag whether pager need to be shown or not
     *
     * @return bool
     */
    public function showPager()
    {
        if (!$this->hasData('show_pager')) {
            $this->setData('show_pager', self::DEFAULT_SHOW_PAGER);
        }
        return (bool)$this->getData('show_pager');
    }

    /**
     * Retrieve how many products should be displayed on page
     *
     * @return int
     */
    protected function getPageSize()
    {
        return $this->showPager() ? $this->getProductsPerPage() : $this->getProductsCount();
    }

    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        if ($this->showPager()) {
            if (!$this->_pager) {
                $this->_pager = $this->getLayout()->createBlock(
                    \Magento\Catalog\Block\Product\Widget\Html\Pager::class,
                    'widget.new.product.list.pager'
                );

                $this->_pager->setUseContainer(true)
                    ->setShowAmounts(true)
                    ->setShowPerPage(false)
                    ->setPageVarName($this->getData('page_var_name'))
                    ->setLimit($this->getProductsPerPage())
                    ->setTotalLimit($this->getProductsCount())
                    ->setCollection($this->getProductCollection());
            }
            if ($this->_pager instanceof \Magento\Framework\View\Element\AbstractBlock) {
                return $this->_pager->toHtml();
            }
        }
        return '';
    }

    /**
     * Return HTML block with price
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['zone'] = isset($arguments['zone'])
            ? $arguments['zone']
            : $renderZone;
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            
        ? $arguments['display_minimal_price']
            : true;

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }
        return $price;
    }
    
     /**
      * Get discount percentage for the product
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
