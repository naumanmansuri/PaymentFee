<?php

namespace Ktpl\PaymentFee\Block\Adminhtml\System\Form\Field;

class PaymentFee extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray {

    protected $_columns = [];

    /**
     * @var Methods
     */
    protected $_typeRenderer;
    protected $_searchFieldRenderer;
    protected $_feetypeRenderer;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context, array $data = []
    ) {
        //pr($this->getTemplateFile());
        parent::__construct($context, $data);
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender() {
        $this->_typeRenderer = null;
        $this->_searchFieldRenderer = null;

        $this->addColumn(
                'payment_method', ['label' => __('Method'), 'style' => 'width:150px', 'renderer' => $this->_getPaymentRenderer(), 'class' => 'required']
        );

        $this->addColumn('fee', ['label' => __('Charge/Discount'), 'style' => 'width:100px', 'class' => 'required']);
        $this->addColumn('fee_type', ['label' => __('Type'), 'renderer' => $this->_getFeetypeRenderer()]);
        $this->addColumn('fee_label', ['label' => __('Label'), 'style' => 'width:100px', 'class' => 'required']);
        $this->addColumn('minimum_order', ['label' => __('Min. Order'), 'style' => 'width:100px']);
        $this->addColumn('maximum_order', ['label' => __('Max. Order'), 'style' => 'width:100px']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Retrieve active payment methods renderer
     *
     * @return Methods
     */
    protected function _getPaymentRenderer() {

        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock(
                    'Ktpl\PaymentFee\Block\Adminhtml\System\Form\Field\Methods', '', ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_typeRenderer->setClass('payemtfee_select');
        }
        return $this->_typeRenderer;
    }

    protected function _getFeetypeRenderer() {

        if (!$this->_feetypeRenderer) {
            $this->_feetypeRenderer = $this->getLayout()->createBlock(
                    'Ktpl\PaymentFee\Block\Adminhtml\System\Form\Field\FeeTypes', '', ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_typeRenderer->setClass('payemtfee_select');
        }
        return $this->_feetypeRenderer;
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row) {

        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getPaymentRenderer()->calcOptionHash($row->getPaymentMethod())] = 'selected="selected"';
        $optionExtraAttr['option_' . $this->_getFeetypeRenderer()->calcOptionHash($row->getFeeType())] = 'selected="selected"';

        $row->setData(
                'option_extra_attrs', $optionExtraAttr
        );
    }

}
