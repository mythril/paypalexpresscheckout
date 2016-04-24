<?php

namespace Mythril\PayPal\ExpressCheckout;

class Gateway {
	const API_VERSION = '98.0';
	protected $cfg;
	public function __construct(Configuration $cfg) {
		$this->cfg = $cfg;
	}

	public function initiatePurchase(PurchaseDetails $p) {
		$req = new Request($this->cfg);
		return new IncompleteTransaction($req->send('SetExpressCheckout', array(
			'PAYMENTREQUEST_0_AMT' => $p->getTotalPrice(),
			'PAYMENTREQUEST_0_ITEMAMT' => '0.00',
			'PAYMENTREQUEST_0_SHIPPINGAMT' => '0.00',
			'PAYMENTREQUEST_0_CURRENCYCODE' => $p->getCurrency(),
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
			'PAYMENTREQUEST_0_NOTIFYURL' => $p->getNotifyUrl(),
			'RETURNURL' => $p->getReturnUrl(),
			'CANCELURL' => $p->getCancelUrl(),
			'useraction' => 'commit',
			'NOSHIPPING' => 1,
			'ADDROVERRIDE' => 0,
		)));
	}
}
