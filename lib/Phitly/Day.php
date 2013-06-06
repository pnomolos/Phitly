<?php

namespace Phitly;

class Day {
	public $clicks;
	public $day_start;
	
	public function __construct($args) {
		$this->clicks    = $args->clicks;
		$this->day_start = Client::parse_time($args->day_start);
	}
}