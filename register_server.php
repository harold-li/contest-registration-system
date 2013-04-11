<?php
require_once "config.php";
$year = date('Y');
if(isset($_SESSION['right']) && strtoupper($_SESSION['right'])=='ADMINISTRATOR' && !empty($_POST['tid']))
{
	$query = "SELECT * FROM college_contest WHERE tid=%d AND year=%d";
	$result = db_query($query,$_POST['tid'],$year);
}
else
{
	$query = "SELECT * FROM college_contest WHERE user_id='%s' AND year=%d";
	$result = db_query($query,$_POST['user_id'],$year);
}
$fields = array('name','stu_id','college','class','gender','contact');
if ($user = mysql_fetch_object($result))
{
	mysql_free_result($result);
	$query = "UPDATE college_contest SET ";
	for ($i=1;$i<=$_POST['user_count'];$i++)
	{
		foreach ($fields as $field)
		{
			$query .= $field.$i."='".mysql_escape_string($_POST[$field.$i])."',";
		}
	}
	for ($i=$_POST['user_count']+1;$i<=3;$i++)
	{
		foreach ($fields as $field)
		{
			$query .= $field.$i."=NULL,";
		}
	}
	if(isset($_SESSION['right']) && strtoupper($_SESSION['right'])=='ADMINISTRATOR' && !empty($_POST['tid']))
	{
		$query .= "notice=%d,changed=".time()." WHERE tid=%d AND year=%d";
		db_query($query,$_POST['notice'],$_POST['tid'],$year) or die("can't update record.");
		
		$admin_time = date('Y-m-d H:i:s');
		$history = $admin_time." ".$_SESSION['user']." update the information.\n";
		$history .= $user->history;
		$query = "UPDATE college_contest SET history='%s' WHERE tid=%d AND year=%d";
		db_query($query,$history,$_POST['tid'],$year);
	}
	else
	{
		$query .= "notice=%d,status=0,changed=".time()." WHERE user_id='%s' AND year=%d";
		db_query($query,$_POST['notice'],$_POST['user_id'],$year) or die("can't update record.");
	}
	$_SESSION['message'][] = '更新成功';
}
else
{
	mysql_free_result($result);
	$query = "INSERT INTO college_contest ";
	
	$field = "(user_id,";
	$value = "('".$_POST['user_id']."',";
	for ($i=1;$i<=$_POST['user_count'];$i++)
	{
		foreach ($fields as $val)
		{
			$field .= $val.$i.",";
			$value .= "'".mysql_escape_string($_POST[$val.$i])."',";
		}
	}
	$field .= "notice,created,changed,year)";
	$value .= "%d,".time().",".time().",".$year.")";

	$query .= $field." values ".$value;
	//echo $query;
	db_query($query,$_POST['notice']) or die("can't insert record.");
	$_SESSION['message'][] = '报名成功';
}
header('Location: '.$_SERVER['HTTP_REFERER']);
exit;
?>