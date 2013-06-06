<?php

namespace Phitly;

class User extends Http {
	public $login;
	public $api_key;
	
	protected $access_token;
	protected $clicks;
	protected $countries;
	protected $realtime_links;
	protected $referrers;
	protected $total_clicks;
	
	public function __construct($access_token) {
		$this->access_token = $access_token;
		$this->default_query_opts = array('login' => $access_token->login, 'apiKey' => $access_token->apiKey);
	}
	
	public function referrers(array $opts = array()) {
		if (!$this->referrers || !empty($opts['force'])) {
			unset($opts['force']);
			$this->referrers = $this->call('referrers', 'Referrer', $opts);
		}
		return $this->referrers;
	}
	
	public function countries(array $opts = array()) {
		if (!$this->countries || !empty($opts['force'])) {
			unset($opts['force']);
			$this->countries = $this->call('countries', 'Country', $opts);
		}
		return $this->countries;
	}
	
	public function realtime_links(array $opts = array()) {
		if (!$this->realtime_links || !empty($opts['force'])) {
			unset($opts['force']);
			$opts += array('access_token' => $this->access_token->token);
			$result = $this->get('/user/realtime_links', array('query' => $opts));
			$this->realtime_links = array_map(function($rl) { return new RealtimeLink($rl); }, $result->data->realtime_links);
		}
		return $this->realtime_links;
	}
	
	public function clicks(array $opts = array()) {
		$this->get_clicks($opts);
		return $this->clicks;
	}
	
	public function total_clicks(array $opts = array()) {
		$this->get_clicks($opts);
		return $this->total_clicks;
	}
	
	public function client() {
		$this->client = $this->client ?: new Client($this->login, $this->api_key);
	}
	
	public function link_history(array $opts = array()) {
		$opts += array('access_token' => $this->access_token->token);
		$result = $this->get('/user/link_history', array('query' => $opts));
		return array_map(function($lh) { 
			return new Url($this->client,
				(object)array(
					'short_url' => $lh->link,
					'hash' => array_slice(explode('/', $lh->link), -1, 1)
				)
			);
		}, $result->data->link_history);
	}
	
	private function call($method, $class, array $opts = array()) {
		$opts += array('access_token' => $this->access_token->token);
		$result = $this->get("/user/{$method}", array('query' => $opts));
		return array_map(function($obj) use($klass){
			return new $klass($obj);
		}, $this->data->$method);
	}
	
	private function get_clicks(array $opts = array()) {
		if (!$this->clicks || !empty($opts['force'])) {
			unset($opts['force']);
			$opts += array('access_token' => $this->access_token->token);
			$result = $this->get('/user/clicks', array('query' => $opts));
			$this->clicks = array_map(function($c) { return new Day($c); }, $this->data->clicks);
			$this->total_clicks = $this->data->total_clicks;
		}
	}
}