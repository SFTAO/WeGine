<?php
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$keyword = trim($_GPC['keyword']);
	if(!empty($keyword)){
		$hasbiaoqian = pdo_fetchall("SELECT * FROM ".tablename(BEST_BIAOQIAN)." WHERE weid = {$_W['uniacid']} AND (realname like '%{$keyword}%' OR telphone like '%{$keyword}%' OR name like '%{$keyword}%')");
		if(!empty($hasbiaoqian)){
			$total = count($hasbiaoqian);
			$list = array();
			foreach($hasbiaoqian as $k=>$v){
				 $isfankefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$v['fensiopenid']}' AND kefuopenid = '{$v['kefuopenid']}' AND fszx = 1");
				if(!empty($isfankefu)){
					$list[$k] = $isfankefu;
				}
			}
		}else{
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansnickname like '%{$keyword}%' AND fszx = 1");
			$allpage = ceil($total/10)+1;
			$page = intval($_GPC["page"]);
			$pindex = max(1, $page);
			$psize = 10;
			$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansnickname like '%{$keyword}%' AND fszx = 1 ORDER BY  id DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
		}
	}else{
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fszx = 1");
		$allpage = ceil($total/10)+1;
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fszx = 1 ORDER BY id DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	}
	$pager = pagination($total, $pindex, $psize);
	include $this->template('web/zaixian');
}elseif($operation == 'kefu') {
	$keyword = trim($_GPC['keyword']);
	if(!empty($keyword)){
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansnickname like '%{$keyword}%' AND kfzx = 1");
		$allpage = ceil($total/10)+1;
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansnickname like '%{$keyword}%' AND kfzx = 1 ORDER BY  id DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	}else{
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kfzx = 1");
		$allpage = ceil($total/10)+1;
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kfzx = 1 ORDER BY id DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	}
	$pager = pagination($total, $pindex, $psize);
	include $this->template('web/zaixian');
}elseif($operation == 'kehuxiaxian'){
	$id = intval($_GPC['id']);
	$data['fszx'] = 0;
	pdo_update(BEST_FANSKEFU,$data,array('id'=>$id));
	message('下线成功！', $this->createWebUrl('zaixian'), 'success');
}elseif($operation == 'kefuxiaxian'){
	$id = intval($_GPC['id']);
	$data['kfzx'] = 0;
	pdo_update(BEST_FANSKEFU,$data,array('id'=>$id));
	message('下线成功！', $this->createWebUrl('zaixian',array('op'=>'kefu')), 'success');
}elseif($operation == 'kehuxiaxianpl'){
	$data['fszx'] = 0;
	pdo_update(BEST_FANSKEFU,$data,array('weid'=>$_W['uniacid']));
	message('下线成功！', $this->createWebUrl('zaixian'), 'success');
}elseif($operation == 'kefuxiaxianpl'){
	$data['kfzx'] = 0;
	pdo_update(BEST_FANSKEFU,$data,array('weid'=>$_W['uniacid']));
	message('下线成功！', $this->createWebUrl('zaixian',array('op'=>'kefu')), 'success');
}else {
	message('请求方式不存在');
}
?>