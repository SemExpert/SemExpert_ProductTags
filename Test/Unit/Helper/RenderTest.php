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
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use SemExpert\ProductTags\Helper\Render;
use SemExpert\ProductTags\Api\ConfigInterface;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    const NEW_PRODUCT_TAG_CONTENT = '<span class="new-product">New Product</span>';
    const FREE_SHIPPING_TAG_CONTENT = '<span class="free-shipping">Free Shipping</span>';
    const FREE_SHIPPING_THRESHOLD = 500;
    const PAST_DATE = '2000-01-01';
    const FUTURE_DATE = '2050-01-01';
    const DEFAULT_STORE_CODE = 'default';
    /**
     * @var Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var PriceInfoInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceInfoMock;

    /**
     * @var Product|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var Render
     */
    public $helper;

    /**
     * @var \SemExpert\ProductTags\Api\ConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configMock;

    /**
     * @var FinalPrice|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceMock;

    /**
     * @var TimezoneInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $localeMock;

    ###########################################################################
    ## Setup Methods

    public function setUp()
    {
        $this->contextMock = $this->getMock(Context::class, [], [], '', false);
        $this->configMock = $this->getMock(ConfigInterface::class);
        $this->localeMock = $this->getMock(TimezoneInterface::class);

        $intervalMap = [
            [self::DEFAULT_STORE_CODE, self::PAST_DATE, self::FUTURE_DATE, true],
            [self::DEFAULT_STORE_CODE, $this->anything(), self::PAST_DATE, false],
            [self::DEFAULT_STORE_CODE, self::FUTURE_DATE, $this->anything(), false]
        ];

        $this->localeMock->method('isScopeDateInInterval')->will($this->returnValueMap($intervalMap));

        $this->helper = new Render($this->contextMock, $this->configMock, $this->localeMock);

        $this->productMock = $this->getMock(Product::class, [], [], '', false);
        $this->productMock->method('getStore')->willReturn(self::DEFAULT_STORE_CODE);

        $this->priceInfoMock = $this->getMock(PriceInfoInterface::class);
        $this->priceMock = $this->getMock(FinalPrice::class, [], [], '', false);

        $this->productMock->method('getPriceInfo')->willReturn($this->priceInfoMock);
        $this->priceInfoMock->method('getPrice')->willReturn($this->priceMock);
    }

    protected function setupNewProductConfig($value = self::NEW_PRODUCT_TAG_CONTENT)
    {
        $this->configMock->method('getNewProductTag')->willReturn($value);
    }

    protected function setupProductAsNew()
    {
        $datesMap = [
            ['news_from_date', null, self::PAST_DATE],
            ['news_to_date', null, self::FUTURE_DATE]
        ];

        $this->productMock->method('getData')->will($this->returnValueMap($datesMap));
    }

    ###########################################################################
    ### Actual Tests

    public function testFreeShipping()
    {
        $this->configMock->method('getFreeShippingThreshold')->willReturn(self::FREE_SHIPPING_THRESHOLD);
        $this->configMock->method('getFreeShippingTag')->willReturn(self::FREE_SHIPPING_TAG_CONTENT);

        $this->priceMock->method('getValue')->willReturn(self::FREE_SHIPPING_THRESHOLD + 1);

        $result = $this->helper->freeShipping($this->productMock);
        $this->assertEquals(self::FREE_SHIPPING_TAG_CONTENT, $result);
    }

    public function testFreeShippingIsEmptyWithLowPrice()
    {
        $this->configMock->method('getFreeShippingThreshold')->willReturn(self::FREE_SHIPPING_THRESHOLD);
        $this->configMock->method('getFreeShippingTag')->willReturn(self::FREE_SHIPPING_TAG_CONTENT);
        $this->priceMock->method('getValue')->willReturn(self::FREE_SHIPPING_THRESHOLD - 1);

        $this->assertEquals('', $this->helper->freeShipping($this->productMock));
    }

    public function testNewProduct()
    {
        $this->setupProductAsNew();
        $this->setupNewProductConfig();

        $this->assertSame(self::NEW_PRODUCT_TAG_CONTENT, $this->helper->newProduct($this->productMock));
    }

    public function testNewProductIsEmptyWithoutInfo()
    {
        $this->setupNewProductConfig();
        $this->assertSame('', $this->helper->newProduct($this->productMock));
    }

    public function testNewProductTagMatchesConfig()
    {
        $config = '<span>Different Tag</span>';

        $this->setupNewProductConfig($config);
        $this->setupProductAsNew();

        $this->assertSame($config, $this->helper->newProduct($this->productMock));
    }

    public function testNewProductIsEmptyOnOldProduct()
    {
        $datesMap = [
            ['news_from_date', null, self::PAST_DATE],
            ['news_to_date', null, self::PAST_DATE]
        ];

        $this->productMock->method('getData')->will($this->returnValueMap($datesMap));
        $this->setupNewProductConfig();

        $this->assertSame('', $this->helper->newProduct($this->productMock));
    }

    public function testNewProductIsEmptyOnFutureProduct()
    {
        $datesMap = [
            ['news_from_date', null, self::FUTURE_DATE],
            ['news_to_date', null, self::FUTURE_DATE]
        ];

        $this->productMock->method('getData')->will($this->returnValueMap($datesMap));
        $this->setupNewProductConfig();

        $this->assertSame('', $this->helper->newProduct($this->productMock));
    }

}
