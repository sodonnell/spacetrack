spacetrack
===

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

Installation:
===

```
composer require "sodonnell/spacetrack":"@dev"
```
