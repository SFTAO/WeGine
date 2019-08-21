<?php
global $_GPC, $_W;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$grouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = {$_W['uniacid']} ORDER BY displayorder ASC");
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update(BEST_CSERVICE, array('displayorder' => $displayorder), array('id' => $id));
		}
		message('客服排序更新成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
	}
	if($_GPC['groupid']){
		$kefuandgroup = pdo_fetchall("SELECT kefuid FROM ".tablename(BEST_KEFUANDGROUP)." WHERE groupid = {$_GPC['groupid']}");
		$kefuidsarr = array();
		foreach($kefuandgroup as $k=>$v){
			$kefuidsarr[] = $v['kefuid'];
		}
		$cservicelist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE (groupid = '{$_GPC['groupid']}' OR id in (".implode(',',$kefuidsarr).")) ORDER BY displayorder ASC");
	}else{
		$cservicelist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} ORDER BY displayorder ASC");
	}
	include ROOT_PATH.'qrcode.class.php';
	foreach($cservicelist as $k=>$v){
		if($v['ctype'] == 1){
			$cservicelist[$k]['serviceurl'] = $_W['siteroot'].'app/'.str_replace('./','',$this->createMobileUrl('chat',array('toopenid'=>$v['content'])));
			$this->mkdirs('../addons/cy163_customerservice/qrcode/');
			$qrcodename = "../addons/cy163_customerservice/qrcode/".$v['content'].".png";
			//if(!file_exists($qrcodename)){ 
				$errorCorrectionLevel = 'L';//容错级别   
				$matrixPointSize = 6;//生成图片大小   
				//生成二维码图片
				QRcode::png($cservicelist[$k]['serviceurl'],$qrcodename,$errorCorrectionLevel,$matrixPointSize,2); 
			//}
			$cservicelist[$k]['qrcode'] = $qrcodename;
		}
		if($v['ctype'] == 2){
			$cservicelist[$k]['serviceurl'] = "http://wpa.qq.com/msgrd?v=3&uin=".$v['content'];
		}
		if($v['ctype'] == 3 || $v['ctype'] == 4){
			$cservicelist[$k]['serviceurl'] = "tel:".$v['content'];
		}
	}
	$this->addauth();
	include $this->template('web/cservice');
}elseif($operation == 'pingjia') {
	$cservicelist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND ctype = 1 ORDER BY displayorder ASC");
	
	$content = trim($_GPC['content']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename(BEST_PINGJIA)." WHERE kefuopenid = '{$content}'");
	$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$pingjialist = pdo_fetchall("SELECT * FROM ".tablename(BEST_PINGJIA)." WHERE kefuopenid = '{$content}' ORDER BY time DESC".$limit);
	foreach($pingjialist as $k=>$v){
		$pingjialist[$k]['member'] = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$v['fensiopenid']}'");
	}
	$pager = pagination($total, $pindex, $psize);
	include $this->template('web/cservice');
}elseif($operation == 'post') {
	$cservicegrouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder ASC");
	$cjwtlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_WENZHANG)." WHERE weid = {$_W['uniacid']}");
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$cservice = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICE) . " WHERE weid = {$_W['uniacid']} AND id = {$id}");
		foreach($cservicegrouplist as $k=>$v){
			$isingroup = pdo_fetch("SELECT id FROM ".tablename(BEST_KEFUANDGROUP)." WHERE weid = {$_W['uniacid']} AND kefuid = {$id} AND groupid = {$v['id']}");
			$cservicegrouplist[$k]['isin'] = empty($isingroup) ? 0 : 1;
		}
		foreach($cjwtlist as $k=>$v){
			$isincjwt = pdo_fetch("SELECT id FROM ".tablename(BEST_KEFUANDCJWT)." WHERE weid = {$_W['uniacid']} AND kefuid = {$id} AND wtid = {$v['id']}");
			$cjwtlist[$k]['isin'] = empty($isincjwt) ? 0 : 1;
		}
	}
	if (checksubmit('submit')) {
		if (empty($_GPC['name'])) {
			message('抱歉，请输入客服名称！');
		}
		if (empty($id) && empty($_GPC['ctype'])) {
			message('抱歉，请选择客服类型！');
		}
		if (empty($id) && empty($_GPC['content'])) {
			message('抱歉，请输入客服内容！');
		}
		if (empty($_GPC['thumb'])) {
			message('抱歉，请上传客服头像！');
		}
		$ctype = intval($_GPC['ctype']);
		$starthour = intval($_GPC['starthour']);
		$endhour = intval($_GPC['endhour']);
		$autoreply = trim($_GPC['autoreply']);
		$autoreplyimg = trim($_GPC['autoreplyimg']);
		$data = array(
			'weid' => $_W['uniacid'],
			'name' => trim($_GPC['name']),
			'typename' => trim($_GPC['typename']),
			'ctype' => $ctype,
			'thumb' => $_GPC['thumb'],
			'iskefuqrcode' => intval($_GPC['iskefuqrcode']),
			'kefuqrcode' => $_GPC['kefuqrcode'],
			'starthour' => $starthour,
			'endhour' => $endhour,
			'autoreply' => $autoreply,
			'autoreplyimg'=> $autoreplyimg,
			'displayorder' => intval($_GPC['displayorder']),
			'fansauto'=>trim($_GPC['fansauto']),
			//'isautosub'=>intval($_GPC['isautosub']),
			'notonline'=>trim($_GPC['notonline']),
			'lingjie' => intval($_GPC['lingjie']),
			'beibang' => intval($_GPC['beibang']),
			'bdchat' => intval($_GPC['bdchat']),
			'iszx' => intval($_GPC['iszx']),
			
			'isxingqi' => intval($_GPC['isxingqi']),
			'day1' => intval($_GPC['day1']),
			'day2' => intval($_GPC['day2']),
			'day3' => intval($_GPC['day3']),
			'day4' => intval($_GPC['day4']),
			'day5' => intval($_GPC['day5']),
			'day6' => intval($_GPC['day6']),
			'day7' => intval($_GPC['day7']),
			
			'cangzh' => intval($_GPC['cangzh']),
			'gzhqzval' => intval($_GPC['gzhqzval']),
		);
		if (!empty($id)) {
			pdo_delete(BEST_KEFUANDGROUP,array('kefuid'=>$id));
			if(!empty($_GPC['groupid'])){
				foreach($_GPC['groupid'] as $ck=>$cv){
					$datacc['kefuid'] = $id;
					$datacc['groupid'] = $cv;
					$datacc['weid'] = $_W['uniacid'];
					pdo_insert(BEST_KEFUANDGROUP,$datacc);
				}

			}
			
			pdo_delete(BEST_KEFUANDCJWT,array('kefuid'=>$id));
			if(!empty($_GPC['wtid'])){
				foreach($_GPC['wtid'] as $ck=>$cv){
					$datacc2['kefuid'] = $id;
					$datacc2['wtid'] = $cv;
					$datacc2['weid'] = $_W['uniacid'];
					pdo_insert(BEST_KEFUANDCJWT,$datacc2);
				}

			}
			unset($data['ctype']);
			pdo_update(BEST_CSERVICE, $data, array('id' => $id, 'weid' => $_W['uniacid']));
			if($ctype == 1){
				$dataup['kefuavatar'] = $dataup2['avatar'] = $dataup3['avatar'] = $dataup4['avatar'] = $dataup5['kefuavatar'] = tomedia($data['thumb']);
				$dataup['kefunickname'] = $dataup2['nickname'] = $dataup3['nickname'] = $dataup4['nickname'] = $dataup5['kefuname'] = $data['name'];
				pdo_update(BEST_FANSKEFU,$dataup,array('weid'=>$_W['uniacid'],'kefuopenid'=>$cservice['content']));
				pdo_update(BEST_GROUPCONTENT,$dataup2,array('weid'=>$_W['uniacid'],'openid'=>$cservice['content']));
				pdo_update(BEST_GROUPMEMBER,$dataup3,array('weid'=>$_W['uniacid'],'openid'=>$cservice['content']));
				pdo_update(BEST_CHAT,$dataup4,array('weid'=>$_W['uniacid'],'openid'=>$cservice['content']));
				pdo_update(BEST_ZHUIZONG,$dataup5,array('weid'=>$_W['uniacid'],'kefuopenid'=>$cservice['content']));
			}
		} else {
			$data['content'] = trim($_GPC['content']);
			pdo_insert(BEST_CSERVICE, $data);
			$kefuid = pdo_insertid();
			if(!empty($_GPC['groupid'])){
				foreach($_GPC['groupid'] as $ck=>$cv){
					$datacc['kefuid'] = $kefuid;
					$datacc['groupid'] = $cv;
					$datacc['weid'] = $_W['uniacid'];
					pdo_insert(BEST_KEFUANDGROUP,$datacc);
				}

			}
			if(!empty($_GPC['wtid'])){
				foreach($_GPC['wtid'] as $ck=>$cv){
					$datacc2['kefuid'] = $kefuid;
					$datacc2['wtid'] = $cv;
					$datacc2['weid'] = $_W['uniacid'];
					pdo_insert(BEST_KEFUANDCJWT,$datacc2);
				}

			}
		}
		message('操作成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
	}
	include $this->template('web/cservice');
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	$cservice = pdo_fetch("SELECT id,content,ctype FROM " . tablename(BEST_CSERVICE) . " WHERE id = {$id}");
	if (empty($cservice)) {
		message('抱歉，该客服信息不存在或是已经被删除！', $this->createWebUrl('cservice', array('op' => 'display')), 'error');
	}
	if($cservice['ctype'] == 1){
		pdo_update(BEST_FANSKEFU,array('bdopenid'=>''),array('bdopenid'=>$cservice['content']));
		$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND (openid = '{$cservice['content']}' OR toopenid = '{$cservice['content']}')");
		foreach($chatlist as $k=>$v){
			if($v['type'] != 1 && $v['type'] != 2){
				$this->doQiuniudel($v['content']);
			}
			pdo_delete(BEST_CHAT,array('id'=>$v['id']));
		}
		pdo_delete(BEST_FANSKEFU, array('kefuopenid' => $cservice['content']));
		pdo_delete(BEST_BIAOQIAN, array('kefuopenid' => $cservice['content']));
		pdo_delete(BEST_PINGJIA, array('kefuopenid' => $cservice['content']));
		pdo_delete(BEST_ZHUIZONG,array('kefuopenid'=>$cservice['content']));
		pdo_delete(BEST_KUAIJIE,array('kfid'=>$cservice['id']));
	}
	pdo_delete(BEST_CSERVICE, array('id' => $id));
	message('删除客服信息成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
}elseif($operation == 'search') {
	$zzkey = trim($_GPC['zzkey']);
	$merres = pdo_fetchall("SELECT openid,nickname FROM ".tablename('mc_mapping_fans')." WHERE uniacid = {$_W['uniacid']} AND nickname like '%{$zzkey}%'");
	if(empty($merres)){
		$resArr['error'] = 1;
		$resArr['merres'] = "没有搜索到粉丝哦！";
	}else{
		$html = "";
		foreach($merres as $k=>$v){
			$html .= '<tr><td>'.$v['nickname'].'</td><td>'.$v['openid'].'</td>
				<td style="text-align:right;">
				<button type="button" class="btn btn-info btn-sm xqopenid" data-openid="'.$v['openid'].'">选取</button>
				</td></tr>';
		}
		$resArr['error'] = 0;
		$resArr['html'] = $html;
		$resArr['merres'] = $merres;
	}
	echo json_encode($resArr,true);
	exit;
}elseif($operation == 'kuaijie') {
	$id = intval($_GPC['id']);
	$cservice = pdo_fetch("SELECT id,name FROM " . tablename(BEST_CSERVICE) . " WHERE id = {$id}");
	if (empty($cservice)) {
		message('抱歉，该客服信息不存在或是已经被删除！', $this->createWebUrl('cservice', array('op' => 'display')), 'error');
	}
	$kuaijies = pdo_fetchall("SELECT * FROM " . tablename(BEST_KUAIJIE) . " WHERE kfid = {$id} AND weid = {$_W['uniacid']} ORDER BY displayorder DESC");
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update(BEST_KUAIJIE, array('displayorder' => $displayorder), array('id' => $id, 'weid' => $_W['uniacid']));
		}
		message('快捷消息排序更新成功！', $_SERVER['HTTP_REFERER'], 'success');
	}
	include $this->template('web/cservice');
}elseif($operation == 'dokuaijie') {
	$kfid = intval($_GPC['kfid']);
	$cservice = pdo_fetch("SELECT id FROM " . tablename(BEST_CSERVICE) . " WHERE id = {$kfid} AND ctype = 1");
	if (empty($cservice)) {
		message('抱歉，该客服信息不存在或是已经被删除！');
	}
	$kjtype = intval($_GPC['kjtype']);
	if($kjtype != 0 && $kjtype != 1 && $kjtype != 2){
		message('请选择消息类型！');
	}
	if($kjtype == 0 && $_GPC['con'] == ''){
		message('请填写消息内容！');	
	}
	if($kjtype == 1 && $_GPC['thumb'] == ''){
		message('请上传消息图片！');
	}
	if($kjtype == 2 && $_GPC['allcon'] == ''){
		message('请填写图文内容！');
	}
	
	$thumb = $_GPC['thumb'];
	$data= array(
		'weid'=>$_W['uniacid'],
		'kfid'=>$kfid,
		'kjtype'=>$kjtype,
		'con'=>$_GPC['con'],
		'thumb'=>$_GPC['thumb'],
		'allcon'=>$_GPC['allcon'],
	);
	pdo_insert(BEST_KUAIJIE,$data);
	message('操作成功！', $this->createWebUrl('cservice', array('op' => 'kuaijie','id'=>$kfid)), 'success');
}elseif ($operation == 'deletekj') {
	$id = intval($_GPC['id']);
	$kuaijie = pdo_fetch("SELECT id,kfid FROM " . tablename(BEST_KUAIJIE) . " WHERE id = {$id}");
	if (empty($kuaijie)) {
		message('抱歉，该客服信息不存在或是已经被删除！');
	}
	pdo_delete(BEST_KUAIJIE, array('id' => $id));
	message('删除成功！', $this->createWebUrl('cservice', array('op' => 'kuaijie','id'=>$kuaijie['kfid'])), 'success');
}
?>