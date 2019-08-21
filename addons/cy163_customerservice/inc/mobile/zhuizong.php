<?php
global $_W, $_GPC;
$openid = $_W['fans']['from_user'];
if(empty($openid)){
	$message = '请在微信浏览器中打开！';
	include $this->template('error');
	exit;
}
$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$openid}'");
if(empty($cservice)){
	$message = '你不是客服身份，请联系管理员查看具体信息！';
	include $this->template('error');
	exit;
}
$toopenid = trim($_GPC['toopenid']);
if(empty($toopenid)){
	$message = '请选择要追踪的客户！';
	include $this->template('error');
	exit;
}
$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$toopenid}' AND kefuopenid = '{$openid}'");
if(empty($hasfanskefu)){
	$message = '你不能追踪该客户！';
	include $this->template('error');
	exit;
}
$op = trim($_GPC['op']);
if($op == ''){
	$zzlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_ZHUIZONG)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' ORDER BY time DESC");
	include $this->template('zhuizong');
}elseif($op == 'add'){
	if(empty($cservice)){
		$resArr['error'] = 1;
		$resArr['message'] = '你不是客服身份，请联系管理员查看具体信息！';
		echo json_encode($resArr);
		exit;
	}
	if(empty($hasfanskefu)){
		$resArr['error'] = 1;
		$resArr['message'] = '你不能追踪该客户！';
		echo json_encode($resArr);
		exit;
	}
	$content = $_GPC['content'];
	if(empty($content)){
		$resArr['error'] = 1;
		$resArr['message'] = '请填写记录内容！';
		echo json_encode($resArr);
		exit;
	}
	$data = array(
		'weid'=>$_W['uniacid'],
		'kefuopenid'=>$openid,
		'kefuname'=>$hasfanskefu['kefunickname'],
		'kefuavatar'=>$hasfanskefu['kefuavatar'],
		'fansopenid'=>$toopenid,
		'fansavatar'=>$hasfanskefu['fansavatar'],
		'fansname'=>$hasfanskefu['fansnickname'],
		'content'=>$content,
		'time'=>TIMESTAMP,
		'zztype'=>0
	);
	pdo_insert(BEST_ZHUIZONG,$data);
	$resArr['error'] = 0;
	$resArr['message'] = '添加记录成功！';
	echo json_encode($resArr);
	exit;
}
?>