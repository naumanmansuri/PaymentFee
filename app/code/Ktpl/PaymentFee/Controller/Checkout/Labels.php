<?php

namespace Ktpl\PaymentFee\Controller\Checkout;

use Magento\Framework\App\Action\Context;

class Labels extends \Magento\Framework\App\Action\Action {

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJson;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    public function __construct(
    Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Ktpl\PaymentFee\Helper\Data $helper, \Magento\Framework\Controller\Result\JsonFactory $resultJson, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
        $this->_resultJson = $resultJson;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Trigger to re-calculate the collect Totals
     *
     * @return bool
     */
    public function execute() {

        $price = $_POST['priceData'];

        $title = $this->_helper->getTitle($price);

        $response = [
            'title' => $title
        ];

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultJson = $this->_resultJson->create();
        return $resultJson->setData($response);
    }

}
