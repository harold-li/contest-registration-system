<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<script src="misc/jquery-1.7.1.min.js" type="text/javascript" ></script>
<script src="misc/jquery.lwtCountdown-1.0.js" type="text/javascript" ></script>
<script src="misc/register_contest.js" type="text/javascript" ></script>
<script src="misc/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript" ></script>
<link rel="stylesheet" type="text/css" href="misc/jquery-ui-1.8.18.custom/css/start/jquery-ui-1.8.18.custom.css"/>
<link rel="stylesheet" type="text/css" href="misc/style.css"/>
<!--[if lt IE 9]>
  <script type="text/javascript" src="misc/lightview-3.0.6/js/excanvas/excanvas.js"></script>
<![endif]-->
<script type="text/javascript" src="misc/lightview-3.0.6/js/spinners/spinners.js"></script>
<script type="text/javascript" src="misc/lightview-3.0.6/js/lightview/lightview.js"></script>
<link rel="stylesheet" type="text/css" href="misc/lightview-3.0.6/css/lightview/lightview.css"/>
<title><?php require_once "config.php";echo $head_title['list'] ?></title>
</head>

<?php show_message();view_count(); ?>
<div align=center><h2><a href=./><?php echo $page_title['list'] ?></a></h2></div>
<div class=example_pane>
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
</div>

<br>
<div align="center">
<?php
$year = date('Y');
if(array_key_exists('y', $_GET))
	$year = $_GET['y'];
$query = "SELECT * FROM college_contest WHERE year=%d ORDER BY tid";
$result = db_query($query,$year);
?>
<?php if(isset($_SESSION['user'])): ?>
<table id="register-entry">
<td width=40%>
<div>
<?php
//if(!isset($_SESSION['right'])||strtoupper($_SESSION['right'])!='ADMINISTRATOR')
	echo "<a href=register.php>报名入口</a><br/>";
