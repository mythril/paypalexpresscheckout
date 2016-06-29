<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mythril\PayPal\ExpressCheckout\Configuration;
use Mythril\PayPal\ExpressCheckout\Gateway;
use Mythril\PayPal\ExpressCheckout\BasicPurchaseDetails;

// this is not a unit test, it is meant to be ran manually
// with real paypal sandbox credentials

$configuration = Configuration::fromFile(__DIR__ . '/../phantom.cfg.php');
$gateway = new Gateway($configuration);
$result = $gateway->verifyCredentials();

var_dump($result);
