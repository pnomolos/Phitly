<?php

namespace Phitly;

class MissingUrl {
	public $short_url;
	public $user_hash;
	public $long_url;
	public $error;
	
	public function __construct($args) {
		$this->error     = $args->error;
		$this->long_url  = $args->long_url;
		$this->short_url = $args->short_url;
		$this->user_hash = $args->hash;
	}
}