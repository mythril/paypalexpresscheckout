<?php

namespace Mythril\PayPal\ExpressCheckout;

class Gateway {
	const API_VERSION = '98.0';
	const SANDBOX_REDIRECT = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
	const LIVE_REDIRECT = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
	protected $cfg;
	protected $extras = array();
	public function __construct(Configuration $cfg) {
		$this->cfg = $cfg;
	}

	public function setExtras(array $extras) {
		$this->extras = $extras;
	}

	public function getRedirectUrl(IncompleteTransaction $tx) {
		if ($this->cfg->useSandbox()) {
			return self::SANDBOX_REDIRECT . $tx->getToken();
		}
		return self::LIVE_REDIRECT . $tx->getToken();
	}

	protected static function fmt($money) {
		return number_format($money, 2, '.', '');
	}

	public function initiatePurchase(PurchaseDetails $p) {
		$req = new Request($this->cfg);
		$details = array(
			'PAYMENTREQUEST_0_AMT' => self::fmt($p->getTotalPrice()),
			'PAYMENTREQUEST_0_CURRENCYCODE' => $p->getCurrency(),
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
			'RETURNURL' => $p->getReturnUrl(),
			'CANCELURL' => $p->getCancelUrl(),
			'MAXAMT' =>  self::fmt($p->getTotalPrice()),
			'NOSHIPPING' => 1,
			'ADDROVERRIDE' => 0,
		) + $this->extras + $p->getExtras();

		return new IncompleteTransaction($req->send('SetExpressCheckout', $details));
	}

	public function verifyCredentials() {
		$details = array(
			'PAYMENTREQUEST_0_AMT' => '0.01',
			'RETURNURL' => 'http://nope.com/return',
			'CANCELURL' => 'http://nope.com/cancel',
		);
		$req = new Request($this->cfg);
		try {
			$req->send('SetExpressCheckout', $details);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function completePurchase(PurchaseDetails $p, $token, $payerId) {
		$req = new Request($this->cfg);
		return new PaymentResult($req->send('DoExpressCheckoutPayment', array(
			'TOKEN' => $token,
			'PAYERID' => $payerId,
			'MAXAMT' =>  self::fmt($p->getTotalPrice()),
			'PAYMENTREQUEST_0_NOTIFYURL' => $p->getNotifyUrl(),
			'PAYMENTREQUEST_0_AMT' => self::fmt($p->getTotalPrice()),
			'PAYMENTREQUEST_0_CURRENCYCODE' => $p->getCurrency(),
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
		)));
	}
}
