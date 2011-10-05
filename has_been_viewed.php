<?php
//returns 1 if the user has been viewed
//returns 0 if the user has not already been viewed, and will also track the view
require_once('mysql_connect.php');

$user_id = $_GET['user_id'];
$friend_id = $_GET['friend_id'];

mysqlConnect();

$res = mysql_query("SELECT user_id FROM views WHERE (user_id='$friend_id' AND viewed_by_id='$user_id')");
if (mysql_num_rows($res)){
	echo 1;
}
else{
	mysql_query("INSERT INTO views (user_id,viewed_by_id) VALUES ('$friend_id','$user_id')");
	echo 0;
}

?>