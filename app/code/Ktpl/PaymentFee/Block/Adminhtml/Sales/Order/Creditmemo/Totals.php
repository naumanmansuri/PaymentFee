<?php

namespace Ktpl\PaymentFee\Block\Adminhtml\Sales\Order\Creditmemo;

class Totals extends \Magento\Framework\View\Element\Template {

    protected $_helper;

    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, \Ktpl\PaymentFee\Helper\Data $helper, array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource() {
        return $this->getParentBlock()->getSource();
    }

    public function getCreditmemo() {
        return $this->getParentBlock()->getCreditmemo();
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals() {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();

        if (!$this->getSource()->getFeeAmount() || $this->getSource()->getFeeAmount() == 0) {
            return $this;
        }
        $method = $this->getParentBlock()->getOrder()->getPayment()->getMethod();

        $fee = new \Magento\Framework\DataObject(
                [
            'code' => 'fee',
            'strong' => false,
            'value' => $this->getSource()->getFeeAmount(),
            'label' => $this->_helper->getTitle($method),
                ]
        );

        $this->getParentBlock()->addTotalBefore($fee, 'grand_total');

        return $this;
    }

}
