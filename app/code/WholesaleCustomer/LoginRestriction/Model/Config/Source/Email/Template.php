<?php

namespace WholesaleCustomer\LoginRestriction\Model\Config\Source\Email;

use Magento\Framework\Option\ArrayInterface;
use Magento\Email\Model\TemplateFactory;

class Template implements ArrayInterface
{
    /**
     * Some property that should be documented
     *
     * @var $templateFactory
     */
    protected $templateFactory;
 
    /**
     * Template constructor.
     *
     * @param TemplateFactory $templateFactory
     */
    public function __construct(
        TemplateFactory $templateFactory
    ) {
        $this->templateFactory = $templateFactory;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toOptionArray()
    {
        $templates = [];

        $templateCollection = $this->templateFactory->create()->getCollection();
        foreach ($templateCollection as $template) {
            $templates[] = [
                'value' => $template->getId(),
                'label' => $template->getTemplateCode(),
            ];
        }

        return $templates;
    }
}
