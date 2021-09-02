<?php
/**
* Openpay Plugin CRON Handler Class
*/

require_once WCOPENPAY_ABSPATH.'class/WC_Gateway_Openpay.php';

class Openpay_Plugin_Cron
{
    public static function edit_cron_schedules( $schedules )
    {
        $schedules['15min'] = array(
            'interval' => 15 * 60, 
            'display' => __( 'Every 15 minutes', 'wc-gateway-openpay' )
        );
        return $schedules;
    }
	
    public static function create_jobs() 
    {
        $timestamp = wp_next_scheduled( 'openpay_do_cron_jobs' );
        if ( $timestamp == false ) {
            wp_schedule_event( time(), '15min', 'openpay_do_cron_jobs' );
        }
    }

    public static function delete_jobs()
    {
        wp_clear_scheduled_hook( 'openpay_do_cron_jobs' );
    }

    public static function fire_jobs()
    {
        $log = new WC_Logger();
        if ( defined( 'DOING_CRON' ) && DOING_CRON === true ) {
            $fired_by = 'schedule';
        } elseif ( is_admin() ) {
            $fired_by = 'admin';
        } else {
            $fired_by = 'unknown';
        }
        $log->add( 'openpay','Firing cron by '.$fired_by.' ...' );
        self::update_payment_limits();
        self::check_pending_abandoned_orders();
    }

    private static function update_payment_limits()
    {
        $gateway = WC_Gateway_Openpay::getInstance();
        $backofficeParams = $gateway->min_max_price();
    }

    private static function check_pending_abandoned_orders()
    {
        global $wpdb;
        $log = new WC_Logger();
        $table_name = $wpdb->prefix . "openpay"; 
        $gateway = WC_Gateway_Openpay::getInstance();
        $frequency = $gateway->get_option( 'job_frequency' );
        $backofficeparams = $gateway->getBackendParams();
        $args = array(
            'status' => array( 'wc-pending' ),
            'payment_method' => 'wc-gateway-openpay',
            'date_created' => '<' . strtotime( '-' . $frequency . ' minutes' )
        );
        $unpaid_orders = wc_get_orders( $args );
        $paymentmanager = new BusinessLayer\Openpay\PaymentManager( $backofficeparams );
        $cancelled_text = __( "Order cancelled from Openpay.", "wc-gateway-openpay" );
        foreach ( $unpaid_orders as $order ) {
            $row = $wpdb->get_results( "SELECT plan_id FROM " . $table_name . " WHERE order_id=" . $order->id );
            if ( !empty( $row ) ) {
                try {
                    /** @var $paymentmanager \BusinessLayer\Openpay\PaymentManager */
                    $paymentmanager = new \BusinessLayer\Openpay\PaymentManager( $backofficeparams );
                    $paymentmanager->setUrlAttributes( [$row[0]->plan_id] );
                    $response = $paymentmanager->getOrder();
                } catch ( \Exception $e ) { 
                    $message = $e->getMessage();
                    if ( strpos( $message, 'Error 12704' ) !== false ) {
                        $order->update_status( 'cancelled', __( 'Cancelled Openpay payment', 'wc-gateway-openpay' ) );
                        $log->add( 'openpay', 'Order cancelled for plan Id => '.$row[0]->plan_id );
                    } else {
                       $log->add( 'openpay', 'UpdateStatusJob:' . $e->getMessage() );
                    }
                }
                if ( $response->orderStatus == 'Approved' && $response->planStatus == 'Active' ) {
                    $order->update_status( 'processing', __( 'Processing Openpay payment', 'wc-gateway-openpay' ) );
                } else {
                    $order->update_status( 'cancelled', __( 'Cancelled Openpay payment', 'wc-gateway-openpay' ) );
                    $log->add( 'openpay', 'Order cancelled for plan Id => '.$row[0]->plan_id );
                }
            }
        }
    }
}
