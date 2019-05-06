#!/usr/bin/env php
<?php
require '../vendor/autoload.php';
require 'config.php';

use SpaceTrack;

SpaceTrack::init($credentials,$cookie);

$endpoint='launch_site';
$postdata=null;
$decode=false;

$req_data = SpaceTrack::httpRequest($endpoint,$postdata,$decode);

print $req_data;
