<?php

namespace Phitly;

class Referrer {
	public $clicks;
	public $referrer;
	public $referrer_app;
	public $url;
	
	public function __construct($args) {
		$this->clicks       = $args->clicks;
		$this->referrer     = $args->referrer;
		$this->referrer_app = $args->referrer_app;
		$this->url          = $args->url;
	}
}