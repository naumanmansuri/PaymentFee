<?php

namespace Ktpl\PaymentFee\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    /**
     * Recipient fixed amount of custom payment config path
     */
    const CONFIG_PAYMENT_FEE = 'paymentfee/config/';

    /**
     * Total Code
     */
    const TOTAL_CODE = 'fee_amount';

    /**
     * @var array
     */
    public $methodFee = NULL;

    /**
     * Constructor
     */
    public function __construct(
    \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_getMethodFee();
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return array
     */
    protected function _getMethodFee() {

        if (is_null($this->methodFee)) {
            $initialFees = $this->getConfig('fee');
            $fees = json_decode($initialFees, 1);
            //$fees = is_array($initialFees) ? $initialFees : unserialize($initialFees);
            if (is_array($fees)) {
                foreach ($fees as $fee) {
                    $this->methodFee[$fee['payment_method']] = array(
                        'fee' => $fee['fee'],
                        'fee_type' => $fee['fee_type'],
                        'fee_label' => $fee['fee_label'],
                        'minimum_order' => $fee['minimum_order'],
                        'maximum_order' => $fee['maximum_order']
                    );
                }
            }
        }
        return $this->methodFee;
    }

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '') {
        if ($field) {
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            return $this->scopeConfig->getValue(self::CONFIG_PAYMENT_FEE . $field, $storeScope);
        }
        return NULL;
    }

    /**
     * Check if Extension is Enabled config
     * @return bool
     */
    public function isEnabled() {
        return $this->getConfig('enabled');
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return bool
     */
    public function canApply(\Magento\Quote\Model\Quote $quote) {

        /*         * @TODO check module or config* */
        if ($this->isEnabled()) {
            if ($method = $quote->getPayment()->getMethod()) {
                if (isset($this->methodFee[$method])) {
                    $totals = $quote->getSubtotal();
                    if ((empty($this->methodFee[$method]['minimum_order']) || $this->methodFee[$method]['minimum_order'] <= $totals) && (empty($this->methodFee[$method]['maximum_order']) || $this->methodFee[$method]['maximum_order'] >= $totals)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return float|int
     */
    public function getFee(\Magento\Quote\Model\Quote $quote) {
        $method = $quote->getPayment()->getMethod();
        $fee = $this->methodFee[$method]['fee'];
        $feeType = $this->methodFee[$method]['fee_type'];
        if ($feeType == \Magento\Shipping\Model\Carrier\AbstractCarrier::HANDLING_TYPE_FIXED) {
            return round($fee,2);
        } else {
            $totals = $quote->getSubtotal();
            return round(($totals * ($fee / 100)),2);
        }
    }

    public function getTitle($method) {
        if (isset($this->methodFee[$method]['fee_label'])) {
            return $this->methodFee[$method]['fee_label'];
        }
        return '';
    }

}
