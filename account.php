<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<link rel="stylesheet" type="text/css" href="misc/style.css"/>
<title><?php require_once "config.php";echo $head_title['index'] ?></title>
</head>
<div align=center><h2><a href=./><?php echo $page_title['index'] ?></a></h2></div>
<?php
/*
* 生成比赛用账号，用户名：team+两位年份+两位序号
* 8位密码，包括非0数字、大写字母、特殊符号@#&~^
* 昵称是队员姓名
* 另生成5个备用账号
* 账号保存在 account.txt
* 数据库语句保存在 sql.txt
*/
if(!isset($_SESSION['right'])||strtoupper($_SESSION['right'])!='ADMINISTRATOR')
{
	$_SESSION['message'][] = '无权操作';
	header('Location: list.php');
	exit;
}
if(empty($_POST))
{
	$_SESSION['message'][] = '错误访问';
	header('Location: list.php');
	exit;
}
$year = date('Y');
global $team_prefix;
//print_r($_POST);
if($_POST['team_prefix'] != NULL)
{
	$team_prefix = $_POST['team_prefix'];
}
if(!isset($_POST['update_db']))
	db_query("DELETE FROM users WHERE user_id LIKE '%s%%'",$team_prefix);

$password_len = 8;
if($_POST['password_len'] != NULL)
	$password_len = $_POST['password_len'];
if($password_len<6)
	$password_len = 6;
	
if(isset($_POST['password_ss']))
	$mask = array('@','#','&','~','^');
//为了方便辨别没有数字0
for($i=1;$i<=9;$i++)
	$mask[] = $i;
//for($i=0;$i<26;$i++)
//	$mask[] = chr($i+ord('a'));
for($i=0;$i<26;$i++)
	$mask[] = chr($i+ord('A'));
//print_r($mask);
if(!file_exists('download/'.$year))
	mkdir('download/'.$year, 0777);
$fp = fopen('download/'.$year.'/team.txt','w') or die("can't open file.");
fwrite($fp,"");
fwrite($fp,"User ID 	   Nick                 Password\r\n");
fwrite($fp,"------------------------------------------------\r\n");
fclose($fp);
$fp = fopen('download/'.$year.'/nick.txt','w') or die("can't open file.");
fwrite($fp,"");
fclose($fp);
if(isset($_POST['account_file']))
{
	$fp = fopen('download/'.$year.'/account.txt','w') or die("can't open file.");
	fwrite($fp,"User ID \tPassword\r\n");
	fwrite($fp,"------------------------\r\n");
	fclose($fp);
}
if(isset($_POST['sql_file']))
{
	$fp = fopen('download/'.$year.'/sql.txt','w') or die("can't open file.");
	fwrite($fp,"");
	fclose($fp);
}
$contest_id = $_POST['contest_id'];
if($contest_id != NULL)
{
	db_query("DELETE FROM private WHERE contest_id=%d",$contest_id);
}
$num = 1;
/***** specail account *****/
foreach ($sp_team as $team)
{
	$user_id = $team_prefix.sprintf("%02d",$num++);
	$nick = $team[0];
	$nick = phpcharset($nick,'UTF-8');
	$password = "";
	for($i=0;$i<$password_len;$i++)
		$password .= $mask[rand()%count($mask)];
	account($user_id,$nick,$password);

	if($team[1] != '')
	{
		$nick = $team[1];
		db_query("UPDATE users SET nick='%s' WHERE user_id='%s'",$nick,$user_id);
		db_query("DELETE FROM privilege WHERE user_id='%s' AND rightstr='title'",$user_id);
		db_query("INSERT INTO privilege (user_id,rightstr) VALUES ('%s','title')",$user_id);
	}
}
/***** specail account *****/
$year = date('Y');
$query = "SELECT name1,name2,name3 FROM college_contest WHERE status=1 AND year=%d";
$result = db_query($query,$year);
while($user = mysql_fetch_object($result))
{
	$user_id = $team_prefix.sprintf("%02d",$num);
	$password = "";
	for($i=0;$i<$password_len;$i++)
		$password .= $mask[rand()%count($mask)];
	$nick = $user->name1;
	if(isset($user->name2))
		$nick .= " ".$user->name2;
	if(isset($user->name3))
		$nick .= " ".$user->name3;
	if(!isset($nick))
		$nick = $user_id;
	account($user_id,$nick,$password);
	$num++;
}
mysql_free_result($result);

