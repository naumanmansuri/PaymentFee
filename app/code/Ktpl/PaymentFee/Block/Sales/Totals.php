<?php

namespace Ktpl\PaymentFee\Block\Sales;

class Totals extends \Magento\Framework\View\Element\Template {

    protected $_helper;

    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, \Ktpl\PaymentFee\Helper\Data $helper, array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
    }

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary() {
        return true;
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource() {
        return $this->_source;
    }

    public function getStore() {
        return $this->_order->getStore();
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder() {
        return $this->_order;
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals() {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        if (!$this->_source->getFeeAmount() || $this->_source->getFeeAmount() == 0) {
            return $this;
        }
        $method = $this->_order->getPayment()->getMethod();
        //pr($this->_source->getFeeAmount());


        $fee = new \Magento\Framework\DataObject(
                [
            'code' => 'fee',
            'value' => $this->_source->getFeeAmount(),
            'label' => $this->_helper->getTitle($method),
                ]
        );

        $parent->addTotalBefore($fee, 'grand_total');
        return $this;
    }

}
