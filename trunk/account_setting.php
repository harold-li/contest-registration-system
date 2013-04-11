<script src="misc/jquery-1.7.1.min.js" type="text/javascript" ></script>
<script src="misc/jquery.lwtCountdown-1.0.js" type="text/javascript" ></script>
<script src="misc/register_contest.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="misc/style.css"/>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title><?php require_once "config.php";echo $head_title['index'] ?></title>
<?php
if(!isset($_SESSION['right'])||strtoupper($_SESSION['right'])!='ADMINISTRATOR')
{
	$_SESSION['message'][] = '无权操作';
	header('Location: list.php');
	exit;
}
?>
<form action="account.php" method="post">
<fieldset id="account_setting">
<legend><b>账号生成设置:</b></legend>
<input type="checkbox" name="update_db" checked="checked" />更新数据库（而不是重新生成）<br />
<input type="checkbox" name="account_file" checked="checked" />生成账号文件<br />
<input type="checkbox" name="sql_file" checked="checked" />生成SQL文件<br />
<input type="checkbox" name="password_ss" checked="checked" />密码包含特殊符号<br />
账号前缀:<input type="text" name="team_prefix" value="team13"><br />
备用账号数量:<input type="text" name="backup_num" value=5><br />
比赛ID:<input type="text" name="contest_id"><br />
密码位数:<input type="text" name="password_len" value=8><br />
<font color="red">*</font>密码不会包含数字0<br>
<font color="red">*</font>注意有特殊账号<br>
</fieldset>
<input type="submit" value="生成" />
<input type="reset" value="重置" />
</form>