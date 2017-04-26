<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 26/4/2017
 * Time: 2:34 PM
 */

namespace SemExpert\ProductTags\Model\Config;

use SemExpert\ProductTags\Model\ConfigInterface;

class Data implements ConfigInterface
{

    /**
     * @return float
     */
    public function getFreeShippingThreshold()
    {
        return 10;
    }

    /**
     * @return string
     */
    public function getFreeShippingTag()
    {
        return 'Hola!!!';
    }
}