for($i=0;$i<$_POST['backup_num'];$i++)//生成备用账号
{
	$user_id = $team_prefix.sprintf("%02d",$num);
	$password = "";
	for($j=0;$j<$password_len;$j++)
		$password .= $mask[rand()%count($mask)];
	$nick = $user_id;
	account($user_id,$nick,$password);
	$num++;
}
$_SESSION['message'][] = '生成账号成功';
header('Location: list.php');
/*echo "<div align=center>";
if(isset($_POST['account_file']) || isset($_POST['sql_file']))
{
	echo "右键另存为<br/>\n";
}
echo "<a href=".urlencode("team.txt").">team.txt</a><br/>\n";
echo "<a href=".urlencode("nick.txt").">nick.txt</a><br/>\n";
if(isset($_POST['account_file']))
{
	echo "<a href=".urlencode("account.txt").">account.txt</a><br/>\n";
}
if(isset($_POST['sql_file']))
{
	echo "<a href=".urlencode("sql.txt").">sql.txt</a><br/>\n";
	
}
echo "</div>";*/
/*
* 生成一个账号插入数据库，并保存到文件
* 设置$contest_id参数，将账号插入到私有比赛中
*/
function account($user_id,$nick,$password)
{
	$year = date('Y');
	//$nick = phpcharset($nick,'UTF-8');
	$contest_id = $_POST['contest_id'];
	//echo "$user_id-$nick:$password\n";
	$time = date("Y-m-d H:i:s" ,time());
	$query = "SELECT user_id FROM users WHERE user_id='%s'";
	$result = db_query($query,$user_id);
	if($team = mysql_fetch_object($result))
	{
		$query = NULL;
		if(isset($_POST['update_db']))
			$query = "UPDATE users SET nick='".mysql_escape_string($nick).
				"',password=encode('$password','PWDforJO2005'),reg_time='$time' WHERE user_id='".mysql_escape_string($user_id)."'";
	}
	else
	{
		$query = "INSERT INTO users (user_id,password,nick,reg_time) VALUES ('".mysql_escape_string($user_id)."',encode('$password','PWDforJO2005'),'$nick','$time')";
	}
	//echo $query."\n";
	if($query != NULL)
		db_query($query) or die("mysql error.");
	$fp = fopen('download/'.$year.'/team.txt','a') or die("can't open file.");
	fwrite($fp,$user_id."\t".sprintf("%-21s",$nick)."\t".$password."\r\n");
	fclose($fp);
	$fp = fopen('download/'.$year.'/nick.txt','a') or die("can't open file.");
	fwrite($fp,$nick."\r\n");
	fclose($fp);
	if(isset($_POST['account_file']))
	{
		$fp = fopen('download/'.$year.'/account.txt','a') or die("can't open file.");
		fwrite($fp,$user_id."\t".$password."\r\n");
		fclose($fp);
	}
	if(isset($_POST['sql_file']))
	{
		$fp = fopen('download/'.$year.'/sql.txt','a') or die("can't open file.");
		fwrite($fp,"$query\r\n");
		fclose($fp);
	}
	if($contest_id != NULL)
	{
		$query = "INSERT INTO private (user_id,contest_id) VALUES ('$user_id','$contest_id')";
		db_query($query);
		//echo "$query\r\n";
		$fp = fopen('download/'.$year.'/sql.txt','a') or die("can't open file.");
		fwrite($fp,"$query\r\n");
		fclose($fp);
	}
	return true;
}
?>
