<?php
define(toolsName, '站长工具');											//网站名称
$connect = '广告请联系QQ14872638';										//广告联络
$blackDomain =  array('localhost','hechaocheng.cn','hechaoliang.cn');	//屏蔽查询
$blackIP = array('10', '127', '168', '192');							//屏蔽查询内网IP
$ads = array(
	//赞助商链接
	'<a href="http://dwz.cn/BandWaGonHost" target="_blank" style="color:red">搬瓦工高性价比VPS<a>',
	'<a href="http://dwz.cn/weloveservers" target="_blank" style="color:blue">我爱服务器<a>',
	'<a href="http://dwz.cn/Henghost" target="_blank" style="color:green">恒创科技<a>'
);

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/* Powered by Hechaocheng|0xE1D53B70|acity[at]hechaocheng.cn
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
class HccTools{
	var $typ = array(' 未知 ','FTP', 'SSH', 'TELNET', 'SMTP', 'Whois', 'DNS', 'HTTP', 'POP3', 'NTP', 'IMAP', 'HTTPS', 'IMAP:SSL', 'SMTP:SSL', 'POP3:SSL', 'MSSQL/udp', 'MYSQL', 'TERMINAL', 'QQ Client', 'WWW 代理');
	var $por = array(0,21, 22, 23, 25, 43, 53, 80, 110, 123, 143, 443, 993, 994, 995, 1433, 3306, 3389, 4000, 8080);
/*
	function __construct(){
		error_reporting(E_ALL^E_NOTICE);
		ini_set('max_execution_time', '5');
		header("Content-type: text/html; charset=utf-8");
		setcookie('HccToos', time(), time()+5);
	}
*/
	public function runtime(){
		list($h,$c) = explode(' ',microtime());
		return ((float)$h + (float)$c);
	}
	
	public function check($ip, $port){
		if(!function_exists('fsockopen')) return '终止:当前主机环境不支持 fsockopen! <a href="http://t.cn/Rq6yB3s" target="_blank">向群友[23 189 103]请求协助.</a>';
		//if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE)) $ip = gethostbyname($host);
		
		$start = $this->runtime();
		$fp = @fsockopen($ip, $port, $ErrCode, $ErrMsg, 1);
		$finish = $this->runtime();
		$execute = ceil(($finish - $start) * 1000);
		$service = array_search($port, $this->por);
		
		if(!$fp){
			$fp && fclose($fp);
			return array(0, $port, $this->typ[$service], ' - ');
		}else{
			$fp && fclose($fp);
			return array(1, $port, $this->typ[$service], $execute);
		}
	}
}
error_reporting(E_ALL^E_NOTICE);
ini_set('max_execution_time', 0);
date_default_timezone_set('PRC');
header('Content-type: text/html; charset=utf-8');
setcookie('HccToos', time(), time()+2);
define(H, substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1));
define(S, $_SERVER['SERVER_NAME']);

$Ports = $_POST['p'];
$Query = preg_replace("/\/(.*)$/is", '', preg_replace("/^(.*):\/\//is", '', $_POST['s']));
if(in_array(strtok($Query, '.'), $blackIP) || array_search($Query, $blackDomain)) $limit = '操作受限:该请求不允许查询.';
if(!empty($Query) && isset($_COOKIE['HccToos']) && !isset($limit)) $limit = '查询间隔时间太短,请稍后重试!';
$tools = new HccTools;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>端口扫描<?php echo $Query ? ' : '. $Query : '';echo '_'. toolsName;?></title>
<link rel="author" href="https://plus.google.com/109839096083180034412?rel=author" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta http-equiv="Cache-Control" content="no-transform " />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" >
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="shortcut icon" href="https://ssl.hechaocheng.eu.org/favicon.ico"/>
<link rel="bookmark" href="https://ssl.hechaocheng.eu.org/favicon.ico"/>
<link rel="stylesheet" type="text/css" href="HccTools/css/default.css"/>
<meta name="Keywords" content="<?php echo toolsName .',';echo $Query ? $Query .',': '';?>端口扫描器,端口扫描工具,在线端口扫描">
<meta name="Description" content="通过该工具可以扫描常用的端口和指定的端口是否开放。">
</head>

