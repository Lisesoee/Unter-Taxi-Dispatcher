<?php

/**
 * Created by PhpStorm.
 * User: rayanelhajj98
 * Date: 8/06/2017
 * Time: 12:32 AM
 */
class SharedModeStrategy implements PricingStrategy
{
    const PRICE_PER_KM = 5;

    function getEstimatedPayment($distance, $nb_of_customers)
    {
        // TODO: Implement getEstimatedPayment() method.
        $estimated_payment = ($distance * SharedModeStrategy::PRICE_PER_KM) / $nb_of_customers;
        return $estimated_payment;
    }

    function getPricePerKM()
    {
        // TODO: Implement getPricePerKM() method.
        return SharedModeStrategy::PRICE_PER_KM;
    }
}