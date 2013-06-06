<?php

namespace Phitly;

class RealtimeLink {
	public $clicks;
	public $user_hash;
	
	public function __construct($args) {
		$this->clicks    = $args->clicks;
		$this->user_hash = $args->user_hash;
	}
	
	public function create_url(Client $client) {
		return new Url($client, (object)array('user_clicks' => $this->clicks, 'user_hash' => $this->user_hash));
	}
}
