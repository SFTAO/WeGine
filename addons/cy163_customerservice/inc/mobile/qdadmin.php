<?php
global $_W, $_GPC;
$openid = $_W['fans']['from_user'];
if(empty($openid)){
	$message = '请在微信浏览器中打开！';
	include $this->template('error');
	exit;
}
$adminsarr = explode("|",$this->module['config']['admins']);
if(!in_array($openid,$adminsarr)){
	$message = '你不是管理员！';
	include $this->template('error');
	exit;
}
$op = trim($_GPC['op']);
if($op == 'fensi'){
	include $this->template('admin_fensi');
}elseif($op == 'searchfensi'){
	$keyword = trim($_GPC['keyword']);
	if(empty($keyword)){
		$resArr['error'] = 1;
		$resArr['message'] = '请输入关键词搜索';
		echo json_encode($resArr);
		exit;
	}
	$hasbiaoqian = pdo_fetchall("SELECT * FROM ".tablename(BEST_BIAOQIAN)." WHERE weid = {$_W['uniacid']} AND (realname like '%{$keyword}%' OR telphone like '%{$keyword}%' OR name like '%{$keyword}%')");
	if(!empty($hasbiaoqian)){
		$list = array();
		foreach($hasbiaoqian as $k=>$v){
			$hasfankefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$v['fensiopenid']}' AND kefuopenid = '{$v['kefuopenid']}'");
			if(!empty($hasfankefu)){
				$list[$k] = $hasfankefu;
			}
		}
	}else{
		$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansnickname like '%{$keyword}%' ORDER BY id DESC");
	}
	$html = '';
	if(!empty($list)){
		foreach($list as $k=>$v){
			$html .= '<a href="'.$this->createMobileUrl('qdadmin',array('openid'=>$v['fansopenid'],'op'=>'fensikefu')).'">
						<div class="item flex">
							<img src="'.$v['fansavatar'].'">
							<div class="right flex flex1">
								<div class="kefuname textellipsis1 flex1">'.$v['fansnickname'].'</div>
								<div class="fname text-r"></div>
								<img src="'.NEWSTATIC_ROOT.'/jt-you.png" class="jtyou" />
							</div>
						</div>
					  </a>';
		}
	}else{
		$html = '<div class="nodata text-c">
					<img src="'.NEWSTATIC_ROOT.'/nodata.png" />
					<div class="text">暂无搜索结果</div>
				</div>';
	}
	$resArr['error'] = 0;
	$resArr['html'] = $html;
	echo json_encode($resArr);
	exit;
}elseif($op == 'kefudetail'){
	$content = trim($_GPC['content']);
	if(empty($content)){
		$message = '参数错误！';
		include $this->template('error');
		exit;
	}
	$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$content}'");
	if(empty($cservice)){
		$message = '不存在该客服！';
		include $this->template('error');
		exit;
	}
	
	$allfkid = pdo_fetchall("SELECT fkid FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND toopenid = '{$cservice['content']}'");
	$fkidarr[] = 0;
	foreach($allfkid as $k=>$v){
		$fkidarr[] = $v['fkid'];
	}
	pdo_query("UPDATE ".tablename(BEST_FANSKEFU)." set guanlinum = 0 WHERE id in (".implode(",",$fkidarr).")");
	
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$content}'");
	$allpage = ceil($total/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$fanslist = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$content}' ORDER BY notread DESC,lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	$isajax = intval($_GPC['isajax']);
	if($isajax == 1){
		$html = '';
		foreach($fanslist as $kk=>$vv){
			if($vv['msgtype'] == 4){
				$con = '<span style="color:#900;">[图片消息]</span>';
			}elseif($vv['msgtype'] == 5){
				$con = '<span style="color:green;">[语音消息]</span>';
			}else{
				$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['lastcon']);
			}
			$mychatbadge = $vv['notread'] > 0 ? '<div class="mychatbadge">'.$vv['notread'].'</div>' : '';
			$timeh = $vv['lasttime'] > 0 ? $this->getChatTimeStr($vv['lasttime']) : '暂无时间';
			$html .= '<div class="item flex textellipsis1">
						<a href="'.$this->createMobileUrl('qdadmin',array('fkid'=>$vv['id'],'op'=>'kefuchatdetail')).'" class="flex tohref textellipsis1">
							<img src="'.$vv['fansavatar'].'">
							'.$mychatbadge.'
							<div class="text textellipsis1 flex1">
								<div class="name textellipsis1">'.$vv['fansnickname'].'</div>
								<div class="lastmsg textellipsis1">
									'.$con.'
								</div>
							</div>
							
							<div class="timedo">
								<div class="time">'.$timeh.'</div>
							</div>
						</a>
					</div>';
		}
		echo $html;
		exit;
	}
	include $this->template('admin_cservicedetail');
}elseif($op == 'kefuchatdetail'){
	$fkid = intval($_GPC['fkid']);
	$fansandkefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE id = {$fkid}");
	if(empty($fkid)){
		$message = '参数错误！';
		include $this->template('error');
		exit;
	}
	$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND fkid  = {$fkid} ORDER BY time ASC");
	foreach($chatcon as $k=>$v){
		if($v['type'] == 5 || $v['type'] == 6){
			$donetime = TIMESTAMP - $v['time'];
			if($donetime >= 24*3600*3){
				unset($chatcon[$k]);
			}
		}else{
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$chatcon[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
					}
				}
			}
			$chatcon[$k]['content'] = $this->guolv($chatcon[$k]['content']);
		}
	}
	$imglist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND fkid  = {$fkid} AND (type = 3 OR type = 4) ORDER BY time DESC");
	include $this->template('admin_kefuchatdetail');
}elseif($op == 'fensichatdetail'){
	$fkid = intval($_GPC['fkid']);
	$fansandkefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE id = {$fkid}");
	if(empty($fkid)){
		$message = '参数错误！';
		include $this->template('error');
		exit;
	}
	$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND fkid  = {$fkid} ORDER BY time ASC");
	foreach($chatcon as $k=>$v){
		if($v['type'] == 5 || $v['type'] == 6){
			$donetime = TIMESTAMP - $v['time'];
			if($donetime >= 24*3600*3){
				unset($chatcon[$k]);
			}
		}else{
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$chatcon[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
					}
				}
			}
			$chatcon[$k]['content'] = $this->guolv($chatcon[$k]['content']);
		}
	}
	$imglist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND fkid  = {$fkid} AND (type = 3 OR type = 4) ORDER BY time DESC");
	include $this->template('admin_fensichatdetail');
}elseif($op == 'fensikefu'){
	$openid = trim($_GPC['openid']);
	$kefulist = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$openid}' ORDER BY kefulasttime DESC");
	include $this->template('admin_fensikefu');
}else{
	$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 ORDER BY displayorder ASC");
	foreach($cservicelist as $kk=>$vv){
		$cservicelist[$kk]['weidu'] = pdo_fetchcolumn("SELECT SUM(`guanlinum`) FROM ".tablename(BEST_FANSKEFU)." WHERE kefuopenid = '{$vv['content']}' AND weid = {$_W['uniacid']}");
	}
	include $this->template('admin_cservice');
}
?>