<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<script src="misc/jquery-1.7.1.min.js" type="text/javascript" ></script>
<script src="misc/jquery.lwtCountdown-1.0.js" type="text/javascript" ></script>
<script src="misc/register_contest.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="misc/style.css"/>
<title><?php require_once "config.php";echo $head_title['reg'] ?></title>
</head>

<?php show_message();view_count(); ?>
<div align=center><h2><a href=./><?php echo $page_title['reg'] ?></a></h2></div>
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
<?php
if(!isset($_SESSION['user']))
{
	header('Location: ./');
	exit;
}
?>
<table  width=99%>
<tr>
<td width=33%></td>
<td width=33%>
<?php if(isset($_SESSION['user'])): ?>
User:<a href=../oj/userstatus?user_id=<?php echo $_SESSION['user']; ?>><?php echo $_SESSION['user']; ?></a><br/>
<a href=list.php>报名列表</a><br/><br/>
<form action=login.php method=post>
<input type=hidden name=op value=logout>
<input type=submit value=Logout>
</form>
<?php
/***** register end *****/
if(time()+3600*12 > contest_date(FALSE))
{
	echo "报名已经结束。\n";
	return;
}
/***** register end *****/
$year = date('Y');
if(isset($_SESSION['right']) && strtoupper($_SESSION['right'])=='ADMINISTRATOR' && !empty($_GET['tid']))
{
	$query = "SELECT * FROM college_contest WHERE tid=%d AND year=%d";
	$result = db_query($query,$_GET['tid'],$year);
}
else
{
	$query = "SELECT * FROM college_contest WHERE user_id='%s' AND year=%d";
	$result = db_query($query,$_SESSION['user'],$year);
}
$registered = FALSE;
$status = array('<font color=green>Pending</font>','<font color=red>Accepted</font>','Rejected');
if ($user = mysql_fetch_object($result))
{
	$registered = TRUE;
}
else
{
	if(!empty($_GET['tid']))
	{
		$_SESSION['message'][] = 'Error tid';
		header('Location: ./');
		exit;
	}
	$user = new stdClass;
}
mysql_free_result($result);
if(isset($_SESSION['right']) && strtoupper($_SESSION['right'])=='ADMINISTRATOR' && !empty($_GET['tid']))
{
	echo "Register User:<a href=../oj/userstatus?user_id={$user->user_id}>{$user->user_id}</a><br/>";
}
?>
<?php if(isset($user->status))echo "Status: ".$status[$user->status];?>
</td>
<td width=33%></td>
</tr>
<tr>
<td width=33%></td>
<td width=33%>
<form class="niceform" action=register_server.php onsubmit="return validate_form(this)"  method=post>
接收通知:<input type=radio name=notice value=1 <?php if(!isset($user->notice)||$user->notice==1)echo 'checked=checked'; ?> />是
<input type=radio name=notice value=0 <?php if(isset($user->notice)&&$user->notice!=1)echo 'checked=checked'; ?> />否<br/>
<fieldset id=user1>
<legend><b>队员一:</b></legend>
姓名: <input type=text name=name1 id=name1 value=<?php if(isset($user->name1))echo $user->name1; ?>>*</br>
学号: <input type=text name=stu_id1 value=<?php if(isset($user->stu_id1))echo $user->stu_id1; ?>>*</br>
学院: <select name=college1><?php echo colleges($user->college1); ?></select>*<br/>
班级: <input type=text name=class1 value=<?php if(isset($user->class1))echo $user->class1; ?>>*</br>
性别: <input type=radio name=gender1 value=male <?php if(!isset($user->gender1)||$user->gender1=='male'||$user->gender1==NULL)echo 'checked=checked'; ?>>男<input type=radio name=gender1 value=female <?php if(isset($user->gender1)&&$user->gender1=='female')echo 'checked=checked'; ?>>女</br>
联系方式: <input type=text name=contact1 size=20 value=<?php if(isset($user->contact1))echo $user->contact1; ?>>*</br>
</fieldset>

