<?php
/**
 * Plugin Name: Openpay Payment Gateway
 * Description: Openpay is an alternative interest-free payment method available for customers at checkout.
 * Version: 1.1.0
 * Author: Openpay
 * Author URI: https://opy.com
 * @class       WC_Gateway_Openpay
 * @extends     WC_Payment_Gateway
 * 
 * Copyright: (c) 2022 Openpay
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define( 'WCOPENPAY_ABSPATH', __DIR__ . '/' );
require_once WCOPENPAY_ABSPATH . 'lib/OpenpayApi/vendor/autoload.php';

if ( !class_exists( 'Openpay_Payment' ) ) {
    class Openpay_Payment {

        /** @var Openpay_Payment */
        protected static $instance;

        /** @var int */
        protected static $version = '1.0.0';

        public function __construct()
	{
            $gateway = WC_Gateway_Openpay::getInstance();
            add_action( "woocommerce_update_options_payment_gateways_{$gateway->id}", array( $gateway, 'process_admin_options' ), 10, 0 );
            add_filter( 'woocommerce_payment_gateways', array( $gateway, 'add_openpay_gateway' ), 10, 1 );
            add_action( 'wp_ajax_reside_action', array( $gateway, 'reside_action' ), 10, 1 );
            add_action( 'wp_ajax_nopriv_reside_action', array( $gateway, 'reside_action' ), 10, 1 );
            add_filter( 'woocommerce_create_order', array( $gateway, 'override_order_creation' ), 10, 2 );
            add_action( 'wp_ajax_openpay_minmax', array( $gateway, 'min_max_price' ), 10, 2 );
            add_action( 'wp_ajax_nopriv_openpay_minmax', array( $gateway, 'min_max_price' ), 10, 2 );
            add_action( 'admin_enqueue_scripts', function() {
                wp_enqueue_script( 'ajax_script', plugin_dir_url( __FILE__ ) . 'js/openpay.js' ); 
                wp_localize_script( 
                    'ajax_script', 
                    'minMaxAjax', 
                    array(
                        'url'   => admin_url( 'admin-ajax.php' ).'?action=min_max_price',
                    )
                ); 
            });
            add_action( 'openpay_do_cron_jobs', array( 'Openpay_Plugin_Cron', 'fire_jobs' ), 10, 0 );

            //This is for checking cron job on saving openpay settings
            //add_action( "woocommerce_update_options_payment_gateways_{$gateway->id}", array( 'Openpay_Plugin_Cron', 'fire_jobs' ), 12, 0 );
            
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'filter_action_links' ), 10, 1 );
            add_filter( 'cron_schedules', array( 'Openpay_Plugin_Cron', 'edit_cron_schedules'), 10, 1 );
            
            // adding openpay icon
            add_filter( 'woocommerce_gateway_icon',array( $gateway,'openpay_gateway_icon' ), 10, 2);
            add_action( 'admin_head', array( $gateway, 'hide_wc_refund_button' ), 10, 2 );
        }

        public static function load_classes()
        {
            require_once dirname( __FILE__ ) . '/class/Cron/Openpay_Plugin_Cron.php';
            if ( class_exists( 'WC_Payment_Gateway' ) ) {
                require_once dirname( __FILE__ ) . '/class/WC_Gateway_Openpay.php';
            }
        }

        public static function activate_plugin()
        {
            self::init();
            
            // delete the old plugin entry
            delete_option('woocommerce_openpay_settings');
            
            Openpay_Plugin_Cron::create_jobs();
            include( 'installer.php' );
        }

        /**
         * Initialize the classes and return instance of WC_Gateway_Openpay
         * 
         */
        public static function init()
        {              
            self::load_classes();
            if ( !class_exists( 'WC_Gateway_Openpay' ) ) {
                return false;
            }
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public static function deactivate_plugin()
        {
            WC_Gateway_Openpay::getInstance()->removeConfiguration();
            Openpay_Plugin_Cron::delete_jobs();
        }
        
        public function filter_action_links( $links )
	{
            $additional_links = array(
                '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=openpay' ) . '">' . __( 'Settings', 'woo_openpay' ) . '</a>',
            );
            return array_merge( $additional_links, $links );
	}
    }

    register_activation_hook( __FILE__, array( 'Openpay_Payment',  'activate_plugin' ) );
    register_deactivation_hook( __FILE__, array( 'Openpay_Payment', 'deactivate_plugin' ) );
    add_action( 'plugins_loaded', array( 'Openpay_Payment', 'init' ), 10, 0 );  
}