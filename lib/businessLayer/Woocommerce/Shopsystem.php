<?php

/**
 * All Woocommerce function which can be necessary
 *
 * @author ideatarmac.com
 */

namespace BusinessLayer\Openpay\Woocommerce;

class Shopsystem 
{
    /**
     * call functions from woocommerce to get all cartinformations
     * 
     * @param object $cartObj
     * 
     * @return object
     */
    public static function prepareShopCartObj($cartObj)
    {
        $shopCart = new \stdClass();

        foreach($cartObj->get_cart() as $item => $values) { 
            $itemData = [];
            $product =  wc_get_product( $values['data']->get_id() );
            $itemData['name'] = $product->get_title();
            if ( $product->get_sku() ) {
                $itemData['sku'] = $product->get_sku();
            } else {
                $itemData['sku'] = $product->get_id();
            }

            $itemData['price'] = $product->get_price();
            $itemData['qty'] = $values['quantity'];
            $products[] = $itemData; 
        }
        $shopCart->products = $products;

        $customer = $cartObj->get_customer();

        $shopCart->total = $cartObj->total;
        $integerTotal = round((float)$cartObj->total, 2);
        $shopCart->integerTotal = (int)($integerTotal * 100);
        $shopCart->custom = ['email' => $cartObj->get_customer()->get_billing_email()];
        $shopCart->deliveryAddress = array(
            'firstname' => $cartObj->get_customer()->get_shipping_first_name(),
            'lastname' => $cartObj->get_customer()->get_shipping_last_name(),
            'country'   => $cartObj->get_customer()->get_shipping_country(),
            'regioncode'     => $cartObj->get_customer()->get_shipping_state(),
            'postcode'  => $cartObj->get_customer()->get_shipping_postcode(),
            'city'      => $cartObj->get_customer()->get_shipping_city(),
            'address'   => $cartObj->get_customer()->get_shipping_address(),
            'street' => $cartObj->get_customer()->get_shipping_address(),
            'line2' => $cartObj->get_customer()->get_shipping_address_2(),
        );
        $shopCart->invoiceAddress = array(
            'firstname' => $cartObj->get_customer()->get_billing_first_name(),
            'lastname' => $cartObj->get_customer()->get_billing_last_name(),
            'country'   => $cartObj->get_customer()->get_billing_country(),
            'regioncode'     => $cartObj->get_customer()->get_billing_state(),
            'postcode'  => $cartObj->get_customer()->get_billing_postcode(),
            'city'      => $cartObj->get_customer()->get_billing_city(),
            'address'   => $cartObj->get_customer()->get_billing_address(),
            'street' => $cartObj->get_customer()->get_billing_address(),
            'line2' => $cartObj->get_customer()->get_billing_address_2(),
        );
        $shopCart->source = 'Woocommerce';    
        return $shopCart;
    }
}