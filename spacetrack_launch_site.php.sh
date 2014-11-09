#!/usr/bin/env php
<?php
/** simple spacetrack API client script example using PHP/CLI scripting */
require 'spacetrack.php';

$spacetrack = spacetrack::getInstance();

$api='launch_site';
$postdata=null;
$decode=false;

$req_data = $spacetrack->api_call($api,$postdata,$decode);

print $req_data;
?>
