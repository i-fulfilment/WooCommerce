<?php
/**
 * Plugin Name: WooCommerce I-Fulfilment Integration
 * Plugin URI: http://www.i-fulfilment.co.uk/
 * Description: Enables the WooCommerce I-Fulfilment integration with Blade IMS.
 * Author: I-Fulfilment - Developer Team - support.team@i-fulfilment.co.uk
 * Author URI: http://www.i-fulfilment.co.uk/
 * Version: 2.0.3
 * Text Domain: i-fulfilment-integration
 *
 * Copyright: (c) 2014 I-Fulfilment, Inc.
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists( 'WP_Http' ) )
    include_once( ABSPATH . WPINC. '/class-http.php' );

// Register new status
function ifl_create_new_order_statuses() {

    // Create the status that gets set when blade picks up the order for the first time
    register_post_status( 'wc-blade-processing', array(
        'label'                     => 'Being processed by Blade',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Being processed by Blade <span class="count">(%s)</span>', 'Being processed by Blade <span class="count">(%s)</span>' )
    ) );

    // Create the awaiting picking status
    register_post_status( 'wc-blade-picking', array(
        'label'                     => 'Awaiting Picking',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Awaiting Picking <span class="count">(%s)</span>', 'Awaiting Picking <span class="count">(%s)</span>' )
    ) );

    // Create the packed status
    register_post_status( 'wc-blade-packed', array(
        'label'                     => 'Packed, Awaiting Despatch',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Packed, Awaiting Despatch <span class="count">(%s)</span>', 'Packed, Awaiting Despatch <span class="count">(%s)</span>' )
    ) );

    // Create the packed status
    register_post_status( 'wc-blade-despatched', array(
        'label'                     => 'Despatched',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Despatched <span class="count">(%s)</span>', 'Despatched <span class="count">(%s)</span>' )
    ) );
}

add_action( 'init', 'ifl_create_new_order_statuses' );

// Add to list of WC Order statuses
function ifl_load_new_order_statuses( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        // Rename the default order status 'Processing' to say 'Waiting for fulfilment' to make it clearer.
        if ( 'wc-processing' === $key && $status == 'Processing') {

		 $new_order_statuses['wc-processing'] = 'Waiting For Fulfilment';

		// Add the new order statuses
       		 $new_order_statuses['wc-blade-processing']        = 'Pulling Order Into Blade IMS';
       		 $new_order_statuses['wc-blade-picking']		    = 'Items Awaiting Picking';
       		 $new_order_statuses['wc-blade-packed']			  =  'Items Packed, Awaiting Order Despatch';
       		 $new_order_statuses['wc-blade-despatched']		= 'Order Despatched';
	  }
    }

    return $new_order_statuses;
}


add_filter( 'wc_order_statuses', 'ifl_load_new_order_statuses' );

add_action( 'admin_menu', 'ifl_register_menu_page' );

function ifl_register_menu_page(){

    // Check they have woo commerce installed already!
    if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

        // Opps, they dont have WooCommerce!
        add_menu_page( 'I-Fulfilment Integration', 'I-Fulfilment', 'manage_options', 'i-fulfilment-integration/error.php', '', '', 55.5 );

        return;
    }

    if ( current_user_can( 'manage_woocommerce' ) ):

    $shipping_methods = WC()->shipping->load_shipping_methods();

    $shipping = array(
        'standard' => array(
            'enabled' => isset($shipping_methods['flat_rate']) && ($shipping_methods['flat_rate']->enabled == 'yes') ? true : false,
            'id' => isset($shipping_methods['flat_rate']) ? $shipping_methods['flat_rate']->id : 0,
        ),
        'free' => array(
            'enabled' => isset($shipping_methods['free_shipping']) && ($shipping_methods['free_shipping']->enabled == 'yes') ? true : false,
            'id' => isset($shipping_methods['free_shipping']) ? $shipping_methods['free_shipping']->id : 0,
        ),
        'international' => array(
            'enabled' => isset($shipping_methods['international_delivery']) && ($shipping_methods['international_delivery']->enabled == 'yes') ? true : false,
            'id' => isset($shipping_methods['international_delivery']) ? $shipping_methods['international_delivery']->id : 0
        ),
        'local' => array(
            'enabled' => isset($shipping_methods['local_delivery']) && ($shipping_methods['local_delivery']->enabled == 'yes') ? true : false,
            'id' => isset($shipping_methods['local_delivery']) ? $shipping_methods['local_delivery']->id : 0
        ),
        'pickup' => array(
            'enabled' => isset($shipping_methods['local_pickup']) && ($shipping_methods['local_pickup']->enabled == 'yes') ? true : false,
            'id' => isset($shipping_methods['local_pickup']) ? $shipping_methods['local_pickup']->id : 0
        )
    );

    update_option('ifl_shipping_overview', $shipping ); // We dont allow local pickup!

    // Grab the various WooCommerce order statuses
    $order_statuses = wc_get_order_statuses();

    // Defaults
    $key = 'NOT_SET';
    $secret = 'NOT_SET';

    // Loop over all the users to find the API key account
    foreach(get_users() as $user){

        // Check this user has WooCommerce details
        if($user->has_caps('manage_woocommerce')) {

            $key = get_user_meta($user->data->ID, 'woocommerce_api_consumer_key');
            $secret = get_user_meta($user->data->ID, 'woocommerce_api_consumer_secret');

            // Lets break out of the loop now if we found the details
            if($key && $secret)  break;
        }
    }

    // Check we are all good on the woocommerce front!
    if(get_option('woocommerce_api_enabled') != 'yes'){

        update_option('ifl_woocommerce_status', 'API Disabled');    // No WooCommerce API!

    } elseif(get_option('woocommerce_version') < 2.2) {

        update_option('ifl_woocommerce_status', 'WooCommerce' . get_option('woocommerce_version') ); // WooCommerce Too Old!

    } elseif(empty($key)) {

        update_option('ifl_woocommerce_status', 'No API Key' ); // API not setup

    } elseif(empty($secret)) {

        update_option('ifl_woocommerce_status', 'No API Token' ); // API not set up!

    } elseif(empty($order_statuses)) {

        update_option('ifl_woocommerce_status', 'No Order Statuses' ); // WooCommerce Too Old!

    } elseif( $shipping['pickup']['enabled'] || $shipping['local']['enabled']) {

        update_option('ifl_woocommerce_status', 'Invalid Shipping Methods' ); // We dont allow local pickup!

    } elseif( !$shipping['standard']['enabled'] && !$shipping['free']['enabled'] && !$shipping['international']['enabled']) {

        update_option('ifl_woocommerce_status', 'No Valid Shipping Methods' ); // We dont allow local pickup!

    } else {

        update_option('ifl_woocommerce_status', 'Good' );    // Seems to be fine!

    }

    // Check wordpress is all okay!
    if(get_bloginfo('version') < 4 || get_bloginfo('version') >= 5){

        update_option('ifl_health_check', 'Unsupported Wordpress version (' . get_bloginfo('version') . ')');    // No WooCommerce API!

    } else {

        update_option('ifl_health_check', 'Good' );    // WooCommerce Too Old!

    }

    // Check our integration is working okay!
    if(empty($order_statuses['wc-blade-processing'])){

        update_option('ifl_integration_status', 'Missing Processing Status');    // No WooCommerce API!

    } elseif(empty($order_statuses['wc-blade-picking'])){

        update_option('ifl_integration_status', 'Missing Picking Status');    // No WooCommerce API!

    } elseif(empty($order_statuses['wc-blade-packed'])){

        update_option('ifl_integration_status', 'Missing Packed Status');    // No WooCommerce API!

    } elseif(empty($order_statuses['wc-blade-despatched'])){

        update_option('ifl_integration_status', 'Missing Despatched Status');    // No WooCommerce API!

    } else {

        update_option('ifl_integration_status',  'Good');    // WooCommerce Too Old!

    }


    add_menu_page( 'I-Fulfilment Integration', 'I-Fulfilment', 'manage_options', 'i-fulfilment-integration/admin.php', '', '', 55.5 );

    function ifl_plugin_activate() {

        $request = new WP_Http;
        $request->request( 'https://bdes.i-fulfilment.co.uk/woo_commerce/Install/register/' . urlencode(get_site_url()));

    }

    register_activation_hook( __FILE__, 'ifl_plugin_activate' );

    endif;

}
