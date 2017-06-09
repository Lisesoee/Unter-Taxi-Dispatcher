<?php

/**
 * Created by PhpStorm.
 * User: rayanelhajj98
 */

/**
 * Interface PricingStrategy
 * declares the functions for a pricing strategy
 * implements the strategy pattern
 */
interface PricingStrategy
{
    /**
     * calculates the estimated payment and returns the result for the strategy
     * @param $distance
     * @param $nb_of_customers
     * @return mixed
     */
    function getEstimatedPayment($distance, $nb_of_customers);

    /**
     * returns the price per km for the strategy
     * @return mixed
     */
    function getPricePerKM();
}