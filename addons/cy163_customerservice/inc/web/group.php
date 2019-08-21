<?php
global $_GPC, $_W;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$grouplist = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUP)." WHERE weid = {$_W['uniacid']}");
	include ROOT_PATH.'qrcode.class.php';
	foreach($grouplist as $k=>$v){
		$grouplist[$k]['member'] = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$v['id']} ORDER BY isdel ASC,status DESC");
		$grouplist[$k]['chatlist'] = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND groupid = {$v['id']} ORDER BY time DESC");
		$grouplist[$k]['url'] = $_W['siteroot'].'app/'.str_replace('./','',$this->createMobileUrl('groupchatdetail',array('groupid'=>$v['id'])));
		
		$this->mkdirs('../addons/cy163_customerservice/qrcode/');
		$qrcodename = "../addons/cy163_customerservice/qrcode/qun-".$v['id'].".png";
		//if(!file_exists($qrcodename)){ 
			$errorCorrectionLevel = 'L';//容错级别   
			$matrixPointSize = 6;//生成图片大小   
			//生成二维码图片
			QRcode::png($grouplist[$k]['url'],$qrcodename,$errorCorrectionLevel,$matrixPointSize,2); 
		//}
		$grouplist[$k]['qrcode'] = $qrcodename;
	}
	include $this->template('web/group');
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$group = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUP) . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
	}
	$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 ORDER BY displayorder ASC");
	if (checksubmit('submit')) {
		if (empty($_GPC['groupname'])) {
			message('抱歉，请输入群聊名称！');
		}
		if (empty($_GPC['thumb'])) {
			message('抱歉，请上传群聊头像！');
		}
		if (empty($_GPC['admin'])) {
			message('抱歉，请选择群聊管理员！');
		}				
		$data = array(
			'weid' => $_W['uniacid'],
			'groupname' => trim($_GPC['groupname']),
			'thumb' => trim($_GPC['thumb']),
			'time'=>TIMESTAMP,
			'autoreply'=>trim($_GPC['autoreply']),
			'admin'=>trim($_GPC['admin']),
			'quickcon'=>trim($_GPC['quickcon']),
			'isguanzhu'=>intval($_GPC['isguanzhu']),
			'maxnum'=>intval($_GPC['maxnum']),
			'jinyan'=>intval($_GPC['jinyan']),
			'isshenhe'=>intval($_GPC['isshenhe']),
			'autotx'=>intval($_GPC['autotx']),
			'isfs'=>intval($_GPC['isfs']),
		);
		if (!empty($id)) {
			pdo_update(BEST_GROUP, $data, array('id' => $id, 'weid' => $_W['uniacid']));
		} else {
			pdo_insert(BEST_GROUP,$data);
			$id = pdo_insertid();
		}
		$hasgroupmember = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$id} AND openid = '{$_GPC['admin']}'");
		if(empty($hasgroupmember)){
			$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_GPC['admin']}'");
			$datamember['weid'] = $_W['uniacid'];
			$datamember['groupid'] = $id;
			$datamember['openid'] = trim($_GPC['admin']);
			$datamember['nickname'] = $cservice['name'];
			$datamember['avatar'] = tomedia($cservice['thumb']);
			$datamember['type'] = 2;
			$datamember['status'] = 1;
			$datamember['intime'] = TIMESTAMP;
			pdo_insert(BEST_GROUPMEMBER,$datamember);
		}
		message('操作成功！', $this->createWebUrl('group', array('op' => 'display')), 'success');
	}
	include $this->template('web/group');
}elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$group = pdo_fetch("SELECT id FROM " . tablename(BEST_GROUP) . " WHERE id = {$id}");
	if (empty($group)) {
		message('抱歉，不存在该群聊或是已经被删除！', $this->createWebUrl('group', array('op' => 'display')), 'error');
	}
	pdo_delete(BEST_GROUP, array('id' => $id));
	pdo_delete(BEST_GROUPMEMBER, array('groupid' => $id));
	
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE groupid = {$id}");
	foreach($list as $k=>$v){
		if($v['type'] == 3){
			$this->doQiuniudel($v['content']);
		}
		pdo_delete(BEST_GROUPCONTENT,array('id'=>$v['id']));
	}
	message('删除群聊成功！', $this->createWebUrl('group', array('op' => 'display')), 'success');
}elseif ($operation == 'deleteall') {
	if (!empty($_GPC['groupid'])) {
		foreach ($_GPC['groupid'] as $id => $groupid) {
			$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE groupid = {$groupid}");
			foreach($list as $k=>$v){
				if($v['type'] == 3){
					$this->doQiuniudel($v['content']);
				}
				pdo_delete(BEST_GROUPCONTENT,array('id'=>$v['id']));
			}
		}
		message('删除聊天记录成功！', $this->createWebUrl('group', array('op' => 'display')), 'success');
	}else{
		message("请选择一条记录！",$this->createWebUrl('group', array('op' => 'display')),"error");
	}
}elseif ($operation == 'delmember') {
	$id = intval($_GPC['id']);
	$groupmember = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUPMEMBER) . " WHERE id = {$id}");
	if (empty($groupmember)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '抱歉，不存在该群成员或是已经被删除！';
		echo json_encode($resarr);
		exit();
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE groupid = {$groupmember['groupid']} AND openid = '{$groupmember['openid']}'");
	foreach($list as $k=>$v){
		if($v['type'] == 3){
			$this->doQiuniudel($v['content']);
		}
		pdo_delete(BEST_GROUPCONTENT,array('id'=>$v['id']));
	}
	pdo_delete(BEST_GROUPMEMBER, array('id' => $id));
	$resarr['error'] = 0;
	$resarr['msg'] = '删除群成员成功！';
	echo json_encode($resarr);
	exit();
}elseif ($operation == 'shenhemember') {
	$id = intval($_GPC['id']);
	$groupmember = pdo_fetch("SELECT * FROM " . tablename(BEST_GROUPMEMBER) . " WHERE id = {$id} AND status = 0 AND isdel = 0");
	if (empty($groupmember)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '抱歉，不存在该群成员或是已经被审核！';
		echo json_encode($resarr);
		exit();
	}
	pdo_update(BEST_GROUPMEMBER,array('status'=>1,'intime'=>TIMESTAMP), array('id' => $id));
	//提醒已被审核进群
	if(!empty($this->module['config']['tpl_kefu'])){
		$or_paysuccess_redirect = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("groupchatdetail",array('groupid'=>$groupmember['groupid'])));
		$postdata = array(
			'first' => array(
				'value' => '您已被审核进群！',
				'color' => '#990000'
			),
			'keyword1' => array(
				'value' => "入群提醒",
				'color' => '#ff510'
			),
			'keyword2' => array(
				'value' => "点击此消息进入群聊",
				'color' => '#ff510'
			),
			'remark' => array(
				'value' => '审核时间：'.date("Y-m-d H:i:s",TIMESTAMP),
				'color' => '#ff510'
			),							
		);
		$account_api = WeAccount::create();
		$account_api->sendTplNotice($groupmember['openid'],$this->module['config']['tpl_kefu'],$postdata,$or_paysuccess_redirect,'#980000');
	}	
	$resarr['error'] = 0;
	$resarr['msg'] = '审核群成员成功！';
	echo json_encode($resarr);
	exit();
}elseif ($operation == 'deletedu') {
	$id = intval($_GPC['id']);
	$chat = pdo_fetch("SELECT id,content,type FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND id = {$id}");
	if (empty($chat)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '不存在该聊天记录！';
		echo json_encode($resarr);
		exit();
	}
	if($chat['type'] == 3){
		$this->doQiuniudel($chat['content']);
	}
	pdo_delete(BEST_GROUPCONTENT,array('id'=>$id));
	$resarr['error'] = 0;
	$resarr['msg'] = '删除成功！';
	echo json_encode($resarr);
	exit();
}elseif ($operation == 'changenickname') {
	$id = intval($_GPC['id']);
	$groupmember = pdo_fetch("SELECT id,openid,groupid FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND id = {$id}");
	if (empty($groupmember)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '不存在该群成员！';
		echo json_encode($resarr);
		exit();
	}
	$dataup['nickname'] = trim($_GPC['nickname']);
	pdo_update(BEST_GROUPMEMBER,$dataup,array('id'=>$id));
	pdo_update(BEST_GROUPCONTENT,$dataup,array('openid'=>$groupmember['openid'],'groupid'=>$groupmember['groupid']));
	$resarr['error'] = 0;
	$resarr['msg'] = '修改昵称成功！';
	echo json_encode($resarr);
	exit();
}
elseif ($operation == 'changekaiguan') {
	$id = intval($_GPC['id']);
	$groupmember = pdo_fetch("SELECT id,txkaiguan,nickname FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND id = {$id}");
	if (empty($groupmember)) {
		$resarr['error'] = 1;
		$resarr['msg'] = '不存在该群成员！';
		echo json_encode($resarr);
		exit();
	}
	if($groupmember['txkaiguan'] == 1){
		$dataup['txkaiguan'] = 0;
		$msg = "关闭启群成员".$groupmember['nickname']."消息通知成功";
	}else{
		$dataup['txkaiguan'] = 1;
		$msg = "开启群成员".$groupmember['nickname']."消息通知成功";
	}
	pdo_update(BEST_GROUPMEMBER,$dataup,array('id'=>$id));
	$resarr['error'] = 0;
	$resarr['msg'] = $msg;
	echo json_encode($resarr);
	exit();
}elseif ($operation == 'addmember') {
	$id = intval($_GPC['id']);
	$group = pdo_fetch("SELECT id FROM " . tablename(BEST_GROUP) . " WHERE id = {$id}");
	if (empty($group)) {
		message('抱歉，不存在该群聊或是已经被删除！', $this->createWebUrl('group', array('op' => 'display')), 'error');
	}
	include $this->template('web/group');
}elseif ($operation == 'doaddmember') {
	$groupid = intval($_GPC['id']);
	$openid = trim($_GPC['openid']);
	if (empty($openid)) {
		message('请填写openid！', $this->createWebUrl('group', array('op' => 'display')), 'error');
	}
	$hasgroupmember = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' AND groupid = {$groupid}");
	if (!empty($hasgroupmember)) {
		message('已存在该成员！', $this->createWebUrl('group', array('op' => 'display')), 'error');
	}
	
	$account_api = WeAccount::create();
	$info = $account_api->fansQueryInfo($openid);
	if($info['subscribe'] == 1){
		$avatar = $info['headimgurl'];
		$nickname = str_replace('\'', '\'\'',$info['nickname']);
	}else{
		message('该用户未关注公众号！', $this->createWebUrl('group', array('op' => 'display')), 'error');
	}
	$data = array(
		'weid'=>$_W['uniacid'],
		'groupid'=>$groupid,
		'openid'=>$openid,
		'avatar'=>$avatar,
		'nickname'=>$nickname,
		'type'=>1,
		'status'=>1,
		'intime'=>TIMESTAMP,
		'txkaiguan'=>intval($_GPC['txkaiguan']),
		'isdel'=>0,
	);
	pdo_insert(BEST_GROUPMEMBER,$data);
	message('添加成员成功！', $this->createWebUrl('group', array('op' => 'display')), 'success');
}
?>