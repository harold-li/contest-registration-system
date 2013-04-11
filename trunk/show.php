<link rel="stylesheet" type="text/css" href="misc/style.css"/>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title><?php require_once "config.php";echo $head_title['index'] ?></title>
<?php
if(!isset($_SESSION['right'])||strtoupper($_SESSION['right'])!='ADMINISTRATOR')
{
	header('Location: ./');
	exit;
}
if(!isset($_GET['tid']) || $_GET['tid'] == NULL)
{
	echo "<font color=red>Invailed tid.</font>";
	exit;
}
$year = date('Y');
if(array_key_exists('y', $_GET))
	$year = $_GET['y'];
$status = array('<font color=green>Pending</font>','<font color=red>Accepted</font>','Rejected');
$tid = $_GET['tid'];
$query = "SELECT * FROM college_contest WHERE tid=%d AND year=%d";
$result = db_query($query,$tid,$year);
if($user = mysql_fetch_object($result))
{
	//print_r($user);
}
else
{
	//header('Location: '.$_SERVER['HTTP_REFERER']);
	echo "<font color=red>Server error.</font>";
	exit;
}
$created_time = date('Y-m-d H:i:s',$user->created);
$changed_time = date('Y-m-d H:i:s',$user->changed);
mysql_free_result($result);
?>
<fieldset id=user1>
<legend><b>队伍信息:</b></legend>
<table width=99%>
<tr bgcolor="#6589D1">
<th></th>
<th>队员一</th>
<?php if(isset($user->name2))echo '<th>队员二</th>'; ?>
<?php if(isset($user->name3))echo '<th>队员三</th>'; ?>
</tr>
<tr>
<td>姓名:</td><td><?php echo $user->name1; ?></td>
<?php if(isset($user->name2))echo '<td>'.$user->name2.'</td>' ?>
<?php if(isset($user->name3))echo '<td>'.$user->name3.'</td>' ?>
</tr>
<tr>
<td>学号:</td><td><?php echo $user->stu_id1; ?></td>
<?php if(isset($user->stu_id2))echo '<td>'.$user->stu_id2.'</td>'; ?>
<?php if(isset($user->stu_id3))echo '<td>'.$user->stu_id3.'</td>'; ?>
</tr>
<tr>
<td>学院:</td><td><?php echo $user->college1; ?></td>
<?php if(isset($user->college2))echo '<td>'.$user->college2.'</td>'; ?>
<?php if(isset($user->college3))echo '<td>'.$user->college3.'</td>'; ?>
</tr>
<tr>
<td>班级:</td><td><?php echo $user->class1; ?></td>
<?php if(isset($user->class2))echo '<td>'.$user->class2.'</td>'; ?>
<?php if(isset($user->class3))echo '<td>'.$user->class3.'</td>'; ?>
</tr>
<tr>
<td>性别:</td><td><?php echo $user->gender1=='female'?'女':'男'; ?></td>
<?php if(isset($user->gender2))echo $user->gender2=='female'?'<td>女</td>':'<td>男</td>'; ?>
<?php if(isset($user->gender3))echo $user->gender3=='female'?'<td>女</td>':'<td>男</td>'; ?>
</tr>
<tr>
<td>联系方式:</td><td><?php echo $user->contact1; ?></td>
<?php if(isset($user->name2))echo '<td>'.(isset($user->contact2)?$user->contact2:'').'</td>'; ?>
<?php if(isset($user->name2))echo '<td>'.(isset($user->contact3)?$user->contact3:'').'</td>'; ?>
</tr>
<tr><td>申请时间:</td><td colspan=3><?php echo $created_time; ?></td></tr>
<tr><td>修改时间:</td><td colspan=3><?php echo $changed_time; ?></td></tr>
<?php if(isset($user->user_id)): ?><tr><td>申请用户:</td><td colspan=3><?php echo "<a href=../oj/userstatus?user_id={$user->user_id}>{$user->user_id}</a>"; ?></td></tr><?php endif; ?>
<?php if(0&&isset($user->admin_user)): ?><tr><td>管理用户:</td><td colspan=3><?php echo $user->admin_user; ?></td></tr><?php endif; ?>
<?php if(0&&isset($user->admin_time)): ?><tr><td>管理时间:</td><td colspan=3><?php echo $user->admin_time; ?></td></tr><?php endif; ?>
<?php if(isset($user->history)): ?><tr><td>管理历史:</td><td colspan=3><pre><?php echo $user->history; ?></pre></td></tr><?php endif; ?>
</table>
</fieldset>
<?php if($year == date('Y')): ?>
<form class="niceform" action=admin.php method=post>
<fieldset>
<legend><b>管理:</b></legend>
<a href=register.php?tid=<?php echo $_GET['tid']; ?>>编辑</a><br/>
<input type=radio name=status id=status_0 value=0 <?php if($user->status==0)echo 'checked=checked'; ?>/><?php echo $status[0];?>
<input type=radio name=status id=status_1 value=1 <?php if($user->status==1)echo 'checked=checked'; ?>/><?php echo $status[1];?>
<input type=radio name=status id=status_2 value=2 <?php if($user->status==2)echo 'checked=checked'; ?>/><?php echo $status[2];?><br/>
评论:<br/>
<textarea rows="4" cols="65" name=comment id=comment title="如果Rejeced填上理由">
<?php if(isset($user->comment))echo $user->comment; ?>
</textarea>
<br/>
<input type=hidden name=tid value=<?php echo $tid; ?> />
<input type=submit value=提交 />
</fieldset>
</form>
<?php endif; ?>
<script src="misc/jquery-1.7.1.min.js" type="text/javascript" ></script>
<script type="text/javascript">
$(document).ready(function()
{
	$("#status_2").click(function()
	{
		$("#comment").show();
	});
});
</script>