<body>
<div class="HCC">
	<div class="HCC_header"><div><a href="<?php echo H;?>" target="_top" title="端口在线扫描器">端口在线扫描器</a></div></div>
	
	
	<ul id="HccMenu"><form action="http://www.so.com/s" class="haosou_form" method="get" id="haosou_search_form" accept-charset="UTF-8" onsubmit="document.charset='UTF-8'" target="_blank"><input type="hidden" name="src" value="lm"><input type="hidden" name="ls" value="s7a1b733a7b"><input type="text" name="q" id="help" $="true" autocomplete="off" x-webkit-speech placeholder="查找资料" /></form>
		<li class="act">端口扫描</li>
		<li>关于程序</li>
	</ul>
	
	<div id="PL">
		<div>
			<form action="" method="post" name="$" onsubmit="return CheckForm(this);">
				<p style="text-align:center">请输入IP或域名 ：<input type="text" name="s" id="s" autofocus="autofocus" onfocus="this.select()" value="<?php echo $Query ? $Query : 'my.hechaocheng.cn';?>" autocomplete="off" style="border:#616378 2px solid;" /><input type="submit" value="开始扫描" style="background-color:#616378;border:#616378 2px solid; color:#FFF" /></p>
				<p style="text-align:right;color:#CCC"><?php echo $limit ? '<span style="float:left;color:red;text-align:left;">'. $limit .'</span>' : '';?>多个端口请用逗号隔开 例80,8080</p>
				<textarea name="p" id="p"><?php echo $Ports ? $Ports : '21,80,443,3306,3389,8080';?></textarea>
			</form>
			<?php
			if(!empty($Query) && !empty($Ports) && empty($limit) && !isset($_COOKIE['HccToos'])){
				$port = explode(',',$_POST['p']);
				$cc = array_filter(preg_replace('/\D/','',array_filter($port)));
				sort($cc);
			?>
			<table id="CJP" cellspacing="1">
			<tr><td colspan="5" style="text-align:left;background-color:#ccc;color:blue"><span style="float:right;color:#FFF"><? echo date('Y-m-d H:i:s');?></span><?php echo $Query;?></td></tr>
			<tr style="background-color:#E3E3E3;">
				<td width="10%">端口</td>
				<td width="15%">服务</td>
				<td width="20%">响应</td>
				<td width="20%">状态</td>
				<td width="35%">赞助商</td>
			</tr>
			<?php
				$st = $tools->runtime();
				$co = 0;
				for($i=0; $i<count($cc); $i++){
					if($cc[$i] < 63365){
						$tmp = $tools->check($Query, $cc[$i]);
						$ad = $ads[$i] ? $ads[$i] : $connect;
						
						if(is_numeric($tmp[3])){
							if( $tmp[3] <= 50  ){		$time = '<font color="green">'. $tmp[3] .' 毫秒</font>';
							}elseif( $tmp[3] <= 100 ){	$time = '<font color="#00FF40">'. $tmp[3] .' 毫秒</font>';
							}elseif( $tmp[3] <= 150 ){	$time = '<font color="blue">'. $tmp[3] .' 毫秒</font>';
							}elseif( $tmp[3] <= 200 ){	$time = '<font color="#FDAB02">'. $tmp[3] .'v</font>';
							}elseif( $tmp[3] <= 250 ){	$time = '<font color="#F60">'. $tmp[3] .' 毫秒</font>';
							}elseif( $tmp[3] <= 300 ){	$time = '<font color="red">'. $tmp[3] .' 毫秒</font>';
							}else{$time = '<font color="red"><b>'. $tmp[3] .'</b> 毫秒</font>';}
						}else{
							$time = '';
						}
						
						$status = $tmp[0] == 1 ? '<font color="red">开放</font>' : '<font color="green">关闭</font>';
						$statu2 = $tmp[0] == 1 ? ' style="background-color:#EDE1E3"' : '';
						echo '<tr'. $statu2 .'><td>'. $tmp[1] .'</td><td>'. $tmp[2] .'</td><td>'. $time .'</td><td>'. $status .'</td><td>'. $ad .'</td></tr>';
						$co++;
					}
				}
				$et = $tools->runtime();
				$rt = ceil(($et - $st) * 1000);
				echo '<tr><td colspan="5">共扫描 '. $Query .' 端口 '. $co .' 个, 耗时 '. $rt .' 毫秒!</td></tr></table>';
			}
			?>
		</div>
	
		<div class="hide">
	  		<p>通过该工具可以扫描常用的端口和指定的端口是否开放。</p>
	  		程序作者: <a href="https://hechaocheng.cn" target="_blank" rel="firend">何朝城</a> <a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&amp;email=qcjKwN3Q6cHMysHIxsrBzMfOh8rH" target="_blank" title="通过QQ邮箱联络">acity[at]hechaocheng.cn</a><br />程序演示:<a href="http://kr.hechaocheng.tk/" target="_blank">http://kr.hechaocheng.tk/</a><br /><a target="_blank" onClick="if(confirm('提示：有\'发广告,睹博,加另一个Q(皮条客),招网络刷单,裸聊,刷单…\'等行为的朋友请勿加群,否则一经发现,直接请出去.')){return true;}else{return false;};" href="http://t.cn/Rq6yB3s"><img border="0" src="HccTools/images/group_23189103.png" alt="站长博主运维讨论群" title="站长博主运维讨论群"></a><p style="width:200px;height:200px;margin:auto;"><img style="width:200px;height:auto" src="HccTools/images/hechaocheng.qq.png" title="扫一扫加我QQ: hechaocheng" /></p>
		</div>
	</div>

	
	
	<div class="HCC_footer">&copy;Copyright <a href="http://<?php echo S;?>/" style="color:#000" title="<?php echo S;?>"><?php echo S;?></a> All Rights Reserved<br /><font color="#999999">Powered by </font><!--我很期待你能保留这个链接,谢谢!--><a href="http://hechaocheng.cn/" target="_blank" style="color:#999999" title="阿城守候[FB5C F3C1 159B 2E67 8CE0 092D EFB1 CF89 E1D5 3B70]">Hechaocheng/0xE1D53B70</a></div>
</div>
<script type="text/javascript" src="HccTools/js/Menu.js"></script>
</body>
</html>