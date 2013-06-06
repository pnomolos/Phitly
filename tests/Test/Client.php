<?php
/**
 * @package Phitly
 * @link http://spot.os.ly
 */
class Test_Client extends PHPUnit_Framework_TestCase
{
	protected $backupGlobals = false;
	protected $bitly = null;
	
	protected function setUp() {
		$this->bitly = new \Phitly\Client(get_credentials()->login, get_credentials()->apiKey);
	}
	
	/**
	 * @group Single Link
	 */
	public function testShorteningSingleLink() {
		$url = $this->bitly->shorten('http://cnn.com/');
		return $url;
	}
	
	/**
	 * @depends testShorteningSingleLink
	 * @group Single Link
	 */
	public function testSingleLinkReturnedShortUrl($url) {
		$this->assertEquals('http://bit.ly/11FtX5w', $url->short_url);
	}
	
	/**
	 * @depends testShorteningSingleLink
	 * @group Single Link
	 */
	public function testSingleLinkReturnedJmpUrl($url) {
		$this->assertEquals('http://j.mp/11FtX5w', $url->jmp_url());
	}
	
	/**
	 * @depends testShorteningSingleLink
	 * @group Single Link
	 */
	public function testSingleLinkRetainedLongUrl($url) {
		$this->assertEquals('http://cnn.com/', $url->long_url);
	}
	
	/**
	 * @group Multiple Links
	 */
	public function testShorteningMultipleLinks() {
		$urls = $this->bitly->shorten(array('http://cnn.com/', 'http://google.com/'));
		return $urls;
	}
	
	/**
	 * @depends testShorteningMultipleLinks
	 * @group Multiple Links
	 */
	public function testMultipleLinksReturnedShortUrlsInOrder($urls) {
		$this->assertEquals('http://bit.ly/11FtX5w', $urls[0]->short_url);
		$this->assertEquals('http://bit.ly/Zu2Pdg',  $urls[1]->short_url);
	}
	
	/**
	 * @depends testShorteningMultipleLinks
	 * @group Multiple Links
	 */
	public function testMultipleLinksReturnedLongUrlsInOrder($urls) {
		$this->assertEquals('http://cnn.com/',    $urls[0]->long_url);
		$this->assertEquals('http://google.com/', $urls[1]->long_url);
	}
}