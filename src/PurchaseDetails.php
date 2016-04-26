<?php

namespace Mythril\PayPal\ExpressCheckout;

interface PurchaseDetails {
	public function getTotalPrice();
	public function getCurrency();
	public function getReturnUrl();
	public function getCancelUrl();
	public function getNotifyUrl();
	public function getExtras();
}