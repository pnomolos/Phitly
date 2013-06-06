<?php

namespace Phitly;

class Url {
	public $short_url;
	public $jmp_url;
	public $long_url;
	public $user_hash;
	public $global_hash;
	public $aggregate_link;
	
	protected $client;
	
	protected $new_hash;
	protected $user_clicks;
	protected $global_clicks;
	protected $title;
	protected $created_by;
	protected $created_at;
	protected $referrers;
	protected $countries;
	protected $clicks_by_day;
	protected $clicks_by_minute;
	
	public function __construct($client, $opts) {
		if (count($opts)) {
			foreach (array_intersect(
				array('jmp_url', 'long_url', 'global_hash', 'user_clicks', 'global_clicks', 'title', 'created_by', 'aggregate_link'),
				array_keys(get_object_vars($opts)))
				as $var) {
					$this->$var = $opts->$var;
				}
			$this->short_url = $opts->url ?: $opts->short_url;
			$this->user_hash = $opts->hash ?: $opts->user_hash;
			$this->is_new_hash = (1 == $opts->new_hash);
			if (!empty($opts->created_at)) {
				$this->created_at = new \DateTime($opts->created_at);
			}
			if (!empty($opts->referrers)) {
				$this->referrers = array();
				foreach ($opts->referrers as $referrer) {
					$this->referrers[] = new Referrer($referrer);
				}
			}
			if (!empty($opts->countries)) {
				$this->countries = array();
				foreach ($opts->countries as $country) {
					$this->countries[] = new Country($country);
				}
			}
			if (!empty($opts->clicks) && is_array($opts->clicks[0])) {
				$this->clicks_by_day = array();
				foreach ($opts->clicks as $day) {
					$this->clicks_by_day[] = new Day($day);
				}
			} else if (!empty($opts->clicks)) {
				$this->clicks_by_minute = $opts->clicks;
			}
		}
		$this->short_url = $this->short_url ?: "http://bit.ly/{$this->user_hash}";
	}
	
	public function is_new_has() {
		return $this->is_new_hash;
	}
	
	public function user_clicks(array $opts = array()) {
		$this->update_clicks_data(!empty($opts['force']));
		return $this->user_clicks;
	}
	
	public function global_clicks(array $opts = array()) {
		$this->update_clicks_data(!empty($opts['force']));
		return $this->global_clicks;
	}
	
	public function title(array $opts = array()) {
		$this->update_info(!empty($opts['force']));
		return $this->title;
	}
	
	public function created_by(array $opts = array()) {
		$this->update_info(!empty($opts['force']));
		return $this->created_by;
	}
	
	public function created_at(array $opts = array()) {
		$this->update_info(!empty($opts['force']));
		return $this->created_at;
	}
	
	public function referrers(array $opts = array()) {
		$this->update_referrers(!empty($opts['force']));
		return $this->referrers;
	}
	
	public function countries(array $opts = array()) {
		$this->update_countries(!empty($opts['force']));
		return $this->countries;
	}
	
	public function clicks_by_minute(array $opts = array()) {
		$this->update_clicks_by_minute(!empty($opts['force']));
		return $this->clicks_by_minute;
	}
	
	public function clicks_by_day(array $opts = array()) {
		$this->update_clicks_by_day(!empty($opts['force']));
		return $this->clicks_by_day;
	}
	
	public function jmp_url() {
		return ($this->short_url ? str_replace('bit.ly', 'j.mp', $this->short_url) : null);
	}
	
	public function qrcode_url(array $opts = array()) {
		return $this->short_url . (!empty($opts['s']) ? ".qrcode?s={$opts['s']}" : '.qrcode');
	}
	
	private function update_clicks_data($force = false) {
		if ($force || !$this->global_clicks) {
			$full_url = $this->client->clicks($this->user_hash ?: $this->short_url);
			$this->global_clicks = $full_url->global_clicks;
			$this->user_clicks   = $full_url->user_clicks;
		}
	}
	
	private function update_info($force = false) {
		if ($force || !$this->created_by || !$this->title || !$this->created_at) {
			$full_url = $this->client->info($this->user_hash ?: $this->short_url);
			$this->created_at = new \DateTime($full_url->created_at);
			$this->created_by = $full_url->created_by;
			$this->title      = $full_url->title;
		}
	}
	
	private function update_referrers($force = false) {
		if ($force || !$this->referrers) {
			$full_url = $this->client->referrers($this->user_hash ?: $this->short_url);
			$this->referrers = $full_url->referrers;
		}
	}
	
	private function update_countries($force = false) {
		if ($force || !$this->countries) {
			$full_url = $this->client->countries($this->user_hash ?: $this->short_url);
			$this->countries = $full_url->countries;
		}
	}
	
	private function update_clicks_by_minute($force = false) {
		if ($force || !$this->clicks_by_minute) {
			$full_url = $this->client->clicks_by_minute($this->user_hash ?: $this->short_url);
			$this->clicks_by_minute = $full_url->clicks_by_minute;
		}
	}
	
	private function update_clicks_by_day($force = false) {
		if ($force || !$this->clicks_by_day) {
			$full_url = $this->client->clicks_by_day($this->user_hash ?: $this->short_url);
			$this->clicks_by_day = $full_url->clicks_by_day;
		}
	}
}