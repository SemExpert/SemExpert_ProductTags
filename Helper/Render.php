<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 10:24 PM
 */

namespace SemExpert\ProductTags\Helper;


use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\SaleableInterface;
use SemExpert\ProductTags\Model\ConfigInterface;

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

    public function freeShipping(SaleableInterface $product)
    {
        if ($product->getPriceInfo()->getPrice(FinalPrice::PRICE_CODE)->getValue() > $this->config->getFreeShippingThreshold()) {
            return $this->config->getFreeShippingTag();
        }

        return '';
    }
}