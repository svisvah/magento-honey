<?php

namespace OrderAttribute\DeliveryNote\Block\Adminhtml\Order\View;

use Magento\Sales\Api\Data\OrderInterface;

class DisplayCustomValue extends \Magento\Backend\Block\Template
{
    /**
     * DisplayCustomValue constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param OrderInterface $orderInterface
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        OrderInterface $orderInterface,
        array $data = []
    ) {
        $this->orderInterface = $orderInterface;
        parent::__construct($context, $data);
    }
    /**
     * Get order note
     *
     * @return string|null
     */
    public function getNote()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->orderInterface->load($orderId);

        return $order->getNote();
    }
}
