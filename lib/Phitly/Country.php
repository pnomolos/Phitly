<?php

namespace Phitly;

class Country {
	public $clicks;
	public $country;
	
	public function __construct($args) {
		$this->clicks  = $args->clicks;
		$this->country = $args->country;
	}
}