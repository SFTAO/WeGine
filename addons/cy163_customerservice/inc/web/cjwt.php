<?php
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update(BEST_WENZHANG, array('paixu' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
		}
		message('常见问题排序更新成功！', $this->createWebUrl('cjwt', array('op' => 'display')), 'success');
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_WENZHANG)." WHERE weid = {$_W['uniacid']}");
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
			'weid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'des'=> $_GPC['des'],
			'addtime'=> TIMESTAMP,
		);
		if (!empty($id)) {
			pdo_update(BEST_WENZHANG, $data, array('id' => $id));
		} else {
			pdo_insert(BEST_WENZHANG, $data);
		}
		message('操作成功！', $this->createWebUrl('cjwt', array('op' => 'display')), 'success');
	}
	$cjwt = pdo_fetch("select * from ".tablename(BEST_WENZHANG)." where id = {$id} and weid= {$_W['uniacid']}");
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$cjwt = pdo_fetch("SELECT id FROM ".tablename(BEST_WENZHANG)." WHERE id = {$id}");
	if (empty($cjwt)) {
		message('抱歉，文章不存在或是已经被删除！', $this->createWebUrl('cjwt', array('op' => 'display')), 'error');
	}
	pdo_delete(BEST_WENZHANG, array('id' => $id));
	message('文章删除成功！', $this->createWebUrl('cjwt', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('web/cjwt');
?>