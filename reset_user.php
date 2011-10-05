<?php
require_once("mysql_connect.php");

session_start();

$user_id = $_SESSION['user_id'];
if ($user_id){
	mysqlConnect();
	$res = mysql_query("DELETE FROM views WHERE (viewed_by_id='$user_id' AND is_killed='0')");
}
else echo 'stop trying to hack';
?>