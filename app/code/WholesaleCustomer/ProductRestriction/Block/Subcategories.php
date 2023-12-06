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

    public function getSubcategories($categoryId)
    {
        $category = $this->categoryFactory->create()->load($categoryId);
        return $category->getChildrenCategories();
    }
}