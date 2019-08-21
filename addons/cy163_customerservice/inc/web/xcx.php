<?php
global $_W, $_GPC;
$cando = $this->checkmain($_W['siteroot']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCX)." WHERE uniacid = {$_W['uniacid']}");
	foreach($list as $k=>$v){
		$list[$k]['url'] = $_W['siteroot'].'app/'.str_replace('./','',$this->createMobileUrl('xcxjt',array('id'=>$v['id'])));
	}
	include $this->template('web/xcx');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['name'],
			'gh_id' => $_GPC['gh_id'],
			'appid' => $_GPC['appid'],
			'secret' => $_GPC['secret'],
			'admins' => $_GPC['admins'],
		);
		if (!empty($id)) {
			pdo_update(BEST_XCX, $data, array('id' => $id));
		} else {
			$data['token'] = $this->randCharNumber(32);
			$data['aeskey'] = $this->randCharNumber(43);
			pdo_insert(BEST_XCX, $data);
		}
		message('操作成功！', $this->createWebUrl('xcx', array('op' => 'display')), 'success');
	}
	$xcx = pdo_fetch("select * from ".tablename(BEST_XCX)." where id = {$id} and uniacid= {$_W['uniacid']}");
	include $this->template('web/xcx');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$xcx = pdo_fetch("SELECT id FROM ".tablename(BEST_XCX)." WHERE id = {$id} AND uniacid = {$_W['uniacid']}");
	if (empty($xcx)) {
		message('抱歉，该小程序不存在或是已经被删除！', $this->createWebUrl('xcx', array('op' => 'display')), 'error');
	}
	pdo_delete(BEST_XCX, array('id' => $id));
	message('小程序删除成功！', $this->createWebUrl('xcx', array('op' => 'display')), 'success');
} elseif ($operation == 'chongzhi') {
	$id = intval($_GPC['id']);
	$xcx = pdo_fetch("SELECT id FROM ".tablename(BEST_XCX)." WHERE id = {$id} AND uniacid = {$_W['uniacid']}");
	if (empty($xcx)) {
		message('抱歉，该小程序不存在或是已经被删除！', $this->createWebUrl('xcx', array('op' => 'display')), 'error');
	}
	pdo_update(BEST_XCX, array('access_token'=>'','guoqitime'=>0),array('id' => $id));
	message('重置token成功！', $this->createWebUrl('xcx', array('op' => 'display')), 'success');
}elseif ($operation == 'auto') {
	$xcxid = intval($_GPC['id']);
	$xcx = pdo_fetch("SELECT * FROM ".tablename(BEST_XCX)." WHERE id = {$xcxid} AND uniacid = {$_W['uniacid']}");
	
	if (!empty($_GPC['paixu'])) {
		foreach ($_GPC['paixu'] as $id => $paixu) {
			pdo_update(BEST_XCXAUTO, array('paixu' => $paixu), array('id' => $id, 'weid' => $_W['uniacid']));
		}
		message('优先级更新成功！', $this->createWebUrl('xcx', array('op' => 'auto','id'=>$xcxid)), 'success');
	}
	
	$allauto = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXAUTO)." WHERE weid = {$_W['uniacid']} AND xcxid = {$xcxid} ORDER BY paixu ASC");
	$index = 'index';
	include $this->template('web/xcxauto');
}else {
	message('请求方式不存在');
}
?>