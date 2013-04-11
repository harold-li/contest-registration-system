<?php
  require_once "config.php";
  if(!isset($_SESSION['right'])||strtoupper($_SESSION['right'])!='ADMINISTRATOR')
  {
    $_SESSION['message'][] = '无权操作';
    header('Location: index.php');
    exit;
  }
  $year = date('Y');
  if(array_key_exists('y', $_GET))
    $year = $_GET['y'];
  if(!array_key_exists('f', $_GET))
  {
?>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<link rel="stylesheet" type="text/css" href="misc/style.css"/>
<title><?php require_once "config.php";echo $head_title['index'] ?></title>

<div align="center"><h3>账号及数据库文件下载</h3></div>
<div class="download">
<?php
  if(file_exists("download/$year/sql.txt"))
    echo "<a href=\"download.php?f=sql&y=$year\">sql.txt</a><br>\n";
  if(file_exists("download/$year/nick.txt"))
    echo "<a href=\"download.php?f=nick&y=$year\">nick.txt</a><br>\n";
  if(file_exists("download/$year/team.txt"))
    echo "<a href=\"download.php?f=team&y=$year\">team.txt</a><br>\n";
  if(file_exists("download/$year/account.txt"))
    echo "<a href=\"download.php?f=account&y=$year\">account.txt</a><br>\n";
?>
<br>
</div>

<?php  
    exit(0);
  }

  $filename = $_GET['f'].'.txt';
  $file = 'download/'.$year.'/'.$filename;
  if(file_exists($file) && $fp=fopen($file, 'r'))
  {
    header("content-type:application/octet-stream");
    //header("accept-ranges:bytes");
    //header("accept-length:".filesize($file));
    header("content-disposition:attachment; filename=".$filename);
  
    echo fread($fp, filesize($file));
    fclose($fp);
  }
  else
  {
    echo "File not exists or cann't open.";
  }
  exit(0);
?>