<?php

namespace Ktpl\PaymentFee\Block\Adminhtml\Sales\Order\Invoice;

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

    /**
     * @return mixed
     */
    public function getInvoice() {
        return $this->getParentBlock()->getInvoice();
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals() {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();

        if (!$this->getSource()->getFeeAmount() || $this->getSource()->getFeeAmount() == 0) {
            return $this;
        }
        
        $method = $this->getParentBlock()->getOrder()->getPayment()->getMethod();
        
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
