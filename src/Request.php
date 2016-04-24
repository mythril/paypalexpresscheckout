<?php

namespace Mythril\PayPal\ExpressCheckout;

class Request {
	const SANDBOX = 'https://api-3t.sandbox.paypal.com/nvp';
	const LIVE = 'https://api-3t.paypal.com/nvp';
	protected $endPoint;
	protected $cfg;
	public function __construct(Configuration $cfg) {
		$this->cfg = $cfg;
		$this->endPoint = $cfg->useSandbox() ? self::SANDBOX : self::LIVE;
	}

	protected static function parseResponse($res) {
		$split = explode('&', $res);
		$arr = array();
		foreach ($split as $whole) {
			$parts = explode('=', $whole);
			$arr[$parts[0]] = isset($parts[1]) ? urldecode($parts[1]) : '';
		}
		return $arr;
	}

	public function send($method, array $data = array()) {
		$c = curl_init();

		$data['METHOD'] = $method;
		$data['VERSION'] = Gateway::API_VERSION;
		$data['USER'] = $this->cfg->getUsername();
		$data['PWD'] = $this->cfg->getPassword();
		$data['SIGNATURE'] = $this->cfg->getSignature();

		$options = array(
			CURLOPT_URL => $this->endPoint,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSLVERSION => 6,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($data, NULL, '&'),
			CURLOPT_TIMEOUT => 30,
			CURLOPT_RETURNTRANSFER => true,
		);

		curl_setopt_array($c, $options);

		$response = curl_exec($c);

		if ($response === false) {
			$code = curl_errno($c);
			$error = curl_error($c);
			curl_close($c);
			throw new Exception("cURL issued an error while trying to contact PayPal: [$code] $error");
		}
		
		curl_close($c);

		$parsed = self::parseResponse($response);

		if (empty($parsed['ACK']) || strpos($parsed['ACK'], 'Success') === false) {
			throw new Exception("PayPal API did not 'ACK'.");
		}

		return $parsed;
	}
}
