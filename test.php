<?php
	header("Content-type:text/html;charset=gbk");
	$link = mysqli_connect('127.0.0.1','root','');

	var_dump($link);

	$link = mysqli_connect('47.93.201.108','root','root');

	if(!$link)
	{
		 die("连接错误: " . mysqli_connect_error()); 
	}
	var_dump($link);
?>