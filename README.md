# Amara PHP Client

[![Latest Stable Version](http://poser.pugx.org/travelosteam/amara-client/v)](https://packagist.org/packages/travelosteam/amara-client)
[![Total Downloads](http://poser.pugx.org/travelosteam/amara-client/downloads)](https://packagist.org/packages/travelosteam/amara-client) 
[![Latest Unstable Version](http://poser.pugx.org/travelosteam/amara-client/v/unstable)](https://packagist.org/packages/travelosteam/amara-client)
[![License](http://poser.pugx.org/travelosteam/amara-client/license)](https://packagist.org/packages/travelosteam/amara-client)

## Requirements

PHP 7.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require travelosteam/amara-client
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Getting Started

### Downloading Offers

```php
use TravelOS\API\Suppliers\Amara\Clients\Offer
use TravelOS\API\Suppliers\Amara\Clients\Soap\UserToken

/*
 * $user, $pass, $code from Amara TO
 * $key from amara docs
 */
$token = new UserToken($user, $pass, $code, $key);
 
$client = new Offer();
$client->setToken($token);
$archive = $client->DownloadOffer();

$file = pathinfo($archive->FileName, PATHINFO_FILENAME);
file_put_contents($file, $archive->FileContent);
```

### Booking Offer

```php
use TravelOS\API\Suppliers\Amara\Clients\Book
use TravelOS\API\Suppliers\Amara\Clients\Soap\ReservationRequestInfo
use TravelOS\API\Suppliers\Amara\Clients\Soap\UserToken

/*
 * $user, $pass, $code from Amara TO
 * $key from amara docs
 */
$token = new UserToken($user, $pass, $code, $key);
 
$client = new Book();
$client->setToken($token);

$reservation = new ReservationRequestInfo();
// ... add reservation data

try{
   $client->verify($reservation);
   $info = $client->book($reservation);
   var_dump($info);
} carch (\Exception $e){
    echo $e->getMessage();
}
```
