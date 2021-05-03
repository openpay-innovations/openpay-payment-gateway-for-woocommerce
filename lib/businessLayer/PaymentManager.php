<?php

/**
 * SDK for Paymentsystems
 *
 * @author ideatarmac.com
 */

namespace BusinessLayer\Openpay;

use BusinessLayer\Openpay\Utilities\INIController;
use BusinessLayer\Openpay\Features\Tokenization;
use BusinessLayer\Openpay\Features\PaymentPage\PaymentPage;
use BusinessLayer\Openpay\Utilities\Constants;
use BusinessLayer\Openpay\Features\CreateRefund;
use BusinessLayer\Openpay\Features\Capture;
use BusinessLayer\Openpay\Features\Session;
use BusinessLayer\Openpay\Features\GetOrder;
use BusinessLayer\Openpay\Features\Configuration;
use BusinessLayer\Openpay\Features\Dispatch;
use BusinessLayer\Openpay\Utilities\Shopdata;
use Openpay\Client\ObjectSerializer;
use Exception;

class PaymentManager
{

    /** @var array */
    private $features;
    /** @var array */
    private $urlattributes = [];
    /** @var array */
    private $shop;
    /** @var array */
    private $authParams;
    /** @var Shopdata */
    private $shopData;
    /** @var string */
    private $apiHost = null;
    
    /**
     * Generate a Instance of this Class and set values from backoffice or
     * if it is null then with the values from config.ini file
     * Form the array like this:
     * $features = array('tokenisation', 'refund', 'capture', 'widget', 'redirect');
     * $shop = array(
     *   'name' => 'Prestashop',
     *   'version' => '1.7'
     * );
     * Authorization type and useKeysFromConfig is always defined in config file.
     * Type can be
     * Basic (key will generated from the attributes user and password)
     * or
     * Bearer (key will generated from attribute password)
     * or
     * Signature (key will generated from prefix(optional), password(optional))
     *
     * @param array|null $backofficeparams Optional parameter. Values from Backoffice
     * @param array|null $features         Optional parameter. List of all active features
     * @param array|null $shop             Optional parameter. See previous comment
     */
    public function __construct($backofficeparams = null, $features = null, $shop = null)
    {
        $this->authParams = $this->loadAuthentificationFromConfig($backofficeparams);
        $this->apiHost = $this->loadHostFromConfig($backofficeparams);
        $this->features = empty($features) ? $this->loadFeaturesFromConfig() : $features;
        $this->shop = empty($shop) ? $this->loadShopsystemFromConfig() : $shop;
    }
    
