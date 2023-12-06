<?php

namespace WholesaleCustomer\LoginRestriction\Plugin;

use Magento\Customer\Model\EmailNotification as MagentoEmailNotification;
use Magento\Customer\Model\Data\Customer;

class EmailNotification
{
    /**
     * Plugin to modify email notification for new customer accounts.
     *
     * @param MagentoEmailNotification $subject
     * @param callable $proceed
     * @param Customer $customer
     * @param string $type
     * @param string $backUrl
     * @param int $storeId
     * @param int|null $sendemailStoreId
     * @return MagentoEmailNotification
     */
    public function aroundNewAccount(
        MagentoEmailNotification $subject,
        callable $proceed,
        Customer $customer,
        $type = 'registered',
        $backUrl = '',
        $storeId = 0,
        $sendemailStoreId = null
    ) {

        $isWholesaleCustomer = $customer->getCustomAttribute('want_to_become_wholesale_customer')
            ? $customer->getCustomAttribute('want_to_become_wholesale_customer')->getValue() == 1
            : false;
        if ($isWholesaleCustomer) {
            return $subject;
        }
        return $proceed($customer, $type, $backUrl, $storeId, $sendemailStoreId);
    }
}
