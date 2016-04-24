<?php

namespace Mythril\PayPal\ExpressCheckout;

class PaymentResult {
	protected $data;
	protected $transactionId;
	protected $paymentStatus;
	protected $pendingReason;
	public function __construct(array $data) {
		$this->data = $data;
		$this->transactionId = $data['PAYMENTINFO_0_TRANSACTIONID'];
		$this->paymentStatus = $data['PAYMENTINFO_0_PAYMENTSTATUS'];
		$this->pendingReason = $data['PAYMENTINFO_0_PENDINGREASON'];
	}

	public function __get($key) {
		return $this->data[$key];
	}

	public function getData() {
		return $this->data;
	}

	public function getTransactionId() {
		return $this->transactionId;
	}

	public function getPaymentStatus() {
		return $this->paymentStatus;
	}

	public function getPendingReason() {
		return $this->pendingReason;
	}
}
