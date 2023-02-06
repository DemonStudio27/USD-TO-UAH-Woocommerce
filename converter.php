<?php
/*
Plugin Name: Dollar to Hryvnia Converter
Plugin URI: https://ds-agency.pp.ua
Description: Converts prices from dollars to Ukrainian hryvnia (UAH) using the official exchange rate from the National Bank of Ukraine
Version: 1.0
Author: Dmytro Ivasechko
Author URI: https://ds-agency.pp.ua
*/

defined( 'ABSPATH' ) or die( 'No direct access!' );

// Get the exchange rate from the National Bank of Ukraine
$exchange_rate = get_exchange_rate();

// Function to convert the price from dollars to hryvnia
function convert_to_hryvnia( $price ) {
    global $exchange_rate;
    return $price * $exchange_rate;
}

// Hook to convert prices in WooCommerce
add_filter( 'woocommerce_get_price', 'convert_to_hryvnia' );

// Function to get the exchange rate from the National Bank of Ukraine
function get_exchange_rate() {
    $url = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json';
    $response = wp_remote_get( $url );
    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    foreach ( $data as $currency ) {
        if ( $currency['cc'] == 'USD' ) {
            return $currency['rate'];
        }
    }

    return false;
}
