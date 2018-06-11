<?php

namespace Ktpl\PaymentFee\Test\Unit\Model\Quote\Address\Total;

class FeeTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Ktpl\PaymentFee\Model\Quote\Address\Total */

    protected $model;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject('Ktpl\PaymentFee\Model\Quote\Address\Total');
    }

}
