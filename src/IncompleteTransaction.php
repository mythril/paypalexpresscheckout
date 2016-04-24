<?php

namespace Mythril\PayPal\ExpressCheckout;

class IncompleteTransaction {
	protected $token;
	protected $timestamp;
	protected $correlationId;

	public function __construct(array $data) {
		$required = array('TOKEN', 'TIMESTAMP', 'CORRELATIONID');
		foreach ($required as $req) {
			if (empty($data[$req])) {
				throw new Exception("Missing required value, API update? ($req)");
			}
		}
		$this->token = $data['TOKEN'];
		$this->timestamp = $data['TIMESTAMP'];
		$this->correlationId = $data['CORRELATIONID'];
	}

	public function getToken() {
		return $this->token;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function getCorrelationId() {
		return $this->correlationId;
	}
}
