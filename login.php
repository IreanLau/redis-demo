<?php
require("redis.php");
$username = $_POST['username'];
$pass = $_POST['password'];
$id = $redis->get("username:".$username);
if(!empty($id)){
	$password = $redis->hget("user:".$id,"password");
	if(md5($pass) == $password){
		$auth = md5(time().$username.rand());//随机变量
		$redis->set("auth".$auth,$id);
		setcookie("auth",$auth,time()+86400);
		header("location:list.php");
	}
}

?>
<form aciton="" method="post">
用户名：<input type="text" name="username"/> <br/>
密码：<input type="password" name="password"/> <br/>
<input type="submit" value="登录"/> <br/>
</form>
