<?php

$this->form_fields = array(
    'enabled'        => array(
        'title'   => __( 'Active', 'openpay' ),
        'type'    => 'select',
        'label'   => __( 'Active', 'openpay' ),
        'options' => array(
            'no'  => __( "No" ),
            'yes'  => __( "Yes" ),

        ),
        'default'     => __( 'options', 'No' ),
    ),
    'title'          => array(
        'title'       => __( 'Title', 'openpay' ),
        'type'        => 'text',
        'default'     => __( 'Openpay', 'openpay' ),
        'description' => __('This controls the title which the user sees during checkout.'),
        'desc_tip'    => true,
    ),
    'description'    => array(
        'title'       => __( 'Description', 'openpay' ),
        'description' => __('This controls the text which the user sees during checkout.'),
        'type'        => 'text',
        'default'     => __( ' - Buy now. Pay smarter.', 'openpay' ),
        'desc_tip'    => true,
    ),
    'payment_mode'        => array(
        'title'   => __( 'Mode', 'openpay' ),
        'type'    => 'select',
        'label'   => __( 'Mode', 'openpay' ),
        'options' => array(
            'test'  => __( "Sandbox" ),
            'live'  => __( "Production" ),
        ),
        'description' => __( 'Use Sandbox mode to test Openpay features before going Live.'),
        'desc_tip'    => true,
    ),
    'region'        => array(
        'title'   => __( 'Region', 'openpay' ),
        'type'    => 'select',
        'label'   => __( 'Region', 'openpay' ),
        'options' => array(
            'Au'  => __( 'Australia', 'openpay' ),
            'En'  => __( 'United Kingdom', 'openpay' ),
        ),
    ),
    'auth_user' => array(
        'title' => __( 'Openpay Username', 'openpay' ),
        'type'  => 'text'
    ),
    'auth_token'          => array(
        'title'       => __( 'Openpay Password', 'openpay' ),
        'type'        => 'text'
    ),
    'minimum'          => array(
        'title'       => __( 'Minimum Checkout Value', 'openpay' ),
        'type'        => 'text',
        'class'       => 'disabled',
        'custom_attributes' => array( 'readonly' => true )
    ),
    'maximum'          => array(
        'title'       => __( 'Maximum Checkout Value', 'openpay' ),
        'type'        => 'text',
        'class'       => 'disabled',
        'custom_attributes' => array( 'readonly' => true )
    ),
    'openpay_button'  => array(
        'type'              => 'input',
        'class'             => 'openpayajax button button-primary',
        'css'               => 'line-height: 16px;width: 150px;font-size: 15px;top: -10px;position: relative;padding:6px 12px;',
        'default'           => __( 'Run Min/Max!', 'openpay' )
    ),
    'job_frequency'          => array(
        'title'       => __( 'Update Pending Order older than', 'openpay' ),
        'type'        => 'text',
        'default'     => 30,
        'class'       => 'disabled',
        'custom_attributes' => array( 'readonly' => true )
    ),
    array(
        'title'    => __( 'Disabled Categories', 'openpay' ),
        'default'  => '',
        'type'     => 'multiselect',
        'class'    => 'chosen_select',
        'options'  => $this->getcategories(),
    ),
    'disable_products'          => array(
        'title'       => __( 'Disabled Products', 'openpay' ),
        'type'        => 'text',
        'description' => __( 'Add comma separated Product IDs.', 'openpay' ),
        'desc_tip'    => true
    )
);