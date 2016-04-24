<?php

// this is not a unit test, it is meant to be ran manually
// with real paypal sandbox credentials

$configuration = new Configuration(__DIR__ . '/../phantom.cfg.php');
$gateway = new Gateway($configuration);
$purchase = new BasicPurchaseDetails(
	$total,
	$currency,
	$cancelUrl,
	$returnUrl,
	$notifyUrl
);
$incomplete = $gateway->initiatePurchase($purchase);
$url = $incomplete->getRedirectUrl();
$incompleteToken = $incomplete->getToken();

redirect($url);

$completed = $gateway->completePurchase($incompleteToken);
