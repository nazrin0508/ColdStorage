<?php
$server='localhost';
$user='root';
$pass='';
$database='wh1';

function connect_db() {
	$connection=new mysqli(
			$GLOBALS['server'],$GLOBALS['user'],
			$GLOBALS['pass'],$GLOBALS['database']);
	return $connection;
}

function getconn() {
	$server=$GLOBALS['server'];
	$user=$GLOBALS['user'];
	$pass=$GLOBALS['pass'];
	$database=$GLOBALS['database'];
	//$conn=new PDO("mysql:host=$server;unix_socket=/tmp/mysql.sock;dbname=$database",$user,$pass);
	$conn=new PDO("mysql:host=$server;dbname=$database",$user,$pass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	return $conn;
}

?>
