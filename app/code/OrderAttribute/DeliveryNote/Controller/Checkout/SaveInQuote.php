<?php

namespace OrderAttribute\DeliveryNote\Controller\Checkout;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\QuoteRepository;

class SaveInQuote extends Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonResultFactory;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * SaveInQuote constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonResultFactory
     * @param Session $checkoutSession
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        Session $checkoutSession,
        QuoteRepository $quoteRepository
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;

        parent::__construct($context);
    }

    /**
     * Execute the action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $checkVal = $this->getRequest()->getParam('checkVal');
        $quoteId = $this->checkoutSession->getQuoteId();

        try {
            $quote = $this->quoteRepository->get($quoteId);
            $quote->setNote($checkVal);
            $this->quoteRepository->save($quote);

            return $this->jsonResultFactory->create()->setData(['success' => true]);
        } catch (\Exception $e) {
            return $this->jsonResultFactory->create()->setData(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
