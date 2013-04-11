<pre>
<?php
require_once "config.php";
if(!isset($_SESSION['right'])||strtoupper($_SESSION['right'])!='ADMINISTRATOR')
{
	$_SESSION['message'][] = '无权操作';
	header('Location: ./');
	exit;
}
$year = date('Y');
$status = array('<font color=green>Pending</font>','<font color=red>Accepted</font>','Rejected');
$status1 = array('Pending','Accepted','Rejected');
$tid = $_POST['tid'];
$query = "SELECT user_id,status,history,notice,name1,name2,name3 FROM college_contest WHERE tid=%d AND year=%d";
$result = db_query($query,$tid,$year);
if($info = mysql_fetch_object($result))
{
	//print_r($info);
	$admin_time = date('Y-m-d H:i:s',time());
	if($info->status != $_POST['status'])
	{
		$history = $admin_time." ".$_SESSION['user'].":".$status[$info->status]."-->".$status[$_POST['status']]."\n";
		if($info->notice)
		{
			//$title = "Information Engineering College Sixth ACM-ICPC Programming Contest Registration notification";
			//$content = "Your registration request had updated by ".$_SESSION['user'].", set ".$status1[$_POST['status']]." status.\nMore information please view Registration System.";
			$content = str_replace("__ADMIN__", $_SESSION['user'], $content);
			$content = str_replace("__STATUS__", $status1[$_POST['status']], $content);
			$mid = mysql_fetch_object(db_query("SELECT MAX(mail_id) AS mid FROM mail"))->mid + 1;
			//echo "mid:$mid.\n";
			//db_query("set names utf8");
			$query = "INSERT INTO mail (mail_id,from_user,to_user,title,content,in_date) VALUES (%d,'system','%s','%s','%s','%s')";
			//echo "$query\n";
			db_query($query,$mid,$info->user_id,$title,$content,$admin_time) or die("can't insert record.");
			if($SEND_MAIL)
			{
				$to = db_result(db_query("SELECT email FROM users WHERE user_id = '%s'",$info->user_id));
				if(!empty($to))
				{
					$name = $info->name1;
					if(!empty($info->name2))
						$name .= ",".$info->name2;
					if(!empty($info->name3))
						$name .= ",".$info->name3;
					
					$str = "你的报名请求被管理员<a href=http://220.166.52.162/oj/userstatus?user_id={$_SESSION['user']}>{$_SESSION['user']}</a>";
					if($_POST['status'] == 1)
						$str .= "接受。<br/>如果需要更改报名信息请联系管理员。";
					if($_POST['status'] == 2)
						$str .= "拒绝。";
					if(!empty($_POST['comment']))
						$str .= "<br/>附加信息：".$_POST['comment'];
					$str .= "<br/>";
					if($_POST['status'] == 0)
						$str = "";
					
					$body = str_replace('__MESSAGE__', $str, $body);
					$body = str_replace('__TIME__', $admin_time, $body);
					send_mail($name,$to,$subject,$body);
				}
			}
		}
	}
	$history .= $info->history;
	$query = "UPDATE college_contest SET status=%d,comment='%s',
			history='%s' WHERE tid=%d AND year=%d";
	//echo $query;
	db_query($query,$_POST['status'],$_POST['comment'],$history,$tid,$year);
}
mysql_free_result($result);
header('Location: list.php');
exit;
?>
</pre>