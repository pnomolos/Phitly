<?php

namespace Phitly;

class Http {
	protected $default_query_opts = array();
	protected $base_uri = 'http://api.bitly.com/v3/';
	
	function __construct($login, $apiKey, $timeout = null) {
		$this->default_query_opts = array('login' => $login, 'apiKey' => $apiKey);
		$this->timeout = null;
	}
	
	
	protected function get($method, array $opts = array()) {
		$response = null;
		try {
			$response = $this->_get($method, $opts);
		} catch (\ConnectionErrorException $e) {
			throw new BitlyTimeout("Bitly didn't respond in time", 504);
		}
		
		if ('200' == $response->status_code) {
			return $response;
		} else {
			var_dump($response); exit;
			throw new BitlyError($response->status_txt, $response->status_code);
		}
	}
	
	protected function _get($method, $opts = array()) {
		$opts += array('query' => array(), 'timeout' => 0);
		$opts['query'] = array_merge($opts['query'], $this->default_query_opts);
		$url = ltrim($method, '/') . (!strpos($method, '?') ? '?' : '&') . http_build_query($opts['query']);
		
		$client = new \Guzzle\Http\Client($this->base_uri);
		$request = $client->get($url);
		if ($opts['timeout']) {
			$request->getCurlOptions()->set(CURLOPT_TIMEOUT, $opts['timeout']);
		}
		
		$response = $request->send();
		return json_decode(json_encode($response->json()));
	}
}