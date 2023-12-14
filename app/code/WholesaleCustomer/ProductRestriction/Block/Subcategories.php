<?php
namespace WholesaleCustomer\ProductRestriction\Block;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\View\Element\Template;

class Subcategories extends Template
{
    protected $categoryFactory;

    public function __construct(
        Template\Context $context,
        CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
    }

    public function getCategories()
    {
      
        $collection = $this->categoryFactory->create()->getCollection()
        ->addAttributeToSelect('*');
        return $collection;
    }

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