    /**
     * get tokenresponse with cart objects or cart array from shop informations
     * config params can set here or will load from config file if it is null
     * Form the array like this:
     * $backofficeConfigparams = array(
     *   'apiClass' => '\Openpay\Client\Api\DefaultApi',
     *   'requestModel' => '\Openpay\Client\Model\CreateOrder',
     *   'method' => 'v1OrdersPost'
     * );
     *
     * @param array|null $backofficeConfigparams Optional parameter. See previous comment
     *
     * @return \stdClass
     */
    public function getToken($backofficeConfigparams = null)
    {
        if (in_array('tokenisation', $this->features)) {
            $tokenization = new Tokenization($this, $backofficeConfigparams);
            try {
                $tokenResponseObj = $tokenization->sendRequest();
                
                return ObjectSerializer::sanitizeForSerialization($tokenResponseObj);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Exception: The non active feature tokenisation was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * get Response from Configration request
     * config params can set here or will load from config file if it is null
     * Form the array like this:
     * $backofficeConfigparams = array(
     *   'apiClass' => '\Openpay\Client\Api\DefaultApi',
     *   'requestModel' => '\Openpay\Client\Model\CreateOrder',
     *   'method' => 'v1OrdersPost'
     * );
     *
     * @param array|null $backofficeConfigparams Optional parameter. See previous comment
     *
     * @return \stdClass
     */
    public function getConfiguration($backofficeConfigparams = null)
    {
        if (in_array('configuration', $this->features)) {
            $urlids =  $this->getUrlAttributes();
            $config = new Configuration($this, $backofficeConfigparams);
            
            try {
                $configResponseObj = $config->sendRequest($urlids);
                
                return ObjectSerializer::sanitizeForSerialization($configResponseObj);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Exception: The non active feature configuration was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * get Response from GetOrder
     * config params can set here or will load from config file if it is null
     * Form the array like this:
     * $backofficeConfigparams = array(
     *   'apiClass' => '\Openpay\Client\Api\DefaultApi',
     *   'requestModel' => '\Openpay\Client\Model\CreateOrder',
     *   'method' => 'v1OrdersPost'
     * );
     *
     * @param array|null $backofficeConfigparams Optional parameter. See previous comment
     *
     * @return \stdClass
     */
    public function getOrder($backofficeConfigparams = null)
    {
        if (in_array('getorder', $this->features)) {
            $urlids =  $this->getUrlAttributes();
            $order = new GetOrder($this, $backofficeConfigparams);
            
            try {
                $orderResponseObj = $order->sendRequest($urlids);
                
                return ObjectSerializer::sanitizeForSerialization($orderResponseObj);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Exception: The non active feature getorder was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * get captureresponse
     * config params can set here or will load from config file if it is null
     * Form the array like this:
     * $backofficeConfigparams = array(
     *   'apiClass' => '\Openpay\Client\Api\DefaultApi',
     *   'requestModel' => '\Openpay\Client\Model\CreateOrder',
     *   'method' => 'v1OrdersPost'
     * );
     *
     * @param array|null $backofficeConfigparams Optional parameter. See previous comment
     *
     * @return \stdClass
     */
    public function getCapture($backofficeConfigparams = null)
    {
        if (in_array('capture', $this->features)) {
            $urlids =  $this->getUrlAttributes();
            $capture = new Capture($this, $backofficeConfigparams);
            
            try {
                $captureResponseObj = $capture->sendRequest($urlids);
                
                return ObjectSerializer::sanitizeForSerialization($captureResponseObj);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Exception: The non active feature capture was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * get dispatchresponse
     * config params can set here or will load from config file if it is null
     * Form the array like this:
     * $backofficeConfigparams = array(
     *   'apiClass' => '\Openpay\Client\Api\DefaultApi',
     *   'requestModel' => '\Openpay\Client\Model\CreateOrder',
     *   'method' => 'v1OrdersPost'
     * );
     *
     * @param array|null $backofficeConfigparams Optional parameter. See previous comment
     *
     * @return \stdClass
     */
    public function dispatch($backofficeConfigparams = null)
    {
        if (in_array('dispatch', $this->features)) {
            $urlids =  $this->getUrlAttributes();
            $dispatch = new Dispatch($this, $backofficeConfigparams);
            
            try {
                $dispatchResponseObj = $dispatch->sendRequest($urlids);
                
                return ObjectSerializer::sanitizeForSerialization($dispatchResponseObj);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Exception: The non active feature dispatch was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * Return a PaymentPage object with information of given payment method
     *
     * @param string $paymentmethod      Required. Type of Form which customer is using. We differentiate
     *                                   between 'redirect' and 'widget'. Redirect means all types where
     *                                   customer leaves the Shopsite, 'widget' means all types where
     *                                   custommer get Form without leaving shopsystem
     * @param boolean $jsIsUsed          Required. How is it implemented from paymentprovider?
     *                                   Do we get thirdparty js then jsUsed is true or do we send Get/Post
     *                                   params to paymentprovider then jsUsed is false
     * @param string|null $requestmethod Optional parameter. Required for paymentmethod 'redirect'.
     *                                   requestmethods can be 'GET' or 'POST'
     * 
     * return BusinessLayer\Openpay\Features\PaymentPage\PaymentPage Return object of all information which is needed to proceed
     *                                                       with given payment method.
     *                                                       e.g. Widget, Redirect, Hosted Fields, 3D, ...
     */
    public function getPaymentPage($paymentmethod, $jsIsUsed, $requestmethod = null)
    {
        $this->setRedirectUrlInShopdata();
        if (in_array($paymentmethod, $this->features)) {
            $paymentpage = new PaymentPage();
            $paymentpage->createPaymentPage(
                $paymentmethod,
                $jsIsUsed,
                $this->shop,
                $requestmethod,
                $this->shopData
            );
            
            return $paymentpage;
        } else {
            throw new Exception('Exception: The non active feature' . $paymentmethod . 'was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * get refundresponse
     * config params can set here or will load from config file if it is null
     * Form the array like this:
     * $backofficeConfigparams = array(
     *   'apiClass' => '\Openpay\Client\Api\DefaultApi',
     *   'requestModel' => '\Openpay\Client\Model\CreateOrder',
     *   'method' => 'v1OrdersPost'
     * );
     *
     * @param array|null $backofficeConfigparams Optional parameter. See previous comment
     *
     * @return \stdClass
     */
    public function refund($backofficeConfigparams = null)
    {
        if (in_array('refund', $this->features)) {
            $urlids =  $this->getUrlAttributes();
            $createrefund = new CreateRefund($this, $backofficeConfigparams);
            try {
                $response = $createrefund->sendRequest($urlids);

                return ObjectSerializer::sanitizeForSerialization($response);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
                     
        } else {
            throw new Exception('The non active feature refund was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * get Sessionresponse
     * config params can set here or will load from config file if it is null
     * Form the array like this:
     * $backofficeConfigparams = array(
     *   'apiClass' => '\Openpay\Client\Api\DefaultApi',
     *   'requestModel' => '\Openpay\Client\Model\CreateOrder',
     *   'method' => 'v1OrdersPost'
     * );
     *
     * @param array|null $backofficeConfigparams See previous comment
     *
     * @return \stdClass
     */
    public function getSession($backofficeConfigparams = null)
    {
        if (in_array('session', $this->features)) {
            $urlids =  $this->getUrlAttributes();
            $session = new Session($this, $backofficeConfigparams);
            
            try {
                $sessionResponseObj = $session->sendRequest($urlids);
                
                return ObjectSerializer::sanitizeForSerialization($sessionResponseObj);
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Exception: The non active feature session was called. Please activate it in the config.ini file');
        }
    }
    
    /**
     * load all active features from the general config file
     *
     * return array
     */
    private function loadFeaturesFromConfig()
    {
        $activefeatures = INIController::fetchKey(Constants::CONFIG_FILE, 'UsedFeatures', 'Features');
        
        return ($activefeatures) ? $activefeatures : [];
    }
    
    /**
     * load Authentification from the general config file or from backoffice parameter
     * 
     * @param arary|null $backofficeparams parameters from Shopsystem backoffice
     *
     * return array
     */
    private function loadAuthentificationFromConfig($backofficeparams)
    {
        $authConfigparams = INIController::fetchSection(Constants::CONFIG_FILE, 'Authentification');
        if ($backofficeparams !== null &&
            isset($authConfigparams['useKeysFromConfig']) && 
            $authConfigparams['useKeysFromConfig'] !== '1'
        ) {
            $allAuthparams = [];
            $allAuthparams['type'] = $authConfigparams['type'];
            unset($authConfigparams['useKeysFromConfig']);
            unset($authConfigparams['type']);
            foreach ($authConfigparams as $key => $value) {
                if ($backofficeparams[$value]) {
                    $allAuthparams[$key] = $backofficeparams[$value];
                }
            }
            
            return $allAuthparams;
        }
        
        return $authConfigparams;
    }
    
    /**
     * load Host static from the general config file or from backoffice parameter
     * 
     * @param arary|null $backofficeparams parameters from Shopsystem backoffice
     *
     * return array
     */
    private function loadHostFromConfig($backofficeparams)
    {
        $region = $backofficeparams['region'];
        if ($backofficeparams['payment_mode'] == 'live') {
            $host = INIController::fetchKey(Constants::CONFIG_FILE, 'Host', 'liveurl' . $region);
        } else {
            $host = INIController::fetchKey(Constants::CONFIG_FILE, 'Host', 'testurl' . $region);
        }
        
        return ($host) ? $host : null;
        
        //return ($backofficeparams !== null && $host) ? $backofficeparams[$host] : null;
    }
    
    /**
     * load shopsystemname and shopsystemversion from the general config file
     *
     * retrun array
     */
    private function loadShopsystemFromConfig()
    {
        $shop = INIController::fetchSection(Constants::CONFIG_FILE, 'Shop');
        
        return $shop;
    }
    
    /**
     * set all Shopdata which are used for a Request
     * 
     * @param object|array|null $cartData     Optional parameter. Is the Object or assoziative Array 
     *                                        from shop with all cart and checkout informations
     * @param \stdClass|array|null $otherData Optional parameter. This object or assoziative have 
     *                                        informations which is needed in the request but not 
     *                                        in the $cart from shopsystem e.g. cancle url 
     * @param object|null $prevResponseData   Optional parameter. Paramters from previous response.
     *                                        E.g. Response of Tokenization
     * @param array|null $form                Optional parameter. If we use additional form to select 
     *                                        special data for Customer (e.g. CreditCard data) then 
     *                                        we can pass the POST or GET Array formData
     * @param \stdClass|array|null $backofficeConfigparam Optional parameter. Can be different values like id,
     *                                                    endpointurl etc, which will needed for redirect or widget
     */
    public function setShopdata($cartData = null, $otherData = null, $prevResponseData = null, $form = null, $backofficeConfigparam = null)
    {
        $this->shopData = new Shopdata($this->shop, $cartData, $otherData, $prevResponseData, $form, $backofficeConfigparam);
    }
    
    /**
     * get shopdata which are used for a Request
     *
     * @return Shopdata 
     */
    function getShopdata() 
    {
        return $this->shopData;
    }
    
    /**
     * set all url attributes
     *
     * @param array $attr
     */
    public function setUrlAttributes(array $attr)
    {
        $this->urlattributes = $attr;
    }
    
    /**
     * get all url attributes
     *
     * @return array
     */
    public function getUrlAttributes()
    {
        return $this->urlattributes;
    }
    
    /**
     * get all attributes for Authentification
     *
     * @return array
     */
    function getAuthParams() 
    {
        return $this->authParams;
    }

    /**
     * set all attributes for Authentification
     *
     * @param array
     */
    function setAuthParams($authParams) 
    {
        $this->authParams = $authParams;
    }
    
    /**
     * get ApiHost
     *
     * @return string
     */
    function getApiHost() 
    {
        return $this->apiHost;
    }

    /**
     * set ApiHost
     *
     * @param string
     */
    function setApiHost($apiHost) 
    {
        $this->apiHost = $apiHost;
    }
    
    /**
     * read redirect url from Config file and insert it in shopData object.
     * Customized for Openpay
     */
    private function setRedirectUrlInShopdata()
    {
        $region = $this->shopData->backofficeConfigparam['region'];
        if ($this->shopData->backofficeConfigparam['payment_mode'] == 'live') {
            $url = INIController::fetchKey(Constants::CONFIG_FILE, 'HandoverUrl', 'liveurl' . $region);
        } else {
            $url = INIController::fetchKey(Constants::CONFIG_FILE, 'HandoverUrl', 'testurl' . $region);
        }
        
        $this->shopData->backofficeConfigparam['handover_url'] = ($url) ? $url : null;
    }
}
