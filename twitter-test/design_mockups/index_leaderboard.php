<?php
session_start();
require_once('lib2/EpiCurl.php');
require_once('lib2/EpiOAuth.php');
require_once('lib2/EpiTwitter.php');
require_once('lib/secret.php');
require_once('mysql_connect.php');       

function ae_detect_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}

if(ae_detect_ie()){
	header("location: ie.html");
}

$twitterObj = new EpiTwitter($consumer_key,$consumer_secret);
$oauth_token = $_GET['oauth_token'];
if ($oauth_token == '' && $_SESSION['ot']==''){
	$url = $twitterObj->getAuthorizationUrl();
	$token = $twitterObj->getRequestToken();
	$oauth = $token->oauth_token;
}
else{
	//$_SESSION['oauth_token'] = $_GET['oauth_token'];
	if ($_SESSION['ot']==''){
		$twitterObj->setToken($_GET['oauth_token']);
		$token = $twitterObj->getAccessToken();
		$twitterObj->setToken($token->oauth_token,$token->oauth_token_secret);
		$_SESSION['ot'] = $token->oauth_token;
		$_SESSION['ots'] = $token->oauth_token_secret;
	}
	else{
		$twitterObj->setToken($_SESSION['ot'],$_SESSION['ots']);
	}
	header("location: kill.php");
	
	
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel='SHORTCUT ICON' href='favicon.ico'/>
		<title>The Grim Tweeper | fun and easy way to clean up your Twitter follower list</title> 
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-1108031-19']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
		<script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
	</head>                                                         
	<body>
		<div class="wrapper">
	    <div class="login-bar">
			you are not logged in
		</div>              
		</div>
    	<div class="mid-box">
	 	
			<div class="right">
				<h1>Th<span class = "ar">e</span> Grim Tw<span class = "ar">ee</span>p<span class = "ar">e</span>r</h1>
				<h2>a fun, easy way to clean up your follow list. <BR>
				login below to get started.</h2>
			</div> 
			<div class="reaper">
					<img src="images/reaper2.png">
			 </div>
   		</div> 

		<center>
			<br /><br /><br />
			<a class='login-link' href="<?=$url?>"><img src="images/sign-in-with-twitter.png"/></a><br>
			<div id="small">we don't spam and never will</div>
		</center> 
		
		<div class="description">
			<ol>
				<li class="step-one"><div id="steps">1. <img src="images/tweep.png" class="lower"> SEE A TWEEP</div>To start, we'll show you one person 
				that you're following on Twitter.</li>
				<li class="step-two"><div id="steps">2. <img src="images/reaper-small.png" class="lower"> KILL OR KEEP</div>You get to decide 
				whether to keep following 
				them or to unfollow them.</li>
				<li class="step-three"><div id="steps">3. <img src="images/repeat.png" class="lower"> RINSE AND REPEAT</div>Keep doing this until
				you're only following
				people that matter to you.</li>
			</ol>
		</div>
		<br><br>   
		<div class="footer"> 
		<span id = "about">The Grim Tweeper is Copyright 2011 <a href="http://twitter.com/weslayzhao">Wesley Zhao</a>, <a href="http://twitter.com/temiri">Tess Rinearson</a>, <a href="http://twitter.com/danshipper">Dan Shipper</a> and <a href="http://twitter.com/ajaymehta">Ajay Mehta</a></span> 
		</div>
		 
	 	<script type="text/javascript">
		var _sf_async_config={uid:19911,domain:"grimtweeper.com"};
		(function(){
		  function loadChartbeat() {
		    window._sf_endpt=(new Date()).getTime();
		    var e = document.createElement('script');
		    e.setAttribute('language', 'javascript');
		    e.setAttribute('type', 'text/javascript');
		    e.setAttribute('src',
		       (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") +
		       "js/chartbeat.js");
		    document.body.appendChild(e);
		  }
		  var oldonload = window.onload;
		  window.onload = (typeof window.onload != 'function') ?
		     loadChartbeat : function() { oldonload(); loadChartbeat(); };
		})();

		</script>   
    </body>
</html>