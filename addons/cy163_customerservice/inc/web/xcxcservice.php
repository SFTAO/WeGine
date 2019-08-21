<?php
global $_W, $_GPC;
$cando = $this->checkmain($_W['siteroot']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update(BEST_XCXCSERVICE, array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
		}
		message('小程序客服排序更新成功！', $this->createWebUrl('xcxcservice', array('op' => 'display')), 'success');
	}
	$xcxlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCX)." WHERE uniacid = {$_W['uniacid']}");
	$conditions = "weid = {$_W['uniacid']}";
	if($_GPC['xcxid'] > 0){
		$conditions .= " AND xcxid = {$_GPC['xcxid']}";
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCSERVICE)." WHERE ".$conditions." ORDER BY displayorder ASC");
	foreach($list as $k=>$v){
		if($v['lingjie'] == 0 && $v['starthour'] == 0 && $v['endhour'] == 0){
			$data['endhour'] = 24;
			pdo_update(BEST_XCXCSERVICE,$data,array('id'=>$v['id']));
		}
	}
	include $this->template('web/xcxcservice');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	$cservice = pdo_fetch("select * from ".tablename(BEST_XCXCSERVICE)." where id = {$id} and weid= {$_W['uniacid']}");
	$xcxlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_XCX) . " WHERE uniacid = {$_W['uniacid']}");
	if (checksubmit('submit')) {		
		if (empty($_GPC['name'])) {
			message('抱歉，请输入客服名称！');
		}
		if (empty($_GPC['xcxid'])) {
			message('抱歉，请选择所属小程序！');
		}
		if (empty($_GPC['content'])) {
			message('抱歉，请输入客服内容！');
		}
		if (empty($_GPC['thumb'])) {
			message('抱歉，请上传客服头像！');
		}		
		$data = array(
			'weid' => $_W['uniacid'],
			'name' => trim($_GPC['name']),
			'content' => trim($_GPC['content']),
			'thumb' => $_GPC['thumb'],
			'displayorder' => intval($_GPC['displayorder']),
			'kefuauto'=>trim($_GPC['kefuauto']),
			'autoreply'=>trim($_GPC['autoreply']),
			//'isautosub'=>intval($_GPC['isautosub']),
			'xcxid'=>intval($_GPC['xcxid']),
			'jhtext' => trim($_GPC['jhtext']),
			'jhname' => trim($_GPC['jhname']),
			
			'lingjie' => intval($_GPC['lingjie']),
			'starthour' => intval($_GPC['starthour']),
			'endhour' => intval($_GPC['endhour']),
			'isxingqi' => intval($_GPC['isxingqi']),
			'day1' => intval($_GPC['day1']),
			'day2' => intval($_GPC['day2']),
			'day3' => intval($_GPC['day3']),
			'day4' => intval($_GPC['day4']),
			'day5' => intval($_GPC['day5']),
			'day6' => intval($_GPC['day6']),
			'day7' => intval($_GPC['day7']),
		);		
		if (!empty($id)) {
			pdo_update(BEST_XCXCSERVICE, $data, array('id' => $id));
			pdo_update(BEST_XCXFANSKEFU, array('kefuavatar'=>tomedia($data['thumb'])), array('weid' => $_W['uniacid'],'kefuopenid'=>$data['content']));
		} else {
			pdo_insert(BEST_XCXCSERVICE, $data);
		}
		message('操作成功！', $this->createWebUrl('xcxcservice', array('op' => 'display')), 'success');
	}
	include $this->template('web/xcxcservice');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$cservice = pdo_fetch("SELECT id FROM ".tablename(BEST_XCXCSERVICE)." WHERE id = {$id} AND weid = {$_W['uniacid']}");
	if (empty($cservice)) {
		message('抱歉，小程序客服不存在或是已经被删除！', $this->createWebUrl('xcxcservice', array('op' => 'display')), 'error');
	}
	pdo_delete(BEST_XCXCSERVICE, array('id' => $id));
	message('小程序客服删除成功！', $this->createWebUrl('xcxcservice', array('op' => 'display')), 'success');
}elseif ($operation == 'msg') {
	$id = intval($_GPC['id']);
	$cservice = pdo_fetch("select * from ".tablename(BEST_XCXCSERVICE)." where id = {$id} and weid= {$_W['uniacid']}");
	$kefuopenid = $cservice['content'];
	$allfkid = pdo_fetchall("SELECT fkid FROM ".tablename(BEST_XCXCHAT)." WHERE weid = {$_W['uniacid']} AND toopenid = '{$kefuopenid}'");
	$fkidarr[] = 0;
	foreach($allfkid as $k=>$v){
		$fkidarr[] = $v['fkid'];
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_XCXFANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND kefuopenid = '{$kefuopenid}' AND lasttime > 0");
	$allpage = ceil($total/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$fanslist = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND kefuopenid = '{$kefuopenid}' AND lasttime > 0 ORDER BY lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($fanslist as $k=>$v){
		$xcxres = pdo_fetch("SELECT name FROM ".tablename(BEST_XCX)." WHERE gh_id = '{$v['gh_id']}'");
		$biaoqian = pdo_fetch("SELECT name FROM ".tablename(BEST_BIAOQIAN)." WHERE kefuopenid = '{$v['kefuopenid']}' AND fensiopenid = '{$v['fansopenid']}'");
		$v['fansnickname'] = $v['fansnickname'] == "" ? "用户" : $v['fansnickname'];
		if(!empty($biaoqian)){
			$fanslist[$k]['fansnickname'] = '['.$xcxres['name'].']['.$biaoqian['name'].']'.$v['fansnickname'];
		}else{
			$fanslist[$k]['fansnickname'] = '['.$xcxres['name'].']'.$v['fansnickname'];
		}
		$fanslist[$k]['chat'] = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCHAT)." WHERE fkid = {$v['id']} ORDER BY time DESC");
	}
	$pager = pagination($total, $pindex, $psize);
	include $this->template('web/xcxchatlist');
} elseif ($operation == 'sucai') {
	$id = intval($_GPC['id']);
	$cservice = pdo_fetch("select * from ".tablename(BEST_XCXCSERVICE)." where id = {$id} and weid= {$_W['uniacid']}");
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXAUTO)." WHERE weid = {$_W['uniacid']} AND kfid = {$id} ORDER BY paixu DESC");
	include $this->template('web/xcxsucailist');
} elseif ($operation == 'editsucai') {
	$scid = intval($_GPC['scid']);
	$sucai = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXAUTO)." WHERE id = {$scid} AND weid = {$_W['uniacid']}");
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'name' =>$_GPC['name'],
			'kfid' => $_GPC['kfid'],
			'paixu' => $_GPC['paixu'],
			'msgtype' => $_GPC['msgtype'],
			'title' => $_GPC['title'],
			'pagepath' => $_GPC['pagepath'],
			'pagethumb' => $_GPC['pagethumb'],
			'description' => $_GPC['description'],
			'url' => $_GPC['url'],
			'thumb_url' => $_GPC['thumb_url'],
			'thumb' => $_GPC['thumb'],
			'iszdhf' => $_GPC['iszdhf'],
			'zdhftitle' => $_GPC['zdhftitle'],
			'zdhftype' => $_GPC['zdhftype'],
		);
		if (!empty($scid)) {
			pdo_update(BEST_XCXAUTO, $data, array('id' => $scid));
		} else {
			pdo_insert(BEST_XCXAUTO, $data);
		}
		message('操作成功！', $_SERVER['HTTP_REFERER'], 'success');
	}
	include $this->template('web/xcxsucailist');
}elseif ($operation == 'delsucai') {
	$scid = intval($_GPC['scid']);
	$sucai = pdo_fetch("SELECT id FROM ".tablename(BEST_XCXAUTO)." WHERE id = {$scid} AND weid = {$_W['uniacid']}");
	if (empty($sucai)) {
		message('抱歉，素材不存在或是已经被删除！');
	}
	pdo_delete(BEST_XCXAUTO, array('id' => $scid));
	message('素材删除成功！', $_SERVER['HTTP_REFERER'], 'success');
}else {
	message('请求方式不存在');
}
?>