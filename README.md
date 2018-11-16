spacetrack
===

> NOTE: This project is old (but not quite deprecated) and is currently being revised to comply with modern packagist/composer and PSR standards. More updates to come, as time permits.

**A generic REST/JSON API client for the space-track.org web service, written in PHP.**

The design concept for this class is to create a universal (PHP) object specifically to interact with all of the space-track.org API services, with as minimal code as possible. 

This class supports the bandwidth-throttling suggested by the API documentation (100k/sec transfers), to minimize service bandwidth spikes. 

Ideally, you should be able to create scripts to interact with the space-track.org interface of your preference, via crontab (on a daily/weekly/monthly basis), and store/sync the data locally for post-processing via database or (key/value) datastore. Many of the API requests return very large (static) datasets, which do not change frequently, so local data caching is highly suggested.

This project is considered experimental, and is intended for authorized users of the space-track.org web service (only). 

Requirements:
===

* Linux-based Operating System
* PHP v5.0 or higher
* PHP compiled w/ CURL extension support
* An authorized user account on space-track.org

This code has not been tested in a non-linux system environment.


FAQ:
===

**Why are you using CURL instead of Guzzle?**

At the time I started working on this, back in 2010, Guzzle was not even on my radar. I'm currently revising this project to use guzzle, though, so once I release it in a modern PSR-compliant composer package, it will indeed use Guzzle and other related packages, such as the guzzle-advanced-throttle (or other rate-limiting/caching related packages).

**There is already a spacetrack package on packagist. Why re-create the wheel?**

See ephemeris: https://packagist.org/packages/luke-nz/ephemeris

Technically, I started this project long before the ephemeris package was available. 

However, my focus here will be integrating this into the laravel world. Also, ephemeris doesn't support all of the current space-track.org API endpoints (as far as I can see from glancing over the code), so my goal is to support all of the endpoints with as minimal amount of code as possible, as this project currently does.
