<?php
/**
 * Plugin Name: I-Fulfilment Integration
 * Plugin URI: http://www.i-fulfilment.co.uk/
 * Description: Enables the WooCommerce I-Fulfilment integration with Blade IMS.
 * Author: I-Fulfilment - Edward Marriner - support.team@i-fulfilment.co.uk
 * Author URI: http://www.i-fulfilment.co.uk/
 * Version: 1.0
 * Text Domain: blade-fulfilment
 *
 * Copyright: (c) 2014 I-Fulfilment, Inc.
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
                 $new_order_statuses['wc-blade-processing']              = 'Pulling Order Into Blade IMS';
                 $new_order_statuses['wc-blade-picking']                 = 'Items Awaiting Picking';
                 $new_order_statuses['wc-blade-packed']                  = 'Items Packed, Awaiting Order Despatch';
                 $new_order_statuses['wc-blade-despatched']              = 'Order Despatched';
        }
    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'ifl_load_new_order_statuses' );
