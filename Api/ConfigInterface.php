<?php
/**
 * Created by PhpStorm.
 * User: Barbazul
 * Date: 25/4/2017
 * Time: 11:17 PM
 */

namespace SemExpert\ProductTags\Api;

interface ConfigInterface
{
    /**
     * @return float
     */
    public function getFreeShippingThreshold();

    /**
     * @return string
     */
    public function getFreeShippingTag();
}