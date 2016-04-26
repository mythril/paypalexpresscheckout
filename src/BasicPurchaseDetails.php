<?php

namespace Mythril\PayPal\ExpressCheckout;

class BasicPurchaseDetails implements PurchaseDetails {
	protected $total;
	protected $currency;
	protected $cancelUrl;
	protected $returnUrl;
	protected $notifyUrl;

	public function __construct(
		$total,
		$currency,
		$cancelUrl,
		$returnUrl,
		$notifyUrl
	) {
		$this->total = $total;
		$this->currency = $currency;
		$this->cancelUrl = $cancelUrl;
		$this->returnUrl = $returnUrl;
		$this->notifyUrl = $notifyUrl;
	}

	public function getTotalPrice() {
		return $this->total;
	}

	public function getCurrency() {
		return $this->currency;
	}

	public function getCancelUrl() {
		return $this->cancelUrl;
	}

	public function getReturnUrl() {
		return $this->returnUrl;
	}

	public function getNotifyUrl() {
		return $this->notifyUrl;
	}

	public function getExtras() {
		return array();
	}
}