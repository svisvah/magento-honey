<?php

namespace Vendor\GuestOrderLink\Observer;

use Magento\Framework\Event\ObserverInterface;

class Customersave implements ObserverInterface

{
protected $collectionFactory;

protected $logger;

public function __construct(

\Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory,

\Psr\Log\LoggerInterface $logger

)

{

$this->collectionFactory = $collectionFactory;

$this->logger = $logger;

}

public function execute(\Magento\Framework\Event\Observer $observer)

{

try {

$savedCustomer = $observer->getData('customer_data_object');

$collection = $this->collectionFactory->create()

->addAttributeToSelect('entity_id')

->addAttributeToSelect('customer_email')

->addFieldToFilter('customer_email',

['eq' => $savedCustomer->getEmail()]

)->addFieldToFilter('customer_is_guest',

['eq' => 1]

);

if ($collection->count()) {

foreach ($collection as $order){

$order->setCustomerId($savedCustomer->getId());

$order->setCustomerGroupId($savedCustomer->getGroupId());

$order->setCustomerIsGuest(1);

$order->save();

}

}

return $this;

} catch (\Exception $e) {

$this->logger->critical($e->getMessage());

}
}

}