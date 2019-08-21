<?php
global $_W, $_GPC;
$openid = $_W['fans']['from_user'];
if(empty($openid)){
	header("Location:".$this->createMobileUrl('kefulogin'));
}
$iscservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$openid}'");
if(empty($iscservice)){
	$message = '你不是客服！';
	include $this->template('error');
	exit;
}
$op = trim($_GPC['op']);
if($op == ""){
	$total1 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND (lastcon != '' OR notread > 0) AND kefudel = 0 AND nowjd = 0");
	$total2 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND nowjd = 1");
	$total3 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND nowjd = 2");
	include $this->template('kefucenter');
}elseif($op == 'sxx'){
	if(empty($iscservice)){
		$resArr['error'] = 1;
		$resArr['message'] = '你不是客服！';
		echo json_encode($resArr);
		exit;
	}
	if($iscservice['isrealzx'] == 1){
		$data['isrealzx'] = 0;
	}else{
		$data['isrealzx'] = 1;
	}
	pdo_update(BEST_CSERVICE,$data,array('id'=>$iscservice['id']));
	$resArr['error'] = 0;
	echo json_encode($resArr);
	exit;
}elseif($op == 'search'){
	$nickname = trim($_GPC['nickname']);
	if(empty($nickname)){
		$resArr['error'] = 1;
		$resArr['msg'] = '请输入搜索词！';
		echo json_encode($resArr);
		exit;
	}
	$hasbiaoqian = pdo_fetchall("SELECT * FROM ".tablename(BEST_BIAOQIAN)." WHERE weid = {$_W['uniacid']} AND (realname like '%{$nickname}%' OR telphone like '%{$nickname}%' OR name like '%{$nickname}%')");
	if(!empty($hasbiaoqian)){
		$fanslist = array();
		foreach($hasbiaoqian as $k=>$v){
			$hasfankefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$v['fensiopenid']}' AND kefuopenid = '{$v['kefuopenid']}' AND lastcon != ''");
			if(!empty($hasfankefu)){
				$fanslist[$k] = $hasfankefu;
			}
		}
	}else{
		$fanslist = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND fansnickname like '%{$nickname}%' AND lastcon != '' ORDER BY lasttime DESC");
	}
	$html = '';
	if(!empty($fanslist)){
		foreach($fanslist as $k=>$v){
			$html .= '<a href="'.$this->createMobileUrl('servicechat',array('toopenid'=>$v['fansopenid'])).'">
						<div class="weui-cell">
							<div class="weui-cell__hd">
								<img src="'.$v['fansavatar'].'">
							</div>
							<div class="weui-cell__bd textellipsis1">
								<div class="weui-flex">
									<div class="weui-flex__item textellipsis1" style="color:#333;">'.$v['fansnickname'].'</div>
									<div class="time">'.$this->format_date($v['lasttime']).'</div>
								</div>
								<div class="weui-flex">
									<div class="weui-flex__item title2 textellipsis1">
										'.$v['lastcon'].'
									</div>
								</div>
							</div>
							<div class="weui-cell__ft"></div>
						</div>
					</a>';
			
		}
	}
	$resArr['error'] = 0;
	$resArr['html'] = $html;
	echo json_encode($resArr);
	exit;
}
?>