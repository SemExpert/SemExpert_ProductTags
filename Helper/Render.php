<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 10:24 PM
 */

namespace SemExpert\ProductTags\Helper;


use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\PriceInfoInterface;
use SemExpert\ProductTags\Api\ConfigInterface;

class Render extends AbstractHelper
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(Context $context, ConfigInterface $config)
    {
        parent::__construct($context);
        $this->config = $config;
    }

    public function freeShipping(Product $product)
    {
        if ($this->shouldRenderFinalPrice($product->getPriceInfo())) {
            return $this->config->getFreeShippingTag();
        }

        return '';
    }

    /**
     * @param PriceInfoInterface $priceInfo
     * @return bool
     */
    protected function shouldRenderFinalPrice(PriceInfoInterface $priceInfo)
    {
        return $priceInfo->getPrice(FinalPrice::PRICE_CODE)->getValue() > $this->config->getFreeShippingThreshold();
    }
}