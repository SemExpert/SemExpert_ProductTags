<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 10:30 PM
 */

namespace SemExpert\ProductTags\Test\Unit\Helper;

use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\PriceInfoInterface;
use Magento\Framework\Pricing\SaleableInterface;
use SemExpert\ProductTags\Helper\Render;
use SemExpert\ProductTags\Model\ConfigInterface;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var PriceInfoInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceInfo;

    /**
     * @var SaleableInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $product;

    /**
     * @var Render
     */
    public $helper;

    /**
     * @var ConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    /**
     * @var FinalPrice|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $price;

    public function setUp()
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = $this->getMock(ConfigInterface::class);
        $this->helper = new Render($this->context, $this->config);

        $this->product = $this->getMock(SaleableInterface::class);
        $this->priceInfo = $this->getMock(PriceInfoInterface::class);
        $this->price = $this->getMockBuilder(FinalPrice::class)->disableOriginalConstructor()->getMock();

        $this->product->method('getPriceInfo')->willReturn($this->priceInfo);
        $this->priceInfo->method('getPrice')->willReturn($this->price);
    }

    public function testFreeShipping()
    {
        $tagContent = '<div class="flag envio-gratis"><i class="icon-envio-gratis"></i><span>Envío gratis</span></div>';

        $this->config->method('getFreeShippingThreshold')->willReturn(500);
        $this->config->method('getFreeShippingTag')->willReturn($tagContent);
        $this->price->method('getValue')->willReturn(1000);

        $result = $this->helper->freeShipping($this->product);
        $this->assertEquals($tagContent, $result);
    }

    public function testFreeShippingIsEmptyWithLowPrice()
    {
        $this->config->method('getFreeShippingThreshold')->willReturn(500);
        $this->price->method('getValue')->willReturn(100);

        $this->assertEquals('', $this->helper->freeShipping($this->product));
    }

}
