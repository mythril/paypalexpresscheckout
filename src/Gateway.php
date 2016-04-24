<?php

namespace Mythril\PayPal\ExpressCheckout;

class Gateway {
	const API_VERSION = '98.0';
	const SANDBOX_REDIRECT = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
	const LIVE_REDIRECT = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
	protected $cfg;
	public function __construct(Configuration $cfg) {
		$this->cfg = $cfg;
	}

	public function getRedirectUrl(IncompleteTransaction $tx) {
		if ($this->cfg->useSandbox()) {
			return self::SANDBOX_REDIRECT . $tx->getToken();
		}
		return self::LIVE_REDIRECT . $tx->getToken();
	}

	public function initiatePurchase(PurchaseDetails $p) {
		$req = new Request($this->cfg);
		return new IncompleteTransaction($req->send('SetExpressCheckout', array(
			'PAYMENTREQUEST_0_AMT' => number_format($p->getTotalPrice(), 2, '.', ''),
			'PAYMENTREQUEST_0_CURRENCYCODE' => $p->getCurrency(),
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
			'RETURNURL' => $p->getReturnUrl(),
			'CANCELURL' => $p->getCancelUrl(),
			'useraction' => 'commit',
			'NOSHIPPING' => 1,
			'ADDROVERRIDE' => 0,
		)));
	}

	public function completePurchase(PurchaseDetails $p, $token, $payerId) {
		$req = new Request($this->cfg);
		return new PaymentResult($req->send('DoExpressCheckoutPayment', array(
			'TOKEN' => $token,
			'PAYERID' => $payerId,
			'PAYMENTREQUEST_0_NOTIFYURL' => $p->getNotifyUrl(),
			'PAYMENTREQUEST_0_AMT' => number_format($p->getTotalPrice(), 2, '.', ''),
			'PAYMENTREQUEST_0_CURRENCYCODE' => $p->getCurrency(),
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
		)));
	}
}
