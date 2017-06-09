<?php

/**
 * Created by PhpStorm.
 * User: rayanelhajj98
 * Date: 8/06/2017
 * Time: 12:32 AM
 */
class SingleModeStrategy implements PricingStrategy
{

    const PRICE_PER_KM = 5;

    function getEstimatedPayment($distance, $nb_of_customers)
    {
        // TODO: Implement getEstimatedPayment() method.
        $estimated_Payment = $distance * SingleModeStrategy::PRICE_PER_KM;
        return $estimated_Payment;
    }

    function getPricePerKM()
    {
        // TODO: Implement getPricePerKM() method.
        return SingleModeStrategy::PRICE_PER_KM;
    }
}