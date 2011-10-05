<?php
require_once("mysql_connect.php");

function getLeaderboard($max){
	$html = '';
	mysqlConnect();
	$res = mysql_query("SELECT username,image_url,score FROM scores ORDER BY score DESC LIMIT $max");
	if (mysql_num_rows($res)){
	$is_odd = true;
	$count = 1;
		while ($row = mysql_fetch_array($res)){
			$image_url = $row['image_url'];
			if ($image_url=='') $image_url = "http://www.grimtweeper.com/images/smiley.png";
			if ($is_odd) $tr_class = 'odd';
			else $tr_class = 'even';
			$row_html ="<tr class = \"$tr_class\"><td class = \"numeral\">$count</td>
			<td><img src= '$image_url' height='28px'/></td>
			<td class = \"name\"><a target='_blank' href='http://twitter.com/{$row['username']}'>{$row['username']}</a></td>
			<td class = \"score\">{$row['score']}</td>";
			$html = $html.$row_html;
			if ($is_odd) $is_odd = false;
			else $is_odd = true;
			$count++;
		}
	}
	return $html;
}
?>