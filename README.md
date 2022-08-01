<img src="https://static.openpay.com.au/brand/logo/openpay_logo_transparent.svg" width="170" alt="Openpay Logo">

## Overview

The purpose of this document is to enumerate the technical steps involved in the integration of Openpay plugin into an existing Wordpress and Woocommerce installation.

## Install Wordpress and Woocommerce

If Wordpress or Woocommerce is not already installed, following links provide step-by-step instructions to install [Wordpress](https://wordpress.org/support/article/how-to-install-wordpress/) and [WooCommerce](https://docs.woocommerce.com/documentation/plugins/woocommerce/getting-started/installation-and-updating/) on the website.

## Woocommerce Documentation

This link will help you familiarize yourself with [Woocommerce’s features](https://docs.woocommerce.com/)


Introduction
---------

### What is Openpay Payment Gateway Plugin?

WooCommerce is a free to use plugin for WordPress that converts a basic WordPress site into a Multi-dimensional eCommerce platform with a wide range of features, built on open source technology.

WooCommerce provides online merchants not only with a flexible shopping cart system, but also control over the look‐and‐feel of the content as well as the functionality of their online store.

WooCommerce can be fully integrated with Openpay’s online payment solutions. Installing and configuring the Hosted Payment Solution module of Openpay provide a simple, secure and convenient option to connect with Openpay’s online payment processing platform.

## Integration Components

Openpay integration has following plugins:
 - plugin: openpay-payment-gateway

## Signing up for Openpay merchant account

In order to start taking payments through Openpay, you need to sign up and get approval for an Openpay merchant account. Once your application is approved, you can configure Openpay Hosted Payment Solution module and start taking payments.

Openpay provides a test account and users for training and testing purpose.


## Compatibility

- Wordpress 5.x
- WooCommerce 4.5+

## Requirements

- At least 5.6 or the later version of PHP
- cURL extension for PHP
- JSON extension for PHP
- Multibyte String extension for PHP
- Worpress and WooCommerce must be pre-installed
- SSL: A valid security certificate is required to work over a secure channel (HTTPS) while submitting the form data from the storefront. Self-signed SSL certificates are not supported
- Curl (version 7.20.0 - 7.4.0)

## Already Using the OLD Openpay Payment Plugin?

Before installing this plugin, please remove the OLD payment payment plugin named **Openpay Payment Method** by deleting it from here: `<merchant-site-url>/wp-admin/plugins.php`


## Installation

To install the plugin, download the zip file.

Log in to your WordPress admin dashboard

Navigate from Dashboard to Plugins > Add New > Upload Plugin (select the downloaded file)

Activate the **Openpay Payment Gateway** plugin and you are set to go ahead


## Configuration

Navigate to WooCommerce > Settings > Payments > Openpay > Manage

- **Active:** Yes
- **Title:** Openpay - Buy now, Pay Smarter
- **Description:** Secure Payment Method (this field can be empty)
- **Mode:** Sandbox or Production - Sandbox Mode for test purpose only
- **Region:** Australia or United Kingdom
- **Openpay Username:** Please enter first part of Auth Token provided by Openpay
- **Openpay Password:** Please enter second part of Auth Token provided by Openpay
- **Disabled Categories:** Comma separated category IDs
- **Disabled Products:** Comma separated product IDs

For instance if the Auth token is "3-373|180D731A-F9C8-437B-8FC0-8341196D9CF0" then following is the username and password

`Username:` 3-373

`Password:` 180D731A-F9C8-437B-8FC0-8341196D9CF0

- Click ‘Run Min/Max!’ to get the configured minimum and maximun checkout amounts
(Order amount should be greater than minimum and less than maximum to enable Openpay payment method on the frontend of your site.)
- Click ‘Save changes’ to save the settings.

<img width="960" alt="opy-settings" src="https://user-images.githubusercontent.com/58763572/141085940-73922755-3b2e-42b3-9fd9-ba8de55769a2.png">
 
 
## Refund

Orders can be partiallly or fully refunded from the `Edit order` page


## Update Plugin

- Delete the `Openpay Payment Gateway` plugin from here: `<merchant-site-url>/wp-admin/plugins.php`
- Repeat the steps mentioned above in the Installation section

## Uninstallation

The Openpay plugin can be always uninstalled by clicking `Deactivate` or `Delete` in the admin side

## Order statuses

Openpay uses default statuses from Woocommerce.

#### Pending
Customer completes the Openpay plan and will be redirected to merchant website. WooCommerce order is created in `Pending` status before the plan is captured.

#### Processing
If Plan is successfully captured then WooCommerce order status will be changed to `Processing`. 
Every 30 minutes cron will check the pending orders in the system that are in status `Preparation in progress` status, that will update the WooCommerce order status to `Processing` if the Openpay plan status is active otherwise order will move to cancelled state in WooCommerce.

#### Refunded
All the Orders with status i.e`Processing` can be partially or fully refunded in Openpay. If whole amount is refunded then order status will be changed to `Refunded`.

#### Canceled
Cron job will move the WooCommerce orders to `Canceled` state if the Openpay plan status is not active.


## Scheduled Job Test

Cron can be tested manually by browsing this link - `<wordpress-root-url>/wp-cron.php`. All WooCommerce orders which have been paid using Openpay payment method but are in `Pending` status and are older than 30 mins will be changed to “Processing” or “Canceled” state.


## License

	Copyright 2021 Openpay

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
