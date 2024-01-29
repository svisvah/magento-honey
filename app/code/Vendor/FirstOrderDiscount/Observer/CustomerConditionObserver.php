<?php

namespace Vendor\FirstOrderDiscount\Observer;

/**
 * Class CustomerConditionObserver
 */
class CustomerConditionObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Execute observer.
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
    $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
    $objectmanager->get('Psr\Log\LoggerInterface')->info('First Order Discount Observer'); //Print log in var/log/system.log
    $objectmanager->get('Psr\Log\LoggerInterface')->debug('First Order Discount Observer');


        $additional = $observer->getAdditional();
        $conditions = (array) $additional->getConditions();

        $conditions = array_merge_recursive($conditions, [
            $this->getCustomerFirstOrderCondition()
        ]);

        $additional->setConditions($conditions);
        return $this;
    }

    /**
     * Get condition for customer first order.
     * @return array
     */
    private function getCustomerFirstOrderCondition()
    {
        return [
            'label'=> __('Customer first order'),
            'value'=> \Vendor\FirstOrderDiscount\Model\Rule\Condition\Customer::class
        ];
    }
}