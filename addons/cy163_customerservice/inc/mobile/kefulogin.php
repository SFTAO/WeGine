<?php
global $_W, $_GPC;
$openid = $_W['fans']['from_user'];
if(!empty($openid)){
	header("Location:".$this->createMobileUrl('kefucenter'));
}
$nowtime = TIMESTAMP;
if(($nowtime-$_SESSION['lasttime'])>60){
	unset($_SESSION['openid']);
	unset($_SESSION['lasttime']);
	$_SESSION['openid'] = $openid;
	$_SESSION['lasttime'] = $nowtime;
}
$op = trim($_GPC['op']);
if($op == 'login'){
	$username = trim($_GPC['username']);
	$pwd = trim($_GPC['password']);
	if(empty($username) || empty($pwd)){
		$resArr['error'] = 1;
		$resArr['message'] = '用户名和密码不得为空！';
		echo json_encode($resArr);
		exit;
	}
	$pwd = sha1($pwd);
	$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = '{$_W['uniacid']}' AND username= '{$username}' AND pwd = '{$pwd}' AND ctype = 1");
	if(empty($cservice)){
		$resArr['error'] = 1;
		$resArr['message'] = '用户名或密码错误！';
		echo json_encode($resArr);
		exit;
	}
	$_SESSION['openid'] = $cservice['content'];
	$_SESSION['lasttime'] = $nowtime;
	$resArr['error'] = 0;
	$resArr['message'] = '登录成功！';
	echo json_encode($resArr);
	exit;
}else{
	include $this->template('kefulogin');
}
?>