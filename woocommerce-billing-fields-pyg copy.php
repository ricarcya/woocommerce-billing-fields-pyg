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

//add fields to customer registration form
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
    $address_fields['billing_telefono'] = array(
        'label' => __('Teléfono', 'woocommerce'),
        'placeholder' => _x('Teléfono', 'placeholder', 'woocommerce'),
        'required' => false,
        'clear' => true,
        'type' => 'text'
    );
    return $address_fields;
}

//add fields to the customer account page
add_action( 'woocommerce_edit_account_form', 'paraguay_custom_edit_account_form' );
function paraguay_custom_edit_account_form() {
    $user_id = get_current_user_id();
    $ruc = get_user_meta( $user_id, 'billing_ruc', true );
    $razon_social = get_user_meta( $user_id, 'billing_razon_social', true );
    $dni = get_user_meta( $user_id, 'billing_dni', true );
    $telefono = get_user_meta( $user_id, 'billing_telefono', true );
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_ruc"><?php _e( 'RUC', 'woocommerce' ); ?></label>
        <input type="text" class="input-text" name="billing_ruc" id="billing_ruc" value="<?php echo esc_attr( $ruc ); ?>" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_razon_social"><?php _e( 'Razón Social', 'woocommerce' ); ?></label>
        <input type="text" class="input-text" name="billing_razon_social" id="billing_razon_social" value="<?php echo esc_attr( $razon_social ); ?>" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_dni"><?php _e( 'DNI', 'woocommerce' ); ?></label>
        <input type="text" class="input-text" name="billing_dni" id="billing_dni" value="<?php echo esc_attr( $dni ); ?>" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_telefono"><?php _e( 'Teléfono', 'woocommerce' ); ?></label>
        <input type="text" class="input-text" name="billing_telefono" id="billing_telefono" value="<?php echo esc_attr( $telefono ); ?>" />
    </p>
    <?php
}

//save fields in the customer account page
add_action( 'woocommerce_save_account_details', 'paraguay_custom_save_account_details' );
function paraguay_custom_save_account_details( $user_id ) {
    if ( isset( $_POST['billing_ruc'] ) ) {
        update_user_meta( $user_id, 'billing_ruc', sanitize_text_field( $_POST['billing_ruc'] ) );
    }
    if ( isset( $_POST['billing_razon_social'] ) ) {
        update_user_meta( $user_id, 'billing_razon_social', sanitize_text_field( $_POST['billing_razon_social'] ) );
    }
    if ( isset( $_POST['billing_dni'] ) ) {
        update_user_meta( $user_id, 'billing_dni', sanitize_text_field( $_POST['billing_dni'] ) );
    }
    if ( isset( $_POST['billing_telefono'] ) ) {
        update_user_meta( $user_id, 'billing_telefono', sanitize_text_field( $_POST['billing_telefono'] ) );
    }
}

//add fields to the customer account page
add_action( 'woocommerce_register_form', 'paraguay_custom_register_form' );
function paraguay_custom_register_form() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_ruc"><?php _e( 'RUC', 'woocommerce' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_ruc" id="billing_ruc" value="<?php if ( ! empty( $_POST['billing_ruc'] ) ) echo esc_attr( $_POST['billing_ruc'] ); ?>" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_razon_social"><?php _e( 'Razón Social', 'woocommerce' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_razon_social" id="billing_razon_social" value="<?php if ( ! empty( $_POST['billing_razon_social'] ) ) echo esc_attr( $_POST['billing_razon_social'] ); ?>" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_dni"><?php _e( 'DNI', 'woocommerce' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_dni" id="billing_dni" value="<?php if ( ! empty( $_POST['billing_dni'] ) ) echo esc_attr( $_POST['billing_dni'] ); ?>" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_telefono"><?php _e( 'Teléfono', 'woocommerce' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_telefono" id="billing_telefono" value="<?php if ( ! empty( $_POST['billing_telefono'] ) ) echo esc_attr( $_POST['billing_telefono'] ); ?>" />
    </p>
    <?php
}

//validate fields in the customer account page
add_action( 'woocommerce_register_post', 'paraguay_custom_validate_register_fields', 10, 3 );
function paraguay_custom_validate_register_fields( $username, $email, $validation_errors ) {
    if ( isset( $_POST['billing_dni'] ) && empty( $_POST['billing_dni'] ) ) {
        $validation_errors->add( 'billing_dni_error', __( 'DNI es requerido!', 'woocommerce' ) );
    }
    return $validation_errors;
}

//add fields to the customer account page
add_action( 'woocommerce_checkout_fields', 'paraguay_custom_checkout_fields' );
function paraguay_custom_checkout_fields( $fields ) {
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
    $fields['billing']['billing_telefono'] = array(
        'label' => __('Teléfono', 'woocommerce'),
        'placeholder' => _x('Teléfono', 'placeholder', 'woocommerce'),
        'required' => false,
        'clear' => true,
        'type' => 'text'
    );
    return $fields;
}

//validate fields in the checkout page
add_action( 'woocommerce_checkout_process', 'paraguay_custom_validate_checkout_fields' );
function paraguay_custom_validate_checkout_fields() {
    if ( isset( $_POST['billing_dni'] ) && empty( $_POST['billing_dni'] ) ) {
        wc_add_notice( __( 'DNI es requerido!', 'woocommerce' ), 'error' );
    }
}
