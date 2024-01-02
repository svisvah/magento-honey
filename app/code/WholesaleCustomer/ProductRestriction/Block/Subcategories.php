<?php

namespace WholesaleCustomer\ProductRestriction\Block;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\View\Element\Template;

class Subcategories extends Template
{
    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;
    /**
     * @param Template\Context $context
     * @param CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
    }
    /**
     * Retrieve the discount percentage for the current product
     *
     * @return array
     */
    public function getCategories()
    {
        $collection = $this->categoryFactory->create()->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active', 1); // Filter out disabled categories

        return $collection;
    }
    /**
     * Retrieve the discount percentage for the current product
     *
     * @return array
     * @param int $categoryId
     */
    public function getSubcategories($categoryId)
    {
        $category = $this->categoryFactory->create()->load($categoryId);

        // Check if the category has children
        if ($category->hasChildren()) {
            return $category->getChildrenCategories();
        }

        return [];
    }
}
