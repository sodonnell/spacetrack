#!/usr/bin/env php
<?php
require '../vendor/autoload.php';
require 'config.php';

SpaceTrack::init($credentials,$cookie);

$api='launch_site';
$postdata=null;
$decode=false;

$req_data = SpaceTrack::httpRequest($api,$postdata,$decode);

print $req_data;
