<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<script src="misc/jquery-1.7.1.min.js" type="text/javascript" ></script>
<script src="misc/jquery.lwtCountdown-1.0.js" type="text/javascript" ></script>
<script src="misc/register_contest.js" type="text/javascript" ></script>
<script src="misc/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="misc/jquery-ui-1.8.18.custom/css/start/jquery-ui-1.8.18.custom.css"/>
<link rel="stylesheet" type="text/css" href="misc/style.css"/>
<title><?php require_once "config.php";echo $head_title['index'] ?></title>
</head>
<?php show_message();view_count(); ?>
<div id="logo" align="center"><img src="misc/img/logo.jpg" /></div>
<div align=center><h2><?php echo $page_title['index'] ?></h2></div>
<DIV class=example_pane>
	<div id="countdown_dashboard">
		<div class="dash weeks_dash">
			<span class="dash_title">weeks</span>
			<div class="digit">0</div>
			<div class="digit">0</div>
		</div>
		<div class="dash days_dash">
			<span class="dash_title">days</span>
			<div class="digit">0</div>
			<div class="digit">0</div>
		</div>
		<div class="dash hours_dash">
			<span class="dash_title">hours</span>
			<div class="digit">0</div>
			<div class="digit">0</div>
		</div>
		<div class="dash minutes_dash">
			<span class="dash_title">minutes</span>
			<div class="digit">0</div>
			<div class="digit">0</div>
		</div>
		<div class="dash seconds_dash">
			<span class="dash_title">seconds</span>
			<div class="digit">0</div>
			<div class="digit">0</div>
		</div>
	</div>
</DIV>
<table align=center>
<?php
foreach (contest_info() as $key => $value)
	echo "<tr><td width=13%></td><td class=info><b>$key</b>:</td><td>$value</td><td></td></tr>";
?>
</table>

<?php if(!isset($_SESSION['user'])): ?>
<div align="center" id="login1"><a href="#">使用OJ账号登录</a></div>
<div align="center" id="login" style="display:none">
<form action="login.php" method="post">
User ID:<input type="text" name="user_id" size=10><br/>
Password:<input type="password" name="password" size=10><br/>
<input type="hidden" name="op" value="login">
<input type="submit" value="Login">    <a href="../oj/registerpage">Register</a>
</form>
</div>
<?php endif;?>
<?php if(isset($_SESSION['user'])): ?>
<div align=center>
<br/><br/>

<?php
if(strtoupper($_SESSION['right'])=='ADMINISTRATOR')
	echo "<a href=list.php>管理入口</a><br>";
else
	echo "<a href=list.php>报名列表</a><br><a href=register.php>报名入口</a><br>";
?>
<form action="login.php" method="post">
<input type="hidden" name="op" value="logout">
<input type="submit" value="Logout">
</form>
</div>
<?php endif;?>
<?php print_bottom(); ?>