#!/usr/bin/env php
<?php
require './config.php';
require './vendor/autoload.php';

use SpaceTrack\SpaceTrack;

SpaceTrack::init($credentials,$cookie);

$endpoint='launch_site'; // define the API endpoint key per endpoints.json config.
$postdata=null; // leave null if GET request
$decode=false; // decode JSON?

$req_data = SpaceTrack::httpRequest($endpoint,$postdata,$decode);

print $req_data;
