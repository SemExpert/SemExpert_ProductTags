<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 10:30 PM
 */

namespace SemExpert\ProductTags\Test\Unit\Helper;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\PriceInfoInterface;
use SemExpert\ProductTags\Helper\Render;
use SemExpert\ProductTags\Api\ConfigInterface;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockContext;

    /**
     * @var PriceInfoInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceInfo;

    /**
     * @var Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $product;

    /**
     * @var Render
     */
    public $helper;

    /**
     * @var \SemExpert\ProductTags\Api\ConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockConfig;

    /**
     * @var FinalPrice|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceMock;

    public function setUp()
    {
        $this->mockContext = $this->getMock(Context::class, [], [], '', false);
        $this->mockConfig = $this->getMock(ConfigInterface::class);
        $this->helper = new Render($this->mockContext, $this->mockConfig);

        $this->product = $this->getMockBuilder(Product::class)->disableOriginalConstructor()->getMock();
        $this->priceInfo = $this->getMock(PriceInfoInterface::class);
        $this->priceMock = $this->getMockBuilder(FinalPrice::class)->disableOriginalConstructor()->getMock();

        $this->product->method('getPriceInfo')->willReturn($this->priceInfo);
        $this->priceInfo->method('getPrice')->willReturn($this->priceMock);
    }

    public function testFreeShipping()
    {
        $tagContent = '<span class="free-shipping">Free Shipping</span>';
        $threshold = 500;

        $this->mockConfig->method('getFreeShippingThreshold')->willReturn($threshold);
        $this->mockConfig->method('getFreeShippingTag')->willReturn($tagContent);

        $this->priceMock->method('getValue')->willReturn($threshold + 1);

        $result = $this->helper->freeShipping($this->product);
        $this->assertEquals($tagContent, $result);
    }

    public function testFreeShippingIsEmptyWithLowPrice()
    {
        $this->mockConfig->method('getFreeShippingThreshold')->willReturn(500);
        $this->priceMock->method('getValue')->willReturn(100);

        $this->assertEquals('', $this->helper->freeShipping($this->product));
    }

}
