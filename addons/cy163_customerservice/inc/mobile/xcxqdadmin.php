<?php
global $_W, $_GPC;
$openid = $_W['fans']['from_user'];
if(empty($openid)){
	$message = '请在微信浏览器中打开！';
	include $this->template('error');
	exit;
}
$op = trim($_GPC['op']);
if($op == ''){
	$admins = pdo_fetchall("SELECT id FROM ".tablename(BEST_XCX)." WHERE uniacid = {$_W['uniacid']} AND admins = '{$openid}'");
	$xcxids = array();
	foreach($admins as $k=>$v){
		$xcxids[] = $v['id'];
	}
	$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCSERVICE)." WHERE weid = {$_W['uniacid']} AND xcxid in (".implode(",",$xcxids).")");
	include $this->template('admin_xcxkefu');
}elseif($op == 'kefudetail'){
	$content = trim($_GPC['content']);
	$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXCSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$content}'");
	if(empty($cservice)){
		$message = '不存在该客服！';
		include $this->template('error');
		exit;
	}
	
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_XCXFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$content}'");
	$allpage = ceil($total/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$fanslist = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$content}' AND lastcon != '' ORDER BY notread DESC,lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	
	foreach($fanslist as $kk=>$vv){
		$xcxres = pdo_fetch("SELECT name FROM ".tablename(BEST_XCX)." WHERE gh_id = '{$vv['gh_id']}'");
		$biaoqian = pdo_fetch("SELECT name FROM ".tablename(BEST_BIAOQIAN)." WHERE kefuopenid = '{$vv['kefuopenid']}' AND fensiopenid = '{$vv['fansopenid']}'");
		$vv['fansnickname'] = $vv['fansnickname'] == "" ? "用户" : $vv['fansnickname'];
		if(!empty($biaoqian)){
			$fanslist[$kk]['fansnickname'] = '['.$xcxres['name'].']['.$biaoqian['name'].']'.$vv['fansnickname'];
		}else{
			$fanslist[$kk]['fansnickname'] = '['.$xcxres['name'].']'.$vv['fansnickname'];
		}
		$fanslist[$kk]['fansavatar'] = $vv['fansavatar'] != "" ? $vv['fansavatar'] : MD_ROOT.'static/xcx.png';
	}
	
	$isajax = intval($_GPC['isajax']);
	if($isajax == 1){
		$html = '';
		foreach($fanslist as $kk=>$vv){
			if($vv['msgtype'] == 'image'){
				$con = '<span style="color:#900;">[图片消息]</span>';
			}else{
				$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['lastcon']);
			}
			$mychatbadge = $vv['notread'] > 0 ? '<div class="mychatbadge">'.$vv['notread'].'</div>' : '';
			$html .= '<div class="item flex textellipsis1">
						<a href="'.$this->createMobileUrl('xcxqdadmin',array('fkid'=>$vv['id'],'op'=>'detail')).'" class="flex tohref textellipsis1">
							<img src="'.$vv['fansavatar'].'">
							'.$mychatbadge.'
							<div class="text textellipsis1 flex1">
								<div class="name textellipsis1">'.$vv['fansnickname'].'</div>
								<div class="lastmsg textellipsis1">
									'.$con.'
								</div>
							</div>

							<div class="timedo">
								<div class="time">'.date("Y-m-d H:i:s",$vv['lasttime']).'</div>
							</div>
						</a>
					</div>';
		}
		echo $html;
		exit;
	}
	include $this->template('admin_xcxcservice');
}elseif($op == 'detail'){
	include_once ROOT_PATH.'qqface.php';
	$fkid = intval($_GPC['fkid']);
	$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCHAT)." WHERE weid = {$_W['uniacid']} AND fkid  = {$fkid} ORDER BY time ASC");
	$chatcontime = 0;
	foreach($chatcon as $k=>$v){
		if(($v['time'] - $chatcontime) > 7200){
			$chatcon[$k]['time'] = $v['time'];
		}else{
			$chatcon[$k]['time'] = '';
		}
		
		$chatcon[$k]['content'] = qqface_convert_html($chatcon[$k]['content']);
		
		$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $chatcon[$k]['content']);
		$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
		preg_match_all($regex,$chatcon[$k]['content'],$array2);  
		if(!empty($array2[0]) && $v['msgtype'] == 'text'){
			foreach($array2[0] as $kk=>$vv){
				if(!empty($vv) && strpos($vv,'https://res.wx.qq.com') === false){
					$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
				}
			}
		}
		
		$chatcontime = $v['time'];
	}
	$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE id = {$fkid}");
	$biaoqian = pdo_fetch("SELECT * FROM ".tablename(BEST_BIAOQIAN)." WHERE kefuopenid = '{$openid}' AND fensiopenid = '{$hasfanskefu['fansopenid']}'");
	include $this->template('admin_xcxdetail');
}
?>