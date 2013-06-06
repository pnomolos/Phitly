<?php

namespace Phitly;

class Client extends Http {
	protected static $lookup_map = array(
		'shorten' => 'longUrl',
		'lookup'  => 'longUrl',
		'expand'  => 'shortUrl',
		'info'    => 'shortUrl',
		'stats'   => 'shortUrl',
		'clicks_by_minute' => 'shortUrl',
		'clicks_by_day'    => 'shortUrl'
	);
	
	function validate($x_login, $x_apiKey) {
		$response = $this->get('/validate', array('query' => compact('x_login', 'x_apiKey')));
		return 1 == $response['data']['valid'];
	}
	
	function shorten($input, array $opts = array()) {
		return $this->call('shorten', $input, $opts);
	}
	
	function expand($input) {
		return $this->call('expand', $input);
	}
	
	function clicks($input) {
		return $this->call('clicks', $input);
	}
	
	function info($input) {
		return $this->call('info', $input);
	}
	
	function lookup($input) {
		return $this->call('lookup', $input);
	}
	
	function referrers($input) {
		return $this->call_single('referrers', $input);
	}
	
	function clicks_by_minute($input) {
		return $this->call('clicks_by_minute', $input);
	}
	
	function clicks_by_day($input, $opts = array()) {
		foreach ($opts as $k => $v) {
			if ('days' != $k) {
				unset($opts[$k]);
			}
		}
		return $this->call('clicks_by_day', $input, $opts);
	}
	
	protected function is_a_short_url($input) {
		return strpos($input, 'http://') !== false;
	}
	
	protected function call_single($method, $input) {
		if (!is_string($input)) {
			throw new \InvalidArgumentException('This method only takes a hash or url input');
		}
		$query = "/{$method}?" . ($this->is_a_short_url($input) ? 'shortUrl=' : 'hash=') . urlencode($input);
		$response = $this->get($query);
		return new Url($this, $response->data);
	}
	
	protected function call($method, $input, array $opts = array()) {
		$input = (array)$input;
		$query = array();
		foreach ($opts as $k => $v) {
			$query[] = "{$k}={$v}";
		}
		
		$arg_name = static::$lookup_map[$method];
		foreach ($input as $arg) {
			$arg_name = $arg_name == 'shortUrl' ? ($this->is_a_short_url($v) ? 'shortUrl' : 'hash') : $arg_name;
			$new_query = $query;
			$new_query[] = "{$arg_name}=" . urlencode($arg);
			$new_query = "/{$method}?" . join('&', $new_query);
			$response = $this->get($new_query);
			$arr = isset($response->data->$method) ? $response->data->$method : $response->data;
			if (!is_array($arr)) {
				$arr = array($arr);
			}
			foreach ($arr as $url) {
				$result_index = array_search(
					isset($url->short_url) ? $url->short_url : 
					isset($url->hash) ? $url->hash :
					isset($url->long_url) ? $url->long_url :
					$url->global_hash, 
					$input
				);
				// unset($input[$result_index]);
				if (empty($url->error)) {
					$results[$result_index] = new Url($this, $url);
				} else {
					$results[$result_index] = new MissingUrl($url);
				}
			}
		}
		return count($results) > 1 ? $results : $results[0];
	}
}
