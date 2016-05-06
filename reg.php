<?php
require("redis.php");
$username = $_POST['username'];
$password = md5($_POST['password']);
$age = $_POST['age'];

$uid = $redis->incr("userid");
$res=$redis->hmset("user:".$uid,array("uid"=>$uid,"username"=>
	$username,"password"=>$password,"age"=>$age));

$redis->rpush("uid",$uid);//为了统计，将uid存入list
$redis->set("username:".$username,$uid);
header("location:list.php");
