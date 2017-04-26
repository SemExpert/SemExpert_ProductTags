<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 10:30 PM
 */

namespace SemExpert\ProductTags\Test\Unit\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use SemExpert\ProductTags\Helper\Render;
use SemExpert\ProductTags\Model\ConfigurationInterface;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $product;

    /**
     * @var Render
     */
    public $helper;

    /**
     * @var ConfigurationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    public function setUp()
    {
        $this->config = $this->getMock(ConfigurationInterface::class);
        $this->helper = new Render($this->config);
        $this->product = $this->getMock(ProductInterface::class);
    }

    public function testFreeShipping()
    {
        $tagContent = '<div class="flag envio-gratis"><i class="icon-envio-gratis"></i><span>Envío gratis</span></div>';

        $this->config->method('getFreeShippingThreshold')->willReturn(500);
        $this->config->expects($this->once())->method('getFreeShippingTag')->willReturn($tagContent);
        $this->product->method('getPrice')->willReturn(1000);

        $result = $this->helper->freeShipping($this->product);
        $this->assertEquals($tagContent, $result);
    }

    public function testFreeShippingIsEmptyWithLowPrice()
    {
        $this->config->method('getFreeShippingThreshold')->willReturn(500);
        $this->product->method('getPrice')->willReturn(100);

        $this->assertEquals('', $this->helper->freeShipping($this->product));
    }

}
