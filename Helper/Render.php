<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 10:24 PM
 */

namespace SemExpert\ProductTags\Helper;


use Magento\Catalog\Api\Data\ProductInterface;
use SemExpert\ProductTags\Model\ConfigurationInterface;

class Render
{
    /**
     * @var ConfigurationInterface
     */
    protected $config;

    public function __construct(ConfigurationInterface $config)
    {
        $this->config = $config;
    }

    public function freeShipping(ProductInterface $product)
    {
        if ($product->getPrice() > $this->config->getFreeShippingThreshold()) {
            return $this->config->getFreeShippingTag();
        }

        return '';
    }
}