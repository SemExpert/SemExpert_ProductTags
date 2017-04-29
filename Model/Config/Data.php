<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 26/4/2017
 * Time: 2:34 PM
 */

namespace SemExpert\ProductTags\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use SemExpert\ProductTags\Api\ConfigInterface;

class Data implements ConfigInterface
{
    const FREE_SHIPPING_THRESHOLD_PATH = 'product_tags/free_shipping/threshold';
    const FREE_SHIPPING_TAG_CONTENT_PATH = 'product_tags/free_shipping/tag_content';
    const NEW_PRODUCT_TAG_CONTENT_PATH = 'product_tags/new_product/tag_content';
    const SALE_TAG_CONTENT_PATH = 'product_tags/sale/tag_content';
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return float
     */
    public function getFreeShippingThreshold()
    {
        return $this->scopeConfig->getValue(self::FREE_SHIPPING_THRESHOLD_PATH, 'store');
    }

    /**
     * @return string
     */
    public function getFreeShippingTag()
    {
        return $this->scopeConfig->getValue(self::FREE_SHIPPING_TAG_CONTENT_PATH, 'store');
    }

    /**
     * @return string
     */
    public function getNewProductTag()
    {
        return $this->scopeConfig->getValue(self::NEW_PRODUCT_TAG_CONTENT_PATH, 'store');
    }

    /**
     * @return string
     */
    public function getSaleTag()
    {
        return $this->scopeConfig->getValue(self::SALE_TAG_CONTENT_PATH, 'store');
    }
}
