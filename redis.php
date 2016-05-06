<?php

$redis = new Redis();

$redis->pconnect("localhost");

$redis->auth("ireanlau");


//$data = $redis->keys("*");

//var_dump($data);
