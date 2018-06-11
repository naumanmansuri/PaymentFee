<?php

namespace Ktpl\PaymentFee\Block\Adminhtml\Sales\Order;

class Totals extends \Magento\Framework\View\Element\Template {

    protected $_helper;

    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, \Ktpl\PaymentFee\Helper\Data $helper, array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder() {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * @return mixed
     */
    public function getSource() {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return $this
     */
    public function initTotals() {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        if (!$this->getSource()->getFeeAmount() || $this->getSource()->getFeeAmount() == 0) {
            return $this;
        }

        $method = $this->getOrder()->getPayment()->getMethod();

        $total = new \Magento\Framework\DataObject(
                [
            'code' => 'fee',
            'value' => $this->getSource()->getFeeAmount(),
            'label' => $this->_helper->getTitle($method),
                ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }

}
