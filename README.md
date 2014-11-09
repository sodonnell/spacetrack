spacetrack
===

A generic REST/JSON API client for the space-track.org web service, written in PHP.

This project is considered experimental, and is intended for authorized users of the space-track.org web service (only). 

The design concept for this class is to create a universal library specifically to interact with all of the API services, with as little coding as possible. This class supports the bandwidth-throttling suggested by the API documentation (100k/sec transfers) to minimize server load. 

Ideally, you should be able to create scripts to interact with the space-track interface of your preference, via crontab (on a daily/weekly/monthly basis), and store/sync the data locally for post-processing via database or (key/value) datastore. 
