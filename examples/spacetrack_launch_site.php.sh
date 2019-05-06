#!/usr/bin/env php
<?php
/** simple spacetrack API client script example using PHP/CLI scripting */
require 'spacetrack.php';
require 'config.php';

$spacetrack = new SpaceTrack($credentials,$cookie);

$api='launch_site';
$postdata=null;
$decode=false;

$req_data = $spacetrack->httpRequest($api,$postdata,$decode);

print $req_data;
