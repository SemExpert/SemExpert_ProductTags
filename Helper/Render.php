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
use Magento\Catalog\Pricing\Price\RegularPrice;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\PriceInfoInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use SemExpert\ProductTags\Api\ConfigInterface;

class Render extends AbstractHelper
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var TimezoneInterface
     */
    protected $localeDate;

    /**
     * Render constructor.
     * @param Context $context
     * @param ConfigInterface $config
     * @param TimezoneInterface $localeDate
     */
    public function __construct(Context $context, ConfigInterface $config, TimezoneInterface $localeDate)
    {
        parent::__construct($context);
        $this->config = $config;
        $this->localeDate = $localeDate;
    }

    public function freeShipping(Product $product)
    {
        if ($this->shouldRenderFinalPrice($product->getPriceInfo())) {
            return $this->config->getFreeShippingTag();
        }

        return '';
    }

    /**
     * @param Product $product
     * @return string
     */
    public function newProduct(Product $product)
    {
        $newsFromDate = $product->getData('news_from_date');
        $newsToDate = $product->getData('news_to_date');

        $isDateInInterval = $this->localeDate->isScopeDateInInterval($product->getStore(), $newsFromDate, $newsToDate);

        if (!$newsFromDate && !$newsToDate || !$isDateInInterval) {
            return '';
        }

        return $this->config->getNewProductTag();
    }

    public function sale(Product $product)
    {
        $regularPrice = $product->getPriceInfo()->getPrice(RegularPrice::PRICE_CODE);
        $finalPrice = $product->getPriceInfo()->getPrice(FinalPrice::PRICE_CODE);

        if ($finalPrice->getValue() < $regularPrice->getValue()) {
            return $this->config->getSaleTag();
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
