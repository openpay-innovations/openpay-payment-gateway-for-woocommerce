<?php

/**
 * Here are all custom function are changeable if necessary
 *
 * @author ideatarmac.com
 */

namespace BusinessLayer\Openpay\CustomLayer;

class Customizations
{
    
    /**
     * return the authorization string which should be in request header
     *
     * @param array $authParams   Is the assoziative array with the informations for Authorization
     *                            Is coming from Shopsystem or config.ini File
     * @param array $requestArray Is the parameters in body which we will send to APi. Sometimes we need
     *                            this values for signature
     *
     * @return string
     */
    public static function customSignatureFunction($authParams, $requestArray)
    {
        //$body = json_encode($this->requestArray);
        //@Todo Insert you custom logic

        //return $signature
    }
    
    /**
     * calculate and insert signature attribute in request array
     * 
     * @param array $requestArray
     * @param \BusinessLayer\Openpay\Utilities\DicUnit[] $dictonary
     * @param string $value  
     * 
     * @return array
     */
    public static function customBodySignatureFunction($requestArray, $dictonary, $value)
    {
        //@Todo Insert you custom logic
        //$requestArray['signature'] = $signatureattribute;
        //return $requestArray;
    }
    
    /**
     * seach Token in an Array
     * 
     * @param \BusinessLayer\Openpay\Utilities\DicUnit[] $dictonary
     */
    public static function getPlanId($dictonary)
    { 
        foreach ($dictonary as $unit) {
            if (strpos($unit->shopkey, 'fieldName') && $unit->value == 'TransactionToken') {
                $element = $unit->iters['iter1'];
            }
            if (strpos($unit->shopkey, 'fieldValue')) {
                $values[] = $unit->value;
            }
        }
        
        return $values[$element];
    }
}
