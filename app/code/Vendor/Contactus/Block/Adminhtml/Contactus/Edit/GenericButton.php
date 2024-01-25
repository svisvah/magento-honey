<?php
 
namespace Vendor\Contactus\Block\Adminhtml\Contactus\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 * @package Vendor\Contactus\Block\Adminhtml\Contactus\Edit
 */

class GenericButton
{
    /**
     * @var Context
    */
    protected $context;

    /**
     * @var PageRepositoryInterface
    */
    protected $pageRepository;
    /**
     * @param Context $context
     * @param PageRepositoryInterface $pageRepository
    */
    public function __construct(
        Context $context,
        PageRepositoryInterface $pageRepository
    ) {
        $this->context = $context;
        $this->pageRepository = $pageRepository;
    }
    public function getPageId()
    {
        try {
            return $this->pageRepository->getById(
                $this->context->getRequest()->getParam('id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
        return null;
    }
    /**
     * Generate url by route and parameters
     *
     * @param   array $params
     * @param   string $route
     * @return  string
    */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}