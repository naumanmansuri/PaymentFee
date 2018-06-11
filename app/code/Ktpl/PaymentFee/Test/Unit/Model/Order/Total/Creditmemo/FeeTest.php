<?php

namespace Ktpl\PaymentFee\Test\Unit\Model\Order\Total\Creditmemo;

class FeeTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Ktpl\PaymentFee\Model\Order\Total\Creditmemo */

    protected $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject('\Ktpl\PaymentFee\Model\Order\Total\Invoice');
    }

}
