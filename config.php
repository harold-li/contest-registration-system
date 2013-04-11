<?php
/*
* 配置信息及常用函数
*/
require_once "mysql.php";
session_start();
date_default_timezone_set('Asia/Chongqing');
$team_prefix = 'team12';
$quert_count = 0;
global $db_url;
db_init();

$sp_team = array(
    array('Nicholas', '<font color=red><b>Nicholas</b></font>'),
    array('Minya Konka', '<font color=red><b>Minya Konka</b></font>'),
    array('友情参赛队*', ''),
  );
$head_title = array(
  'index'  => '西南科技大学第八届ACM-ICPC程序设计竞赛报名',
  'list'   => '西南科技大学第八届ACM-ICPC程序设计竞赛报名列表',
  'reg'    => '西南科技大学第八届ACM-ICPC程序设计竞赛报名',
  'download'    => '西南科技大学第八届ACM-ICPC程序设计竞赛报名',
	);
$page_title = array(
  'index'  => '西南科技大学第八届ACM-ICPC程序设计竞赛报名系统',
  'list'   => '西南科技大学第八届ACM-ICPC程序设计竞赛报名列表',
  'reg'    => '西南科技大学第八届ACM-ICPC程序设计竞赛报名系统',
  'download'    => '西南科技大学第八届ACM-ICPC程序设计竞赛报名系统',
	);
/*
 * Email subject and body
 */
$SEND_MAIL = FALSE;
$subject = "西南科技大学第八届ACM-ICPC程序设计竞赛报名通知";
$body = <<<STR
<center><h2>西南科技大学第八届ACM-ICPC程序设计竞赛报名</h2></center><br/>
你的报名请求状态更新，详细情况请登录报名系统查看。<br/>
__MESSAGE__<br/>
任何疑问与建议请联系我们。<br/>
报名系统：<a href='http://220.166.52.162/contest'>http://220.166.52.162/contest</a><br/>
OJ系统：<a href='http://220.166.52.162/oj'>http://220.166.52.162/oj</a><br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://202.115.161.162/oj'>http://202.115.161.162/oj</a><br/>
<p align=right>__TIME__<br/>信息工程学院ACM-ICPC集训队<br/></p>
STR;
$title = "西南科技大学第八届ACM-ICPC程序设计竞赛报名通知";
$content = "你的报名请求状态更新：管理员__ADMIN__设置为 __STATUS__ 状态。\n详细情况请登录报名系统查看。";

/*
* 一次性显示错误信息
*/
function show_message()
{
if(isset($_SESSION['message']))
{
	echo '<div class="messages error"><ul>';
	foreach($_SESSION['message'] as $value)
	{
		echo "<li>$value</li>\n";
	}
	echo '</ul></div>';
	unset($_SESSION['message']);
}
}

/*
* 设置数据库信息
*/
function db_init()
{
	global $db_url;
    if(getenv("VCAP_SERVICES"))
    {
        $services_json = json_decode(getenv("VCAP_SERVICES"),true);
        $mysql_config = $services_json["mysql-5.1"][0]["credentials"];
        $db_user = $mysql_config["username"];
        $db_password = $mysql_config["password"];
        $hostname = $mysql_config["hostname"];
        $port = $mysql_config["port"];
        $db_name = $mysql_config["name"];
        $db_server = "$hostname:$port";
    }
    else
    {
        $db_server = "localhost:33066";
        $db_user = "root";
        $db_password = "123456";
        $db_name = "poj-power";
    }
	
	$db_url = implode(';',array($db_server,$db_user,$db_password,$db_name));
}

/*
 * 设置倒计时的比赛开始时间在register_contest.js文件中。
 */
function contest_date($str = TRUE)
{
  $date = "2013.4.27 13:00-18:00";

  if($str)
    return $date;
  return strtotime(str_replace('.', '-', substr($date, 0, 14)));
}
/*
 * 设置比赛信息
 */
function contest_info()
{
	$info = array(
		'主办单位' => '信息工程学院',
		'承办单位' => '信息工程学院ACM集训队',
		'负责人'   => '谭老师、陈老师、陈县',
		//'命题组'   => '待定',
		//'工作人员' => '待定',
		//'志愿者'   => '待定',
		'技术支持' => '<a href=../oj/userstatus?user_id=power721>power721</a>',
    //在js/register_contest.js中修改比赛开始时间
		'比赛时间' => '热身赛：2013.4.27 09:00-12:00<br/>正式赛：'.contest_date(),
		'比赛地点' => '东六B306',
		'参赛对象' => '全校同学',
		'参赛形式' => '组队赛，2-3人为一队',
		'比赛平台' => '<a href=http://220.166.52.162/oj/>信息学院OJ平台</a>&nbsp;&nbsp;<a href=http://202.115.161.162/oj/>教育网</a>',
		'奖项设置' => '特等奖、一等奖、二等奖、三等奖<br/>优秀新人奖、最佳女队奖、神秘奖项',
		'比赛规则' => '热身赛为自由练习，不计入最后排名。<br/>正赛比赛时间为5个小时，每支队伍只能共用一台电脑。<br/>比赛期间可以查看任何纸质材料，<br/>但不允许使用任何电子资料<br/>（如：优盘、手机、快译通、MP3等）。',
		'题目形式' => '题目为8-12道，为全英文描述。<br/>（温馨提示:英语不好的同学记得带上字典）',
		'联系方式' => '刘寅  TEL:15280991125 QQ:26085107<br/>吴禄高 TEL:15280991419 QQ:1031876965'
		//'其他信息' => '',
	);
	return $info;
}

