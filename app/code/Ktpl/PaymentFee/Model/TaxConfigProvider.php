<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ktpl\PaymentFee\Model;

use Magento\Tax\Helper\Data as TaxHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Tax\Model\Config as Config;

class TaxConfigProvider extends \Magento\Tax\Model\TaxConfigProvider {

    /**
     * {@inheritdoc}
     */
    public function getConfig() {
        $defaultRegionId = $this->scopeConfig->getValue(
                \Magento\Tax\Model\Config::CONFIG_XML_PATH_DEFAULT_REGION, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        // prevent wrong assignment on shipping rate estimation requests
        if (0 == $defaultRegionId) {
            $defaultRegionId = null;
        }
        return [
            'isDisplayShippingPriceExclTax' => $this->isDisplayShippingPriceExclTax(),
            'isDisplayShippingBothPrices' => $this->isDisplayShippingBothPrices(),
            'reviewShippingDisplayMode' => $this->getDisplayShippingMode(),
            'reviewItemPriceDisplayMode' => $this->getReviewItemPriceDisplayMode(),
            'reviewTotalsDisplayMode' => $this->getReviewTotalsDisplayMode(),
            'includeTaxInGrandTotal' => $this->isTaxDisplayedInGrandTotal(),
            'isFullTaxSummaryDisplayed' => $this->isFullTaxSummaryDisplayed(),
            'isZeroTaxDisplayed' => $this->taxConfig->displayCartZeroTax(),
            'reloadOnBillingAddress' => $this->reloadOnBillingAddress(),
            'defaultCountryId' => $this->scopeConfig->getValue(
                    \Magento\Tax\Model\Config::CONFIG_XML_PATH_DEFAULT_COUNTRY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            ),
            'defaultRegionId' => $defaultRegionId,
            'defaultPostcode' => $this->scopeConfig->getValue(
                    \Magento\Tax\Model\Config::CONFIG_XML_PATH_DEFAULT_POSTCODE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            ),
            'MethodWiseLabel' => $this->getMethodWiseLabel()
        ];
    }

    public function getMethodWiseLabel() {
        $data = $this->scopeConfig->getValue(
                'paymentfee/config/fee', \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $returnAry = array();
        if (!empty($data)) {
            $data = json_decode($data, 1);
            if (is_array($data)) {
                foreach ($data as $fee) {
                    $returnAry[$fee['payment_method']] = $fee['fee_label'];
                }
            }
        }
        return $returnAry;
    }

    /**
     * Shipping mode: 'both', 'including', 'excluding'
     *
     * @return string
     */
    public function getDisplayShippingMode() {
        if ($this->taxConfig->displayCartShippingBoth()) {
            return 'both';
        }
        if ($this->taxConfig->displayCartShippingExclTax()) {
            return 'excluding';
        }
        return 'including';
    }

    /**
     * Return flag whether to display shipping price excluding tax
     *
     * @return bool
     */
    public function isDisplayShippingPriceExclTax() {
        return $this->taxHelper->displayShippingPriceExcludingTax();
    }

    /**
     * Return flag whether to display shipping price including and excluding tax
     *
     * @return bool
     */
    public function isDisplayShippingBothPrices() {
        return $this->taxHelper->displayShippingBothPrices();
    }

    /**
     * Get review item price display mode
     *
     * @return string 'both', 'including', 'excluding'
     */
    public function getReviewItemPriceDisplayMode() {
        if ($this->taxHelper->displayCartBothPrices()) {
            return 'both';
        }
        if ($this->taxHelper->displayCartPriceExclTax()) {
            return 'excluding';
        }
        return 'including';
    }

    /**
     * Get review item price display mode
     *
     * @return string 'both', 'including', 'excluding'
     */
    public function getReviewTotalsDisplayMode() {
        if ($this->taxConfig->displayCartSubtotalBoth()) {
            return 'both';
        }
        if ($this->taxConfig->displayCartSubtotalExclTax()) {
            return 'excluding';
        }
        return 'including';
    }

    /**
     * Show tax details in checkout totals section flag
     *
     * @return bool
     */
    public function isFullTaxSummaryDisplayed() {
        return $this->taxHelper->displayFullSummary();
    }

    /**
     * Display tax in grand total section or not
     *
     * @return bool
     */
    public function isTaxDisplayedInGrandTotal() {
        return $this->taxConfig->displayCartTaxWithGrandTotal();
    }

    /**
     * Reload totals(taxes) on billing address update
     *
     * @return bool
     */
    protected function reloadOnBillingAddress() {
        $quote = $this->checkoutSession->getQuote();
        $configValue = $this->scopeConfig->getValue(
                Config::CONFIG_XML_PATH_BASED_ON, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return 'billing' == $configValue || $quote->isVirtual();
    }

}
