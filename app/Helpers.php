<?php

if(!function_exists('getPrice')){
    function getPrice($priceInDecimals){
        $price = floatval($priceInDecimals) / 100;

        return number_format($price, 2, ',', ' ') . ' €';
    }
}
