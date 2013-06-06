<?php
/**
* @package Phitly
*/

error_reporting(-1);
ini_set('display_errors', 1);

// Date setup
date_default_timezone_set('America/Vancouver');

function get_credentials() {
	return (object)array(
		'login' => 'phitly',
		'apiKey' => 'R_bf891cf7b0160a91756c51815b5d2271'
	);
}

/**
* Autoload test fixtures
*/
function test_phitly_autoloader($className) {
	// Only autoload classes that start with 'Test_' or 'Phitly'
	if (false === strpos($className, 'Test_') && false === strpos($className, 'Phitly')) {
		return false;
	}
	
	if (false !== strpos($className, 'Phitly')) {
		$className = '../lib/' . $className;
	}
	$classFile = str_replace(array('_','\\'), '/', $className) . '.php';
	require __DIR__ . '/' . $classFile;
}
spl_autoload_register('test_phitly_autoloader');

require_once(__DIR__ . '/../vendor/autoload.php');