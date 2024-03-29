<?php
 
namespace Vendor\Contactus\Block\Adminhtml\Contactus\Grid\Renderer\Action;

use Magento\Store\Api\StoreResolverInterface;

/**
 * Class UrlBuilder
 * @package Vendor\Contactus\Block\Adminhtml\Contactus\Grid\Renderer\Action
 */

class UrlBuilder
{
    /**
     * @var \Magento\Framework\UrlInterface
    */
    protected $frontendUrlBuilder;
    /**
     * @param \Magento\Framework\UrlInterface $frontendUrlBuilder
    */
    public function __construct(\Magento\Framework\UrlInterface $frontendUrlBuilder)
    {
        $this->frontendUrlBuilder = $frontendUrlBuilder;
    }
    /**
     * Get action url
     *
     * @param string $routePath
     * @param string $scope
     * @param string $store
     * @return string
     */
    public function getUrl($routePath, $scope, $store)
    {
        $this->frontendUrlBuilder->setScope($scope);
        $href = $this->frontendUrlBuilder->getUrl(
            $routePath,
            [
                '_current' => false,
                '_query' => [StoreResolverInterface::PARAM_NAME => $store]
            ]
        );
        return $href;
    }
}