<fieldset id=user2 <?php if(!isset($user->name2)||$user->name2==NULL)echo 'style="display:none"';?>>
<legend><b>队员二:</b></legend>
姓名: <input type=text name=name2 id=name2 value=<?php if(isset($user->name2))echo $user->name2; ?>>*</br>
学号: <input type=text name=stu_id2 value=<?php if(isset($user->stu_id2))echo $user->stu_id2; ?>>*</br>
学院: <select name=college2><?php echo colleges($user->college2); ?></select>*<br/>
班级: <input type=text name=class2 value=<?php if(isset($user->class2))echo $user->class2; ?>>*</br>
性别: <input type=radio name=gender2 value=male <?php if(!isset($user->gender2)||$user->gender2=='male'||$user->gender2==NULL)echo 'checked=checked'; ?>>男<input type=radio name=gender2 value=female <?php if(isset($user->gender2)&&$user->gender2=='female')echo 'checked=checked'; ?>>女</br>
联系方式: <input type=text name=contact2 size=20 value=<?php if(isset($user->contact2))echo $user->contact2; ?>></br>
<?php if(!isset($user->status)||$user->status != 1 || (isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR')): ?><div id=div_del2><button type=button id=del2>删除队员</button></div><?php endif;?>
</fieldset>

<fieldset id=user3 <?php if(!isset($user->name3)||$user->name3==NULL)echo 'style="display:none"';?>>
<legend><b>队员三:</b></legend>
姓名: <input type=text name=name3 id=name3 value=<?php if(isset($user->name3))echo $user->name3; ?>>*</br>
学号: <input type=text name=stu_id3 value=<?php if(isset($user->stu_id3))echo $user->stu_id3; ?>>*</br>
学院: <select name=college3><?php echo colleges($user->college3); ?></select>*<br/>
班级: <input type=text name=class3 value=<?php if(isset($user->class3))echo $user->class3; ?>>*</br>
性别: <input type=radio name=gender3 value=male <?php if(!isset($user->gender3)||$user->gender3=='male'||$user->gender3==NULL)echo 'checked=checked'; ?>>男<input type=radio name=gender3 value=female <?php if(isset($user->gender3)&&$user->gender3=='female')echo 'checked=checked'; ?>>女</br>
联系方式: <input type=text name=contact3 size=20 value=<?php if(isset($user->contact3))echo $user->contact3; ?>></br>
<?php if(!isset($user->status)||$user->status != 1 || (isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR')): ?><div id=div_del3><button type=button id=del3>删除队员</button></div><?php endif;?>
</fieldset>

<?php if(!isset($user->status)||$user->status != 1 || (isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR')): ?>
<div id=div_add_user><button type=button id=add_user>添加队员</button><br/></div>
<?php endif;?>

<?php if(!isset($user->status)||$user->status != 1 || (isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR')): ?>
<input type=hidden name=user_id id=user_id value=<?php echo $_SESSION['user']; ?> />
<input type=hidden name=user_count id=user_count value=1 />

<input type=submit value=提交 />
<input type=reset value=重置 />
<?php endif;?>
<?php
if(isset($_SESSION['right']) && strtoupper($_SESSION['right'])=='ADMINISTRATOR' && !empty($_GET['tid']))
{
	echo "<input type=hidden name=tid id=tid value={$_GET['tid']} />";
}
?>
</form>

<?php if(isset($user->comment)&&$user->comment!=NULL): ?>
<fieldset>
<legend><b>附加信息:</b></legend>
<textarea rows="5" cols="50" name=comment>
<?php echo $user->comment; ?>
</textarea>
</fieldset>
<?php endif;?>

</td>
<td width=33%></td>
</tr>
</table>
<?php endif;?>
<br/>
</div>
<?php print_bottom(); ?>