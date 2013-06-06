## Phitly

A PHP library for connecting to the bit.ly v3 API.  At the moment only tests for shortening URLs have been written as that's all that I need to do, should someone else want to help out that'd be great!

##Usage

```php
$client = new \Phitly\Client($login, $apiKey);
// Returns a \Phitly\Url object
$url = $client->shorten('http://google.com');
$urls = $client->shorten(array('http://cnn.com', 'http://google.com'));
```

### Acknowledgements
[Phil Nash's ruby bitly library](https://github.com/philnash/bitly) - on which a significant portion of this library was based.

### Notes
As this library is still under development significant changes may still happen.  Specifically, bitly is deprecating the usage of the apiKey parameter so the `\Phitly\Client` constructor is likely to change to support OAuth2 integration.

## Copyright

Copyright &copy; 2011 [Philip Schalm](http://twitter.com/pnomolos)