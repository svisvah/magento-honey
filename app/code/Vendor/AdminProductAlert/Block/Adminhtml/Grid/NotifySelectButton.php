<?php

namespace Vendor\AdminProductAlert\Block\Adminhtml\Grid;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

class NotifySelectButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Notify Select'),
            'class' => 'primary',
            'on_click' => 'location.href="' . $this->getNotifyAllUrl() . '"',
            'sort_order' => 20,
        ];
    }

    /**
     * Get the URL for the Notify All action
     *
     * @return string
     */
    public function getNotifyAllUrl()
    {
        return $this->getUrl('stock/grid/notifyselect');
        // Replace 'vendor_stock/grid/notifyall' with the actual URL path for your Notify All action
    }

    /**
     * Get the URL helper
     *
     * @return UrlInterface
     */
    protected function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
