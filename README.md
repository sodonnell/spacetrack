# spacetrack

**A generic REST/JSON API client for the [space-track.org](https://www.space-track.org/) web service, written in PHP.**

The design concept for this class is to create a universal (PHP) object specifically to interact with all of the space-track.org API services, with as minimal code as possible.

This class supports the bandwidth-throttling suggested by the API documentation (100k/sec transfers), to minimize service bandwidth spikes.

Ideally, you should be able to create scripts to interact with the [space-track.org API endpoint](https://www.space-track.org/documentation#/api) of your preference, via crontab (on a daily/weekly/monthly basis), and store/sync the data locally for post-processing via database or (key/value) datastore. Many of the API requests return very large (static) datasets, which do not change frequently, so local data caching is highly suggested.

This project is considered experimental, and is intended for authorized users of the [space-track.org](https://www.space-track.org/) web service (only).

## Requirements

* PHP v5.0 or higher, compiled w/ the CURL extension
* An authorized user account on [space-track.org](https://www.space-track.org/)

## Installation

```php
composer require "sodonnell/spacetrack"
```

## Getting started

### Usage Example

```php
<?php
require './vendor/autoload.php';

$credentials = [
    'username'=>'???your-username???',
    'password'=>'???your-password???',
];

$cookie = '/tmp/spacetrack.cookie.txt';

use SpaceTrack\SpaceTrack;

SpaceTrack::init($credentials,$cookie);

// optional parameter: decode JSON to PHP Array?
$decode=true;
$response = SpaceTrack::getLaunchSite($decode);

print_r($response);
```

### Available Functions

* SpaceTrack::init(array $credentials, string $cookie)
* SpaceTrack::getAnnouncement(bool $decode_json)
* SpaceTrack::getBoxScore(bool $decode_json)
* SpaceTrack::getCSM(bool $decode_json)
* SpaceTrack::getDecay(bool $decode_json)
* SpaceTrack::getLaunchSite(bool $decode_json)
* SpaceTrack::getOMM(bool $decode_json)
* SpaceTrack::getOrganization(bool $decode_json)
* SpaceTrack::getSatCat(bool $decode_json)
* SpaceTrack::getSatCatChange(bool $decode_json)
* SpaceTrack::getSatCatDebut(bool $decode_json)
* SpaceTrack::getTip(bool $decode_json)
* SpaceTrack::getTLE(bool $decode_json)
* SpaceTrack::getTLELatest(bool $decode_json)
* SpaceTrack::getTLEPublish(bool $decode_json)
