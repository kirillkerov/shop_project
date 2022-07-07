<?php

//отчет об ошибках misqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//"ленивое" подключение к бд
function connect()
{
	
	$host = 'localhost';
	$user = 'admin_fashion';
	$pass = '123456';
	$db = 'fashion';

	static $connect = null;

	if ($connect === null) {
		$connect = mysqli_connect($host, $user, $pass, $db) or die('connection error:' . mysqli_connect_error());
	}

	return $connect;

}