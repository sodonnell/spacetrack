#!/usr/bin/env php
<?php
include_once '../vendor/autoload.php';
require 'config.php';

use SpaceTrack;

$spacetrack = new SpaceTrack($credentials,$cookie);

$api='boxscore'; // define the API endpoint key per endpoints.json config.
$postdata=null; // leave null if GET request
$decode=false; // decode JSON?

$req_data = $spacetrack->httpRequest($api,$postdata,$decode);

print $req_data;

