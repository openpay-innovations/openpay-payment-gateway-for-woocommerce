<?php
/**
 * IntegrationApiModelsCommandsOrderOrigin
 *
 * PHP version 5
 *
 * @category Class
 * @package  Openpay\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Integration API
 *
 * No description provided (generated by Swagger Codegen https://github.com/swagger-api/swagger-codegen)
 *
 * OpenAPI spec version: v1
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 3.0.22
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Openpay\Client\Model;
use \Openpay\Client\ObjectSerializer;

/**
 * IntegrationApiModelsCommandsOrderOrigin Class Doc Comment
 *
 * @category Class
 * @description The type of customer journey being started
 * @package  Openpay\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class IntegrationApiModelsCommandsOrderOrigin
{
    /**
     * Possible values of this enum
     */
    const ONLINE = 'Online';
const POS_APP = 'PosApp';
const POS_WEB = 'PosWeb';
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::ONLINE,
self::POS_APP,
self::POS_WEB,        ];
    }
}