?>
User:<a href=../oj/userstatus?user_id=<?php echo $_SESSION['user']; ?>><?php echo $_SESSION['user']; ?></a>
<form action="login.php" method="post" style="margin-bottom:0">
<input type="hidden" value="logout" name="op">
<input type="submit" value="Logout">
<input type="hidden" name="op" value="logout">
</form>
</div>
</td>
<td align="center" width=20%>
<?php
if(isset($_SESSION['right']) && strtoupper($_SESSION['right'])=='ADMINISTRATOR'/* && $year==date('Y')*/)
{
	$ajax = "";
	if($year==date('Y'))
		$ajax = "<a href=account_setting.php class=lightview data-lightview-caption='如不能正常显示<a href=account_setting.php>点击这里</a>' data-lightview-type=ajax data-lightview-options=\"skin:'mac',radius:0,padding:0 \">生成账号</a>&nbsp;&nbsp;";
	$ajax .= "<a href=download.php?y=$year class=lightview data-lightview-caption='如不能正常显示<a href=download.php?y=$year>点击这里</a>' data-lightview-type=ajax data-lightview-options=\"skin:'mac',radius:0,padding:0 \">文件下载</a>";
	echo $ajax;
}
?>
</td>
<td width=40%></td>
<table>
<?php endif; ?>
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
<?php 
endif; 
?>
<div id="team-info">
<?php
$status = array('<font color=green>Pending</font>','<font color=red>Accepted</font>','Rejected');
$results = db_query("SELECT status,COUNT(*) AS count FROM college_contest WHERE year=%d GROUP BY status", $year);
$count = array(0,0,0);
$total = 0;
while($item = mysql_fetch_object($results))
{
	$count[$item->status] = $item->count;
}
foreach($count as $key => $value)
{
	echo $status[$key].":".$value." ";
	$total += $value;
}
echo "Total:$total";
if(isset($_SESSION['right']) && strtoupper($_SESSION['right'])=='ADMINISTRATOR')
{
	$results = db_query("SELECT COUNT(*) AS count FROM college_contest WHERE gender1!='male' AND (gender2!='male' OR gender2 IS NULL) AND (gender3!='male' OR gender3 IS NULL) AND status!=2 AND year=%d",$year);
	$item = mysql_fetch_object($results);
	echo " <font color=red>Girls Team</font>:{$item->count} ";

	$results = db_query("SELECT COUNT(*) AS count FROM college_contest WHERE SUBSTRING(stu_id1,1,4)='%s' AND (SUBSTRING(stu_id2,1,4)='%s' OR stu_id2 IS NULL) AND (SUBSTRING(stu_id3,1,4)='%s' OR stu_id3 IS NULL) AND status!=2 AND year=%d",$year-1, $year-1, $year-1, $year);
	$item = mysql_fetch_object($results);
	echo " <font color=red>New-comer Team</font>:{$item->count} ";
}
?>
</div>
<table id="team-list" cellspacing="0" cellpadding="0" bordercolor="#FFFFFF" border="1" style="border-collapse: collapse">
<tr bgcolor="#6589D1">
<th><th>
<th colspan=5>队员一<th>
<th colspan=5>队员二<th>
<th colspan=5>队员三<th>
<?php
if(isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR')
	echo "<th><th>";
?>
</tr>
<tr bgcolor="#6589D1">
<th>No.</th>
<th>姓名</th>
<th>学号</th>
<th>学院</th>
<th>班级</th>
<th>性别</th>
<th>联系方式</th>
<th>姓名</th>
<th>学号</th>
<th>学院</th>
<th>班级</th>
<th>性别</th>
<th>联系方式</th>
<th>姓名</th>
<th>学号</th>
<th>学院</th>
<th>班级</th>
<th>性别</th>
<th>联系方式</th>
<th>状态</th>
<?php if(isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR'): ?>
<th>管理</th>
<?php endif; ?>
</tr>
<?php
$status = array('<font color=green>Pending</font>','<font color=red>Accepted</font>','Rejected');
$fields = array('name','stu_id','college','class','gender','contact');
$num = 1;
while ($row = mysql_fetch_array($result))
{
	$add = " bgcolor=#F00506";
	$tid = $row[0];
	for($i=1;$i<=3;$i++)
		if($row['gender'.$i] == 'male')
			$add = "";
	echo "<tr class=list><td $add nowrap='nowrap'>".$num."</td>";
	for($i=1;$i<=3;$i++)
	{
		foreach($fields as $value)
		{
			if($row[$value.$i] == 'male')
				$row[$value.$i] = '男';
			else if($row[$value.$i] == 'female')
				$row[$value.$i] = '女';
			if($value == 'contact' && 
				!(isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR')
				&& !(isset($_SESSION['user'])&&$_SESSION['user']==$row['user_id']))
				$row[$value.$i] = "";
			echo "<td $add nowrap='nowrap'>".$row[$value.$i]."</td>";
		}
	}
	$admin_str = "";
	if($row['status'] != 0)
		$admin_str .= "<a href=admin.php?tid={$row[0]}&op=0>Pending</a>\n";
	if($row['status'] != 1)
		$admin_str .= "<a href=admin.php?tid={$row[0]}&op=1>Accepted</a>\n";
	if($row['status'] != 2)
		$admin_str .= "<a href=admin.php?tid={$row[0]}&op=2>Rejected</a>\n";
	$created_time = date('Y-m-d H:i:s',$row['created']);
	$changed_time = date('Y-m-d H:i:s',$row['changed']);
	$ajax = "<a href=show.php class=lightview data-lightview-caption='如不能正常显示<a href=show.php?tid=$tid&y=$year>点击这里</a>' data-lightview-type=ajax data-lightview-options=\"ajax:{data:'tid=$tid&y=$year'},skin:'mac',radius:0,padding:0 \">管理</a>";
	$str = "<td nowrap='nowrap'>{$status[$row['status']]}</td>";
	if(isset($_SESSION['right'])&&strtoupper($_SESSION['right'])=='ADMINISTRATOR')
		$str .= "<td nowrap='nowrap'>$ajax</td>";
	$str .= "</tr>\n";
	echo $str;
	$num++;
}
?>
</table>
</div>
<?php print_bottom(); ?>