<?php

namespace OrderAttribute\DeliveryNote\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteFactory;
use Psr\Log\LoggerInterface;

class PlaceOrder implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * PlaceOrder constructor.
     *
     * @param LoggerInterface $logger
     * @param QuoteFactory $quoteFactory
     */
    public function __construct(
        LoggerInterface $logger,
        QuoteFactory $quoteFactory
    ) {
        $this->_logger = $logger;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $quoteId = $order->getQuoteId();
        $quote = $this->quoteFactory->create()->load($quoteId);

        // Set note from quote to order
        $order->setNote($quote->getNote());
        $order->save();
    }
}
