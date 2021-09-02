<?php

$this->form_fields = array(
    'enabled'        => array(
        'title'   => __( 'Active', 'wc-gateway-openpay' ),
        'type'    => 'select',
        'label'   => __( 'Active', 'wc-gateway-openpay' ),
        'options' => array(
            'no'  => __( "No" ),
            'yes'  => __( "Yes" ),

        ),
        'default'     => __( 'options', 'No' ),
    ),
    'title'          => array(
        'title'       => __( 'Title', 'wc-gateway-openpay' ),
        'type'        => 'text',
        'default'     => __( 'Openpay - Buy now. Pay smarter', 'wc-gateway-openpay' ),
    ),
    'description'    => array(
        'title'       => __( 'Description', 'wc-gateway-openpay' ),
        'type'        => 'textarea',
        'default'     => __( 'Secure Payment Method', 'wc-gateway-openpay' ),
    ),
    'payment_mode'        => array(
        'title'   => __( 'Mode', 'wc-gateway-openpay' ),
        'type'    => 'select',
        'label'   => __( 'Mode', 'wc-gateway-openpay' ),
        'options' => array(
            'test'  => __( "Sandbox" ),
            'live'  => __( "Production" ),
        ),
    ),
    'region'        => array(
        'title'   => __( 'Region', 'wc-gateway-openpay' ),
        'type'    => 'select',
        'label'   => __( 'Region', 'wc-gateway-openpay' ),
        'options' => array(
            'Au'  => __( 'Australia', 'wc-gateway-openpay' ),
            'En'  => __( 'United Kingdom', 'wc-gateway-openpay' ),
        ),
    ),
    'auth_user' => array(
        'title' => __( 'Openpay Username', 'wc-gateway-openpay' ),
        'type'  => 'text'
    ),
    'auth_token'          => array(
        'title'       => __( 'Openpay Password', 'wc-gateway-openpay' ),
        'type'        => 'text'
    ),
    'minimum'          => array(
        'title'       => __( 'Minimum Checkout Value', 'wc-gateway-openpay' ),
        'type'        => 'text',
        'class'       => 'disabled',
        'custom_attributes' => array( 'readonly' => true )
    ),
    'maximum'          => array(
        'title'       => __( 'Maximum Checkout Value', 'wc-gateway-openpay' ),
        'type'        => 'text',
        'class'       => 'disabled',
        'custom_attributes' => array( 'readonly' => true )
    ),
    'openpay_button'  => array(
        'type'              => 'input',
        'class'             => 'openpayajax button button-primary',
        'css'               => 'line-height: 16px;width: 150px;font-size: 15px;top: -10px;position: relative;padding:6px 12px;',
        'default'           => __( 'Run Min/Max!', 'wc-gateway-openpay' )
    ),
    'job_frequency'          => array(
        'title'       => __( 'Update Pending Order older than', 'wc-gateway-openpay' ),
        'type'        => 'text',
        'default'     => 30,
        'class'       => 'disabled',
        'custom_attributes' => array( 'readonly' => true )
    ),
    array(
        'title'    => __( 'Disabled Categories', 'wc-gateway-openpay' ),
        'default'  => '',
        'type'     => 'multiselect',
        'class'    => 'chosen_select',
        'options'  => $this->getcategories(),
    ),
    'disable_products'          => array(
        'title'       => __( 'Disabled Products', 'wc-gateway-openpay' ),
        'type'        => 'text',
        'description' => __( 'Add Product Ids with comma seperated.', 'wc-gateway-openpay' ),
        'desc_tip'    => true
    )
);