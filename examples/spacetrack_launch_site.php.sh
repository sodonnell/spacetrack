#!/usr/bin/env php
<?php
require './vendor/autoload.php';

use SpaceTrack\SpaceTrack;

$credentials = [
    'username'=>'',
    'password'=>''
];

$cookie = '/tmp/spacetrack.cookie.txt';

SpaceTrack::init($credentials,$cookie);

$decode=false; // decode JSON to PHP Array?

$response = SpaceTrack::getLaunchSite($decode);

print $response;
