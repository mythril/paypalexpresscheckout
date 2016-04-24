<?php

namespace Mythril\PayPal\ExpressCheckout;

class PaymentResult {
	protected $data;
	public function __construct(array $data) {
		$this->data = $data;
	}
}
