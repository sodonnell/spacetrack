#!/usr/bin/env php
<?php
require '../vendor/autoload.php';
require 'config.php';

use SpaceTrack;

SpaceTrack::init($credentials,$cookie);

$endpoint='boxscore'; // define the API endpoint key per endpoints.json config.
$postdata=null; // leave null if GET request
$decode=false; // decode JSON?

$req_data = SpaceTrack::httpRequest($endpoint,$postdata,$decode);

print $req_data;