/*
* 设置学院信息
*/
function colleges($selected)
{
	$colleges = array(
		"信息工程学院",
		"计算机科学与技术学院",
		"国防科技学院",
		"理学院",
		"经济管理学院",
		"制造科学与工程学院",
		"土木工程与建筑学院",
		"环境与资源学院",
		"材料科学与工程学院",
		"生命科学与工程学院",
		"文学与艺术学院",
		"法学院",
		"政治学院",
		"体育学科部",
		"外国语学院",
		"成人教育学院",
		"网络教育学院",
		"应用技术学院",
		"拉美研究院",
		"其它学院"
	);
	$str = '';
	foreach ($colleges as $college)
	{
		$add = $college == $selected ? ' selected="selected"' : '';
		$str .= "<option value=$college".$add.">$college</option>\n";
	}
	return $str;
}

/*
* 提示信息函数
* 暂时未使用
*/
function set_message($message = NULL, $type = 'status', $repeat = TRUE)
{
	if ($message)
	{
		if (!isset($_SESSION['messages']))
		{
			$_SESSION['messages'] = array();
		}
		if (!isset($_SESSION['messages'][$type]))
		{
			$_SESSION['messages'][$type] = array();
		}
		if ($repeat || !in_array($message, $_SESSION['messages'][$type]))
		{
			$_SESSION['messages'][$type][] = $message;
		}
	}
	// messages not set when DB connection fails
	return isset($_SESSION['messages']) ? $_SESSION['messages'] : NULL;
}

/*
 *
 */
function view_count($inc = TRUE)
{
    $cnt = file_get_contents('count.txt');
    if($inc)
    { 
      $cnt = (int)$cnt+1;
      file_put_contents('count.txt', $cnt);
    }
    return $cnt;
}

function print_bottom()
{
  global $quert_count;
  echo "<div class=footer align=center>";
  echo "<div id=\"divPageLoadTime\"></div>";
  echo "All Copyright Reserved 2012-2014 <a href=\"mailto:power721@163.com\">power721</a><br>\n
Any problem, Please Contact <a href=\"mailto:swust_acm@163.com\">Administrator</a><br>\n";
  echo "</div>";
  echo "<script language=\"javascript\" type=\"text/javascript\"> var endT=new Date();var endTime=endT.getTime();var spTime=(endTime-beginTime);document.getElementById('divPageLoadTime').innerHTML='<a href=\"../oj\">Power OJ</a>|Excute:'+spTime+'ms|Query:".$quert_count."|View:".view_count(FALSE)."';</script>";
}

function send_mail($name,$to,$subject = "",$body = "")
{
    error_reporting(E_STRICT);
    //date_default_timezone_set("Asia/Shanghai");	//设定时区东八区
    require_once("class.phpmailer.php");
    include("class.smtp.php"); 
	
    $mail             = new PHPMailer(); 		//new一个PHPMailer对象出来
    $body             = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
    $mail->CharSet ="UTF-8";					//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();							// 设定使用SMTP服务
    $mail->SMTPDebug  = 2;                     	// 启用SMTP调试功能
												// 1 = errors and messages
												// 2 = messages only
    $mail->SMTPAuth   = true;					// 启用 SMTP 验证功能
    //$mail->SMTPSecure = "ssl";					// 安全协议
    $mail->Host       = "smtp.163.com";			// SMTP 服务器
    //$mail->Port       = 25;						// SMTP服务器的端口号
    $mail->Username   = "swust_acm";  			// SMTP服务器用户名
    $mail->Password   = "power@0063";			// SMTP服务器密码
    $mail->SetFrom("swust_acm@163.com", "SWUST.Info ACM");
    $mail->AddReplyTo("swust_acm@163.com","SWUST.Info ACM");
	
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, $name);
    //$mail->AddAttachment("images/phpmailer.gif");      // attachment 
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    if(!$mail->Send())
	{
        $_SESSION['message'][] = "Mailer Error: " . $mail->ErrorInfo;
    }
	else
	{
        $_SESSION['message'][] = "通知邮件发送成功！";
    }
}

/*
* Convert charset
*/
function phpcharset($data, $to)
{
	$encode_array = array('ASCII', 'UTF-8', 'GBK', 'GB2312', 'BIG5');
	$encoded = mb_detect_encoding($data, $encode_array);
	$data = mb_convert_encoding($data, $to, $encoded);
	return $data;
}
