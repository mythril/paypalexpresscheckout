<?php

namespace Mythril\PayPal\ExpressCheckout;

use Exception;

class Configuration {
	protected $username;
	protected $password;
	protected $signature;
	protected $sandbox;
	
	public static function fromFile($configFile) {
		if (!file_exists($configFile)) {
			throw new Exception("Configuration file not found: '$configFile'");
		}
		return new self(include($configFile));
	}

	public function __construct(array $cfg) {
		$required = array(
			'username' => 'string',
			'password'  => 'string',
			'signature' => 'string',
			'sandbox' => 'bool',
		);
		foreach ($required as $key => $type) {
			if (empty($cfg[$key])) {
				throw new Exception("Required configuration value is missing: '$key'");
			}
			if (gettype($cfg[$key]) !== $type) {
				throw new Exception("Vonfiguration value is wrong type: '$key' was expected to be a '$type'");
			}
		}
	}
	
	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getSignature() {
		return $this->signature;
	}

	public function useSandbox() {
		return $this->sandbox;
	}
}
