<?php
require_once "config.php";
if($_POST['op'] == 'login')
{
	$query = "SELECT u.user_id,rightstr FROM users u LEFT JOIN privilege p ON p.user_id=u.user_id WHERE u.user_id='%s' AND password=ENCODE('%s','PWDforJO2005') AND UPPER(u.defunct) = 'N'";
	$result = db_query($query,$_POST['user_id'],$_POST['password']);
	if ($user = mysql_fetch_object($result))
	{
		$_SESSION['user'] = $user->user_id;
		//$_SESSION['message'][] = $user->user_id.":登录成功";
		/*$_SESSION['nick'] = $user->nick;
		$_SESSION['school'] = $user->school;
		$_SESSION['email'] = $user->email;
		$_SESSION['qq'] = $user->qq;*/
		$_SESSION['right'] = $user->rightstr;
	}
	else
	{
		$_SESSION['message'][] = '登录失败';
	}
	mysql_free_result($result);
}
else if($_POST['op'] == 'logout')
{
	unset($_SESSION['user']);
	/*unset($_SESSION['nick']);
	unset($_SESSION['school']);
	unset($_SESSION['email']);
	unset($_SESSION['qq']);*/
	unset($_SESSION['right']);
	session_destroy(void);
}
header('Location: '.$_SERVER['HTTP_REFERER']);
exit;
?>
