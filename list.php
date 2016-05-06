<a href="add.php">注册</a>
<?php
	require("redis.php");
	if(!empty($_COOKIE['auth'])){
		$id = $redis->get("auth".$_COOKIE['auth']);
		$name = $redis->hget("user:".$id,"username");
?>
	欢迎您 <?php echo $name?> ,<a href="logout.php">退出</a>

<?php
	}else{
?>


<a href="login.php">登录</a>
<?php
	}
	//分页
	//用户总数
	$count = $redis->lsize("uid");


	//页大小
	$page_size = 4;



	//当前页码
	$page_num = (!empty($_GET['page']))?$_GET['page']:1;


	//页总数
	$page_count = ceil($count/$page_size);//取整

	$ids = $redis->lrange("uid",($page_num-1)*$page_size,(($page_num-1)*$page_size+$page_size-1));
	
//	var_dump($ids);

/*	for($i=1;$i<=($redis->get("userid"));$i++){
		$data[] = $redis->hgetall("user:".$i);
	}
	$data = array_filter($data);
 */

	//分页展示
	foreach($ids as $v){
		$data[] = $redis->hgetall("user:".$v);
	}
?>

<table border=1>
	<tr>
		<th>uid</th>
		<th>username</th>
		<th>age</th>
		<th>操作</th>
	<tr>
<?php foreach($data as $v){ ?>
	<tr>
		<td><?php echo $v['uid']?></td>
		<td><?php echo $v['username']?></td>
		<td><?php echo $v['age']?></td>
		<td> <a href="del.php?id=<?php echo $v['uid']?>">删除</a> <a href=
		"mod.php?id=<?php echo $v['uid']?>">编辑</a>
	<?php if(!empty($_COOKIE['auth']) && $id!=$v['uid']) {?>
	<a href="addfans.php?id=<?php echo $v['uid']?>&uid=<?php echo $id?>">加关注</a></td>
<?php }?>
</tr>
<?php }?>

<tr>
	<td colspan="4">
	<a href="?page=<?php echo(($page_num-1)<=1)?1:($page_num-1)?>">上一页</a>
	<a href="?page=<?php echo(($page_num+1)>=$page_count)?$page_count:$page_num+1?>">下一页</a>
	<a href="?page=1">首页</a>
	<a href="?page=<?php echo($page_count)?>">尾页</a>
	
	当前<?php echo $page_num?>页
	总共<?php echo $page_count?>页
	总共<?php echo $count?>用户
	</td>
</tr>
</table>

<table border=1>
	<caption>我关注了谁</caption>
<?php 
	$data = $redis->smembers("user:".$id.":following");
	foreach($data as $v){
		$row = $redis->hgetall("user:".$v);
?>
	<tr> 
		<td> <?php echo $row["uid"]?> </td> 
		<td> <?php echo $row["username"]?> </td> 
		<td> <?php echo $row["age"]?> </td> 
	</tr>
<?php
	}
?>
</table>


<table border=1>
	<caption>我的粉丝</caption>
<?php 
	$data = $redis->smembers("user:".$id.":fans");
	foreach($data as $v){
		$row = $redis->hgetall("user:".$v);
?>
	<tr> 
		<td> <?php echo $row["uid"]?> </td> 
		<td> <?php echo $row["username"]?> </td> 
		<td> <?php echo $row["age"]?> </td> 
	</tr>
<?php
	}
?>
</table>
