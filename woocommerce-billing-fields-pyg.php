<?php
/*
Plugin Name: WooCommerce Romanian Billing fields
Plugin URI: https://github.com/ricarcya/woocommerce-billing-fields-pyg
Description: This is a WooCommerce plugin that adds extra fields to the billing address containing required information by paraguayan law (RUC, Razón Social, DNI, etc.)
Version: 1.0.4
Author: Andrei Neamtu
Author URI: https://vetasystems.com
License: GPL2
adapted by: Ricardo Aveiro
the original plugin is for woocommerce version 2 and this is for woocommerce version 6.3
*/
add_filter( 'woocommerce_checkout_fields', 'woo_paraguay_custom_checkout_fields' );
function woo_paraguay_custom_checkout_fields( $fields ) {
    $fields['billing']['billing_ruc'] = array(
        'label' => __('RUC', 'woocommerce'),
        'placeholder' => _x('RUC', 'placeholder', 'woocommerce'),
        'required' => true,
        'clear' => true,
        'type' => 'text'
    );
    $fields['billing']['billing_razon_social'] = array(
        'label' => __('Razón Social', 'woocommerce'),
        'placeholder' => _x('Razón Social', 'placeholder', 'woocommerce'),
        'required' => true,
        'clear' => true,
        'type' => 'text'
    );
    $fields['billing']['billing_dni'] = array(
        'label' => __('DNI', 'woocommerce'),
        'placeholder' => _x('DNI', 'placeholder', 'woocommerce'),
        'required' => true,
        'clear' => true,
        'type' => 'text'
    );
    $fields['billing']['billing_telefono'] = array(
        'label' => __('Teléfono', 'woocommerce'),
        'placeholder' => _x('Teléfono', 'placeholder', 'woocommerce'),
        'required' => true,
        'clear' => true,
        'type' => 'text'
    );
    return $fields;
}

//saving fields
add_action( 'woocommerce_checkout_update_order_meta', 'paraguay_custom_checkout_fields');
function paraguay_custom_checkout_fields( $order_id ) {
    if ( ! empty( $_POST['billing_ruc'] ) ) {
        update_post_meta( $order_id, '_billing_ruc', sanitize_text_field( $_POST['billing_ruc'] ) );
    }
    if ( ! empty( $_POST['billing_razon_social'] ) ) {
        update_post_meta( $order_id, '_billing_razon_social', sanitize_text_field( $_POST['billing_razon_social'] ) );
    }
    if ( ! empty( $_POST['billing_dni'] ) ) {
        update_post_meta( $order_id, '_billing_dni', sanitize_text_field( $_POST['billing_dni'] ) );
    }
    if ( ! empty( $_POST['billing_telefono'] ) ) {
        update_post_meta( $order_id, '_billing_telefono', sanitize_text_field( $_POST['billing_telefono'] ) );
    }
}

//show the fields in the order administration page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'paraguay_custom_checkout_fields_display_admin_order_meta', 10, 1 );
function paraguay_custom_checkout_fields_display_admin_order_meta($order){
    echo '<p><strong>'.__('RUC').':</strong> ' . get_post_meta( $order->id, '_billing_ruc', true ) . '</p>';
    echo '<p><strong>'.__('Razón Social').':</strong> ' . get_post_meta( $order->id, '_billing_razon_social', true ) . '</p>';
    echo '<p><strong>'.__('DNI').':</strong> ' . get_post_meta( $order->id, '_billing_dni', true ) . '</p>';
    echo '<p><strong>'.__('Teléfono').':</strong> ' . get_post_meta( $order->id, '_billing_telefono', true ) . '</p>';
}

//show fields in the order
add_filter('woocommerce_order_formatted_billing_address', 'paraguay_custom_checkout_fields_display_order', 10, 2);
function paraguay_custom_checkout_fields_display_order($address, $order){
    $address['RUC'] = get_post_meta( $order->id, '_billing_ruc', true );
    $address['Razón Social'] = get_post_meta( $order->id, '_billing_razon_social', true );
    $address['DNI'] = get_post_meta( $order->id, '_billing_dni', true );
    $address['Teléfono'] = get_post_meta( $order->id, '_billing_telefono', true );
    return $address;
}

//add fields to the emails
add_filter('woocommerce_email_order_meta_keys', 'paraguay_custom_checkout_fields_email_order_meta_keys');
function paraguay_custom_checkout_fields_email_order_meta_keys( $keys ) {
    $keys['RUC'] = '_billing_ruc';
    $keys['Razón Social'] = '_billing_razon_social';
    $keys['DNI'] = '_billing_dni';
    $keys['Teléfono'] = '_billing_telefono';
    return $keys;
}
