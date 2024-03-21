<?php
/*
Plugin Name: WooCommerce Paraguayan Billing fields
Plugin URI: https://github.com/ricarcya/woocommerce-billing-fields-pyg
Description: This is a WooCommerce plugin that adds extra fields to the billing address containing required information by paraguayan law (RUC, Razón Social, DNI, etc.)
Version: 1.0.5
Author: Ricardo Aveiro
Author URI: https://vetasystems.com
License: GPL2
adapted by: Ricardo Aveiro
the original plugin is for woocommerce version 2 and this is for woocommerce version 6.3
*/


//add fields RUC, Razón Social, Cédula o Documento to customer registration form
add_filter( 'woocommerce_default_address_fields', 'paraguay_custom_address_fields' );
function paraguay_custom_address_fields( $address_fields ) {
    $address_fields['billing_ruc'] = array(
        'label' => __('RUC', 'woocommerce'),
        'placeholder' => _x('RUC', 'placeholder', 'woocommerce'),
        'required' => false,
        'clear' => true,
        'type' => 'text'
    );
    $address_fields['billing_razon_social'] = array(
        'label' => __('Razón Social', 'woocommerce'),
        'placeholder' => _x('Razón Social', 'placeholder', 'woocommerce'),
        'required' => false,
        'clear' => true,
        'type' => 'text'
    );
    $address_fields['billing_dni'] = array(
        'label' => __('Cédula o Documento', 'woocommerce'),
        'placeholder' => _x('CIP', 'placeholder', 'woocommerce'),
        'required' => true,
        'clear' => true,
        'type' => 'text'
    );
    return $address_fields;
}

//save fields RUC, Razón Social, Cédula o Documento to the database
add_action('woocommerce_created_customer', 'paraguay_save_custom_fields');
function paraguay_save_custom_fields( $customer_id ) {
    if (isset($_POST['billing_ruc'])) {
        update_user_meta($customer_id, 'billing_ruc', sanitize_text_field($_POST['billing_ruc']));
    }
    if (isset($_POST['billing_razon_social'])) {
        update_user_meta($customer_id, 'billing_razon_social', sanitize_text_field($_POST['billing_razon_social']));
    }
    if (isset($_POST['billing_dni'])) {
        update_user_meta($customer_id, 'billing_dni', sanitize_text_field($_POST['billing_dni']));
    }
}

//use created fields RUC, Razón Social, Cédula o Documento on customer registration form to fill the checkout page
add_filter('woocommerce_checkout_fields', 'paraguay_checkout_fields');
function paraguay_checkout_fields($fields) {
    $fields['billing']['billing_ruc'] = array(
        'label' => __('RUC', 'woocommerce'),
        'placeholder' => _x('RUC', 'placeholder', 'woocommerce'),
        'required' => false,
        'clear' => true,
        'type' => 'text'
    );
    $fields['billing']['billing_razon_social'] = array(
        'label' => __('Razón Social', 'woocommerce'),
        'placeholder' => _x('Razón Social', 'placeholder', 'woocommerce'),
        'required' => false,
        'clear' => true,
        'type' => 'text'
    );
    $fields['billing']['billing_dni'] = array(
        'label' => __('Cédula o Documento', 'woocommerce'),
        'placeholder' => _x('CIP', 'placeholder', 'woocommerce'),
        'required' => true,
        'clear' => true,
        'type' => 'text'
    );
    return $fields;
}

//save fields RUC, Razón Social, Cédula o Documento to the database
add_action('woocommerce_checkout_update_user_meta', 'paraguay_checkout_save_custom_fields');
function paraguay_checkout_save_custom_fields( $customer_id ) {
    if (isset($_POST['billing_ruc'])) {
        update_user_meta($customer_id, 'billing_ruc', sanitize_text_field($_POST['billing_ruc']));
    }
    if (isset($_POST['billing_razon_social'])) {
        update_user_meta($customer_id, 'billing_razon_social', sanitize_text_field($_POST['billing_razon_social']));
    }
    if (isset($_POST['billing_dni'])) {
        update_user_meta($customer_id, 'billing_dni', sanitize_text_field($_POST['billing_dni']));
    }
}

//Add Ruc, Razon Social and Cedula o Documento fields to the Order API response inside billing section
add_filter( 'woocommerce_rest_prepare_order_object', 'paraguay_wc_rest_prepare_order_object', 10, 3 );
function paraguay_wc_rest_prepare_order_object( $response, $object, $request ) {
    // Get the value
    $ruc_meta_field = ( $value = get_post_meta($object->get_id(), '_billing_ruc', true) ) ? $value : '';
    $razon_social_meta_field = ( $value = get_post_meta($object->get_id(), '_billing_razon_social', true) ) ? $value : '';
    $dni_meta_field = ( $value = get_post_meta($object->get_id(), '_billing_dni', true) ) ? $value : '';

    $response->data['billing']['ruc'] = $ruc_meta_field;
    $response->data['billing']['razon_social'] = $razon_social_meta_field;
    $response->data['billing']['dni'] = $dni_meta_field;

    return $response;
}

//show fields RUC, Razón Social, Cédula o Documento in the admin order page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'paraguay_admin_order_data_after_billing_address', 10, 1 );
function paraguay_admin_order_data_after_billing_address( $order ) {
    $ruc = get_post_meta( $order->get_id(), '_billing_ruc', true );
    $razon_social = get_post_meta( $order->get_id(), '_billing_razon_social', true );
    $dni = get_post_meta( $order->get_id(), '_billing_dni', true );

    echo '<p><strong>'.__('RUC').':</strong> ' . $ruc . '</p>';
    echo '<p><strong>'.__('Razón Social').':</strong> ' . $razon_social . '</p>';
    echo '<p><strong>'.__('Cédula o Documento').':</strong> ' . $dni . '</p>';
}

//show fields RUC, Razón Social, Cédula o Documento in the order details page
add_action( 'woocommerce_order_details_after_order_table', 'paraguay_order_details_after_order_table', 10, 1 );
function paraguay_order_details_after_order_table( $order ) {
    $ruc = get_post_meta( $order->get_id(), '_billing_ruc', true );
    $razon_social = get_post_meta( $order->get_id(), '_billing_razon_social', true );
    $dni = get_post_meta( $order->get_id(), '_billing_dni', true );

    echo '<p><strong>'.__('RUC').':</strong> ' . $ruc . '</p>';
    echo '<p><strong>'.__('Razón Social').':</strong> ' . $razon_social . '</p>';
    echo '<p><strong>'.__('Cédula o Documento').':</strong> ' . $dni . '</p>';
}

//if plugin is deleted, remove fields RUC, Razón Social, Cédula o Documento from the database
register_uninstall_hook(__FILE__, 'paraguay_uninstall_hook');
function paraguay_uninstall_hook() {
    global $wpdb;
    $wpdb->query("DELETE FROM wp_usermeta WHERE meta_key IN ('billing_ruc', 'billing_razon_social', 'billing_dni')");
}
