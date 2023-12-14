<?php
namespace  Vendor\MagentoWishlist\Block;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;

class GetCurrentCustomerId extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * GetCurrentCustomerId constructor.
     *
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get customer ID of the currently logged-in person
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        // Check if customer session is available
        if ($this->customerSession && $this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomerId();
        }

        return null;
    }
}