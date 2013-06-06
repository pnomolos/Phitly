<?php

namespace Phitly\OAuth2\Client\Provider;

class Bitly extends \League\OAuth2\Client\Provider\IdentityProvider
{
	public $responseType = 'json';
	public static $baseUrl = 'https://api-ssl.bitly.com/';
	
	public function urlAuthorize() {
		return static::$baseUrl . 'oauth/authorize';
	}
	
	public function urlAccessToken() {
		return static::$baseUrl . 'oauth/access_token';
	}
	
	public function urlUserDetails(\OAuth2\Client\Token\AccessToken $token) {
		return static::$baseUr . 'v3/user/info?access_token=' . $token;
	}
	
	public function userDetails($response, \OAuth2\Client\Token\AccessToken $token) {
		$user = new \League\OAuth2\Client\Provider\User();
		$user->apiKey = $response->data->apiKey;
		$user->login = $response->data->login;
		return $user;
	}
}