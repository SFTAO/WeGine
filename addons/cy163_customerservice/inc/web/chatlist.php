<?php
global $_GPC, $_W;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$cservicelist = pdo_fetchall("SELECT content,name,thumb FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 ORDER BY displayorder ASC");
	foreach($cservicelist as $kk=>$vv){
		$cservicelist[$kk]['weidu'] = pdo_fetchcolumn("SELECT SUM(`guanlinum`) FROM ".tablename(BEST_FANSKEFU)." WHERE kefuopenid = '{$vv['content']}' AND weid = {$_W['uniacid']}");
	}
	$kefuopenid = trim($_GPC['kefuopenid']);
	$total = 0;
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = TIMESTAMP;
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']) + 86399;
	}
	$conditions = "weid = {$_W['uniacid']} AND time > {$starttime} AND time < {$endtime}";
	if(!empty($kefuopenid)){
		$conditions .= " AND toopenid = '{$kefuopenid}'";
	}
	$allfkid = pdo_fetchall("SELECT fkid FROM ".tablename(BEST_CHAT)." WHERE ".$conditions);
	$fkidarr[] = 0;
	foreach($allfkid as $k=>$v){
		$fkidarr[] = $v['fkid'];
	}
	pdo_query("UPDATE ".tablename(BEST_FANSKEFU)." set guanlinum = 0 WHERE id in (".implode(",",$fkidarr).")");
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND lasttime > 0");
	$allpage = ceil($total/10)+1;
	$page = intval($_GPC["page"]);
	$pindex = max(1, $page);
	$psize = 10;
	$fanslist = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND lasttime > 0 ORDER BY lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
	foreach($fanslist as $k=>$v){
		$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$v['id']} ORDER BY time DESC");
		foreach($chatlist as $kk=>$vv){
			$chatlist[$kk]['content'] = htmlspecialchars_decode($vv['content']);
		}
		$fanslist[$k]['chat'] = $chatlist;
	}
	$pager = pagination($total, $pindex, $psize);
	if ($_GPC['export'] == 'export') {	
		$fanslistdaochu = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND lasttime > 0 ORDER BY lasttime DESC");
		foreach($fanslistdaochu as $k=>$v){
			$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$v['id']} ORDER BY time DESC");
			$data = array();
			$onetitle = '和'.$v['fansnickname'].'的记录';
			$twotitle = '和'.$v['fansnickname'].'聊天内容';
			$titlearray = array($onetitle,$twotitle,'时间');
			foreach ($chatlist as $kk => $vv) {
				$data[$kk]['nickname'] = $vv['nickname'];
				$data[$kk]['con'] = $vv['content'];
				$data[$kk]['time'] = date("Y-m-d H:i:s",$vv['time']);
			}
			$this->exportexcel($data,$titlearray,'','',$filename=$v['kefunickname'].'工作记录');
		}
		exit();
	}
	include $this->template('web/chatlist');
}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$kefuopenid = trim($_GPC['kefuopenid']);
	if (empty($id)) {
		message('抱歉，参数传入错误！', $this->createWebUrl('chatlist', array('op' => 'display')), 'error');
	}
	$chatlist = pdo_fetchall("SELECT id,content,type FROM ".tablename(BEST_CHAT)." WHERE fkid = {$id}");
	foreach($chatlist as $k=>$v){
		if($v['type'] != 1 && $v['type'] != 2){
			$this->doQiuniudel($v['content']);
		}
		pdo_delete(BEST_CHAT,array('id'=>$v['id']));
	}
	pdo_query("DELETE FROM ".tablename(BEST_FANSKEFU)." WHERE id = {$id}");
	message('删除聊天记录成功！', $this->createWebUrl('chatlist', array('kefuopenid'=>$kefuopenid)), 'success');
}elseif ($operation == 'deletedu') {
	$id = intval($_GPC['id']);
	$chat = pdo_fetch("SELECT id,content,type FROM ".tablename(BEST_CHAT)." WHERE id = {$id}");
	if (empty($chat)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '不存在该聊天记录！';
		echo json_encode($resarr);
		exit();
	}
	if($chat['type'] != 1 && $chat['type'] != 2){
		$this->doQiuniudel($chat['content']);
	}
	pdo_delete(BEST_CHAT,array('id'=>$id));
	$resarr['error'] = 0;
	$resarr['msg'] = '删除成功！';
	echo json_encode($resarr);
	exit();
}
?>