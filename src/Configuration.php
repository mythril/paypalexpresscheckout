<?php

namespace Mythril\PayPal\ExpressCheckout;

class Configuration {
	protected $username;
	protected $password;
	protected $signature;
	protected $sandbox;
	protected $logger;
	
	public static function fromFile($configFile) {
		if (!file_exists($configFile)) {
			throw new Exception("Configuration file not found: '$configFile'");
		}
		return new self(include($configFile));
	}

	public static function noop($data) {
		
	}

	public function log($data) {
		call_user_func($this->logger, $data);
	}

	public function __construct(array $cfg, $logger = 'Configuration::noop') {
		$this->logger = $logger;
		$required = array(
			'username' => 'string',
			'password'  => 'string',
			'signature' => 'string',
			'sandbox' => 'boolean',
		);
		foreach ($required as $key => $type) {
			if (array_key_exists($key, $cfg) === false) {
				throw new Exception("Required configuration value is missing: '$key'");
			}
			if (gettype($cfg[$key]) !== $type) {
				throw new Exception("configuration value is wrong type: '$key' was expected to be a '$type'");
			}
		}
		$this->username = $cfg['username'];
		$this->password = $cfg['password'];
		$this->signature = $cfg['signature'];
		$this->sandbox = $cfg['sandbox'];
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
