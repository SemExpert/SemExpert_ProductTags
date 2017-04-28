<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 26/4/2017
 * Time: 2:34 PM
 */

namespace SemExpert\ProductTags\Model\Config;

use SemExpert\ProductTags\Api\ConfigInterface;

class Data implements ConfigInterface
{

    /**
     * @return float
     */
    public function getFreeShippingThreshold()
    {
        return 59;
    }

    /**
     * @return string
     */
    public function getFreeShippingTag()
    {
        return '<div class="flag envio-gratis"><i class="icon-envio-gratis"></i><span>Envío gratis</span></div>';
    }

    /**
     * @return string
     */
    public function getNewProductTag()
    {
        return '<div class="flag nuevo">Nuevo</div>';
    }

    /**
     * @return string
     */
    public function getSaleTag()
    {
        return '<div class="flag oportunidad">Oportunidad</div>';
    }
}
