<?php

namespace Phitly;

class OAuth {
	public $access_token;
	
	protected $consumer_token;
	protected $consumer_secret;
	protected $redirect_uri;
	protected $client;
	protected $token;
	
	public function __construct($consumer_token, $consumer_secret, $redirect_uri) {
		$this->consumer_token  = $consumer_token;
		$this->consumer_secret = $consumer_secret;
		$this->redirect_uri    = $redirect_uri;
	}
	
	public function client() {
		$this->client = $this->client ?: new OAuth2\Client\Provider\Bitly(array(
			'clientId'     => $this->consumer_token, 
			'clientSecret' => $this->consumer_secret,
			'redirectUri'  => $this->redirect_uri
		));
		return $this->client;
	}
	
	public function authorize_url($redirect_uri) {
		$uri = $this->client->urlAuthorize();
		return str_replace('api-ssl.', '', $uri);
	}
	
	public function get_access_token_from_code($code) {
		$this->access_token = $this->access_token ?: 
			$this->client->getAccessToken('authorization_code', array(
				'code' => $code
			)
		);
		return $this->access_token;
	}
	
	public function get_access_token_from_token($token, array $params = array()) {
		// TODO: Implement this!
		throw new \Exception('Not currently implemented');
	}
}