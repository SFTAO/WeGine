<?php
/**
 * 万能客服模块微站定义
 *
 * @author 梅小西
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', IA_ROOT.'/addons/cy163_customerservice/');
define('MD_ROOT', '../addons/cy163_customerservice/');
define('BEST_CHAT', 'messikefu_chat');//聊天表
define('BEST_CSERVICE', 'messikefu_cservice');//客服表
define('BEST_CSERVICEGROUP', 'messikefu_cservicegroup');//客服组表
define('BEST_BIAOQIAN', 'messikefu_biaoqian');//粉丝标签
define('BEST_GROUP', 'messikefu_group');//群聊表
define('BEST_DOMAIN', 'http://we7.qiumipai.com/domain.php');//第三方
define('BEST_GROUPMEMBER', 'messikefu_groupmember');//群聊会员表
define('BEST_GROUPCONTENT', 'messikefu_groupchat');//群聊内容表
define('BEST_FANSKEFU', 'messikefu_fanskefu');//粉丝客服表
define('BEST_ADV', 'messikefu_adv');//幻灯片表
define('BEST_SANFANSKEFU', 'messikefu_sanfanskefu');//第三方
define('BEST_SANCHAT', 'messikefu_sanchat');//第三方
define('BEST_KEFUANDGROUP', 'messikefu_kefuandgroup');//第三方
define('BEST_PINGJIA', 'messikefu_pingjia');//评价
define('BEST_WENZHANG', 'messikefu_wenzhang');//评价
define('BEST_KEFUANDCJWT', 'messikefu_kefuandcjwt');//第三方
define('BEST_ZIDONGHUIFU', 'messikefu_zdhf');//第三方
define('BEST_XCX', 'messikefu_xcx');//第三方
define('BEST_XCXCSERVICE', 'messikefu_xcxcservice');//第三方
define('BEST_XCXFANSKEFU', 'messikefu_xcxfanskefu');//第三方
define('BEST_XCXCHAT', 'messikefu_xcxchat');//第三方
define('BEST_TISHI','1%e3%80%81%e5%b0%8f%e7%a8%8b%e5%ba%8f%e5%ae%a2%e6%9c%8d%e5%8a%9f%e8%83%bd%e9%a1%bb%e6%8e%88%e6%9d%83%e6%89%8d%e8%83%bd%e4%bd%bf%e7%94%a8%ef%bc%8c%e8%af%b7%e8%81%94%e7%b3%bb%e8%b4%9f%e8%b4%a3%e4%ba%ba%e5%a4%84%e7%90%86%ef%bc%81%ef%bc%81%ef%bc%81
2%e3%80%81%e8%af%b7%e9%80%89%e6%8b%a9%e4%bb%bb%e6%84%8f%e5%85%ac%e4%bc%97%e5%8f%b7%e5%90%8e%e5%8f%b0%e6%93%8d%e4%bd%9c%e5%b0%8f%e7%a8%8b%e5%ba%8f%e5%ae%a2%e6%9c%8d%e5%8a%9f%e8%83%bd%ef%bc%81%ef%bc%81%ef%bc%81');
define('BEST_XCXAUTO', 'messikefu_xcxauto');//第三方
define('BEST_ZHUIZONG', 'messikefu_zhuizong');//第三方
define('BEST_GROUPVOICEDU', 'messikefu_groupvoicedu');//第三方

function file_get_contents_post($url, $post){
	$options = array(
		'http'=> array(
			'method'=>'POST',
			'content'=> http_build_query($post),
		),
	);
	$result = file_get_contents($url,false, stream_context_create($options));
	return $result;
}
class Cy163_customerserviceModuleSite extends WeModuleSite {
	
	public function __construct(){
		
	}
	
	public function doMobileShenqingqun(){
		global $_W, $_GPC;
		if(empty($_W['fans']['from_user'])){
			$message = '请在微信浏览器中打开！';
			include $this->template('error');
			exit;
		}
		$groupid = intval($_GPC['groupid']);
		if(empty($groupid)){
			$message = '参数传输错误！';
			include $this->template('error');
			exit;
		}
		$hasgroup = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUP)." WHERE weid = {$_W['uniacid']} AND id = {$groupid}");
		if(empty($hasgroup)){
			$message = '不存在该群聊！';
			include $this->template('error');
			exit;
		}
		if($hasgroup['isguanzhu'] == 1 && $_W['fans']['follow'] == 0){
			$message = '关注公众号才能申请进群！';
			include $this->template('error');
			exit;
		}
		if($hasgroup['maxnum'] != 0){
			$ingroupnum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$groupid}");
			if($ingroupnum >= $hasgroup['maxnum']){
				$message = '该群聊人数已满！';
				include $this->template('error');
				exit;
			}
		}
		$hasshenqing = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid}");
		if(!empty($hasshenqing)){
			$message = '您已申请加入该群聊！';
			include $this->template('error');
			exit;
		}
		$iscservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
		if(!empty($iscservice)){
			$data['nickname'] = $iscservice['name'];
			$data['avatar'] = tomedia($iscservice['thumb']);
			$data['type'] = 2;
		}else{
			$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
			$data['avatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($this->module['config']['defaultavatar']) : $_W['fans']['tag']['avatar'];
			$data['type'] = 1;
		}
		$data['groupid'] = $groupid;
		$data['weid'] = $_W['uniacid'];
		$data['openid'] = $_W['fans']['from_user'];
		if($hasgroup['isshenhe'] == 1){
			$data['status'] = 1;
		}
		if($hasgroup['autotx'] == 1){
			$data['txkaiguan'] = 1;
		}
		pdo_insert(BEST_GROUPMEMBER,$data);
		//提醒管理员审核
		if($hasgroup['isshenhe'] == 0){
			$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("guanligroup",array('groupid'=>$groupid)));
			$concon = $data['nickname'].'申请加入'.$hasgroup['groupname'].'！';			
			$row = array();
			$row['title'] = urlencode('新消息提醒');
			$row['description'] = urlencode($concon);
			$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
			$row['url'] = $texturl;
			$news[] = $row;
			$send['touser'] = $hasgroup['admin'];
			$send['msgtype'] = 'news';
			$send['news']['articles'] = $news;
			$account_api = WeAccount::create();
			$account_api->sendCustomNotice($send);
		}
		$message = '提交申请成功！';
		include $this->template('shenqingqun');
	}
	
	public function guolv($content){
		if(!empty($this->module['config']['mingan'])){
			$sensitivewordarr = explode("|",$this->module['config']['mingan']);
			foreach($sensitivewordarr as $k=>$v){
				if(!empty($v)){
					$content = str_replace($v,"***",$content);
				}
			}
		}
		$content = str_replace("\n","<br>",$content);
		return $content;
	}
	
	public function doMobileNtest(){
		global $_GPC, $_W;
		include $this->template('ntest');
	}
	
	public function doMobileQdadmin(){
		include_once ROOT_PATH.'inc/mobile/qdadmin.php';
	}
	
	public function doMobileXcxqdadmin(){
		include_once ROOT_PATH.'inc/mobile/xcxqdadmin.php';
	}
	
	public function doMobileGroupcenter(){
		global $_GPC, $_W;
		$op = trim($_GPC['op']);
		if($op == 'search'){
			if(empty($_W['fans']['from_user'])){
				$resarr['error'] = 1;
				$resarr['msg'] = '请在微信浏览器中打开！';
				echo json_encode($resarr);
				exit;
			}
			$qunname = trim($_GPC['qunname']);
			if(empty($qunname)){
				$resarr['error'] = 1;
				$resarr['msg'] = '请输入群名称查询！';
				echo json_encode($resarr);
				exit;
			}
			$group = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUP)." WHERE groupname like '%{$qunname}%' AND weid = {$_W['uniacid']}");
			if(empty($group)){
				$resarr['error'] = 1;
				$resarr['msg'] = '没有这个群聊！';
				echo json_encode($resarr);
				exit;
			}else{
				if($group['admin'] == $_W['fans']['from_user']){
					$groupbtn = '<div class="buttons"><a href="'.$this->createMobileUrl('guanligroup',array('groupid'=>$group['id'])).'">管理群</a></div>';
				}else{
					$groupbtn = '';
				}
				$resarr['error'] = 0;
				$resarr['html'] = '<div class="item flex">
										<img src="'.tomedia($group['thumb']).'">
										<a href="'.$this->createMobileUrl('groupchatdetail',array('groupid'=>$group['id'])).'" style="flex:1;color:#666;">
											<div class="text textellipsis1">'.$group['groupname'].'</div>
										</a>
										'.$groupbtn.'
									</div>';
				echo json_encode($resarr);
				exit;
			}
		}else{
			if(empty($_W['fans']['from_user'])){
				$message = '请在微信浏览器中打开！';
				include $this->template('error');
				exit;
			}
			$grouplist = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUP)." WHERE weid = {$_W['uniacid']} ORDER BY time DESC");
			$iscservice = pdo_fetch("SELECT id FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
			if(!empty($iscservice)){
				$notread = pdo_fetchcolumn("SELECT SUM(a.notread) FROM ".tablename(BEST_FANSKEFU)." as a,".tablename(BEST_CHAT)." as b WHERE a.weid = {$_W['uniacid']} AND a.kefuopenid = '{$_W['fans']['from_user']}' AND a.id = b.fkid AND b.kefudel = 0");
			}else{
				$notread = pdo_fetchcolumn("SELECT SUM(a.kefunotread) FROM ".tablename(BEST_FANSKEFU)." as a,".tablename(BEST_CHAT)." as b WHERE a.weid = {$_W['uniacid']} AND a.fansopenid = '{$_W['fans']['from_user']}' AND a.id = b.fkid AND b.fansdel = 0");
			}
			$this->module['config']['shareurl'] = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl('groupcenter'));		
			include $this->template('groupcenter');
		}
	}
	
	public function doMobileGuanligroup(){
		global $_GPC, $_W;
		if(empty($_W['fans']['from_user'])){
			$message = '请在微信浏览器中打开！';
			include $this->template('error');
			exit;
		}
		$groupid = intval($_GPC['groupid']);
		$isgroupadmin = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUP)." WHERE weid = {$_W['uniacid']} AND id = {$groupid} AND admin = '{$_W['fans']['from_user']}'");
		if(empty($isgroupadmin)){
			$message = '你不是管理员，不能管理该群聊！';
			include $this->template('error');
			exit;
		}
		$op = trim($_GPC['op']);
		if($op == ''){
			$groupmemberlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND openid != '{$_W['fans']['from_user']}' AND isdel = 0 ORDER BY status ASC,id DESC");
			include $this->template('guanligroup');
		}elseif($op == 'del'){
			$groupid = intval($_GPC['groupid']);
			$id = intval($_GPC['memberid']);
			$groupmember = pdo_fetch("SELECT openid FROM ".tablename(BEST_GROUPMEMBER)." WHERE id = {$id} AND isdel = 0");
			if(empty($groupmember)){
				$resarr['error'] = 1;
				$resarr['msg'] = '不存在该用户记录！';
				echo json_encode($resarr);
				exit;
			}
			pdo_update(BEST_GROUPMEMBER,array('isdel'=>1),array('groupid'=>$groupid,'openid'=>$groupmember['openid']));
			$resarr['error'] = 0;
			$resarr['msg'] = '操作成功！';
			echo json_encode($resarr);
			exit;
		}elseif($op == 'shenhe'){
			$groupid = intval($_GPC['groupid']);
			$id = intval($_GPC['memberid']);
			$groupmember = pdo_fetch("SELECT openid FROM ".tablename(BEST_GROUPMEMBER)." WHERE id = {$id}");
			pdo_update(BEST_GROUPMEMBER,array('status'=>1,'intime'=>TIMESTAMP),array('id'=>$id));
			
			//提醒已被审核进群
			$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("groupchatdetail",array('groupid'=>$groupid)));
			
			$row = array();
			$row['title'] = urlencode('新消息提醒');
			$row['description'] = urlencode('您已被审核进群！');
			$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
			$row['url'] = $texturl;
			$news[] = $row;
			$send['touser'] = $groupmember['openid'];
			$send['msgtype'] = 'news';
			$send['news']['articles'] = $news;
			$account_api = WeAccount::create();
			$account_api->sendCustomNotice($send);
			$resarr['error'] = 0;
			$resarr['msg'] = '操作成功！';
			echo json_encode($resarr);
			exit;
		}
	}
	
	public function doMobileGrouptongzhi(){
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$type = intval($_GPC['type']);
		$isin = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND id = {$id} AND status = 1");
		if(empty($isin)){
			$resArr['error'] = 1;
			$resArr['msg'] = '你不属于该群聊！';
			echo json_encode($resArr);
			exit;
		}
		$data['txkaiguan'] = $type;
		pdo_update(BEST_GROUPMEMBER,$data,array('id'=>$id));
		$resArr['error'] = 1;
		$resArr['msg'] = '操作成功！';
		echo json_encode($resArr);
		exit;
	}
	
	
	public function doMobileGroupchatdetail(){
		global $_GPC, $_W;
		if(empty($_W['fans']['from_user'])){
			$message = '请在微信浏览器中打开！';
			include $this->template('error');
			exit;
		}
		$groupid = intval($_GPC['groupid']);
		$group = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUP)." WHERE id = {$groupid}");
		if(empty($group)){
			$message = '不存在'.$group['groupname'].'！';
			include $this->template('error');
			exit;
		}
		$isin = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid} AND status = 1 AND isdel = 0");
		if(empty($isin)){
			$hasshenqing = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND status = 0 AND groupid = {$groupid} AND isdel = 0");
			if(!empty($hasshenqing)){
				$message = $group['groupname'].'须审核才能入群，您已提交申请，请等待审核！';
				include $this->template('error');
				exit;
			}else{
				pdo_delete(BEST_GROUPMEMBER,array('groupid'=>$groupid,'openid'=>$_W['fans']['from_user']));
				if($group['isguanzhu'] == 1 && $_W['fans']['follow'] == 0){
					$message = '关注公众号才能申请'.$group['groupname'].'！';
					include $this->template('error');
					exit;
				}
				if($group['maxnum'] != 0){
					$ingroupnum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND isdel = 0");
					if($ingroupnum >= $group['maxnum']){
						$message = $group['groupname'].'人数已满！';
						include $this->template('error');
						exit;
					}
				}
				
				$iscservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
				if(!empty($iscservice)){
					$data['nickname'] = $iscservice['name'];
					$data['avatar'] = tomedia($iscservice['thumb']);
					$data['type'] = 2;
				}else{
					$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : $_W['fans']['tag']['nickname'];
					$data['avatar'] = empty($_W['fans']['tag']['avatar']) ? tomedia($this->module['config']['defaultavatar']) : $_W['fans']['tag']['avatar'];
					$data['type'] = 1;
				}
				$data['groupid'] = $groupid;
				$data['weid'] = $_W['uniacid'];
				$data['openid'] = $_W['fans']['from_user'];
				if($group['autotx'] == 1){
					$data['txkaiguan'] = 1;
				}
				if($group['isshenhe'] == 1){
					$data['status'] = 1;
					$data['intime'] = TIMESTAMP;
					pdo_insert(BEST_GROUPMEMBER,$data);
					$isin = pdo_fetch("SELECT id,intime,avatar,nickname,txkaiguan FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid} AND status = 1 AND isdel = 0");
				}else{
					pdo_insert(BEST_GROUPMEMBER,$data);
					//提醒管理员审核
					$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("guanligroup",array('groupid'=>$groupid)));
					$concon = $data['nickname'].'申请加入'.$group['groupname'].'！';
					$row = array();
					$row['title'] = urlencode('新消息提醒');
					$row['description'] = urlencode($concon);
					$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
					$row['url'] = $texturl;
					$news[] = $row;
					$send['touser'] = $group['admin'];
					$send['msgtype'] = 'news';
					$send['news']['articles'] = $news;
					$account_api = WeAccount::create();
					$account_api->sendCustomNotice($send);
					$message = $group['groupname'].'必须审核才能入群，您已提交申请，请等待审核！';
					include $this->template('error');
					exit;
				}
			}
		}
		$allmemberlist = pdo_fetchall("SELECT openid FROM ".tablename(BEST_GROUPMEMBER)." WHERE groupid = {$groupid} AND openid != '{$_W['fans']['from_user']}' AND status = 1 AND isdel = 0");
		$allpeople = count($allmemberlist);
		$allmember = '';
		foreach($allmemberlist as $k=>$v){
			$allmember .= $v['openid'].$groupid."|";
		}
		$allmember = substr($allmember,0,-1);
		$timestamp = TIMESTAMP;
		$quickcon = empty($group['quickcon']) ? '' : explode("|",$group['quickcon']);
		if($group['autoreply']){			
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$group['autoreply'],$array2);  
			if(!empty($array2[0])){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$group['autoreply'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$group['autoreply']);
					}
				}
			}
		}
		
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_GROUPCONTENT)." WHERE groupid = {$groupid} AND weid = {$_W['uniacid']} AND time >= {$isin['intime']}");
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		$groupcontent = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND time >= {$isin['intime']} ORDER BY time ASC LIMIT ".$nowjl.",".$psize);
		$chatcontime = 0;
		foreach($groupcontent as $k=>$v){			
			if(($v['time'] - $chatcontime) > 7200){
				$groupcontent[$k]['time'] = $v['time'];
			}else{
				$groupcontent[$k]['time'] = '';
			}
			if($v['openid'] != $_W['fans']['from_user']){
				$groupcontent[$k]['class'] = 'left';
			}else{
				$groupcontent[$k]['class'] = 'right';
			}

			$chatcontime = $v['time'];
			$groupcontent[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$groupcontent[$k]['content'] = $this->guolv($groupcontent[$k]['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$groupcontent[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$groupcontent[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$groupcontent[$k]['content']);
					}
				}
			}
			if($v['type'] == 5){
				$donetime = $timestamp - $v['time'];
				if($donetime >= 24*3600*3){
					unset($groupcontent[$k]);
				}else{
					$hasgroupvoicedu = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPVOICEDU)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid} AND gchatid = {$v['id']}");
					if(!empty($hasgroupvoicedu)){
						$groupcontent[$k]['hasgroupvoicedu'] = 1;
					}
				}
			}
		}
		include $this->template('groupdetail');
	}
	
	public function doMobileDugroupvoice(){
		global $_W,$_GPC;
		$openid = $_W['fans']['from_user'];
		$groupid = intval($_GPC['groupid']);
		$chatcon = trim($_GPC['gchatcon']);
		$groupchat = pdo_fetch("SELECT id,time FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND content = '{$chatcon}' AND type = 5");
		if(empty($groupchat)){
			$resArr['error'] = 1;
			$resArr['message'] = "暂无这条群聊语音！";
			echo json_encode($resArr);
			exit;
		}
		$hasgroupvoicedu = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPVOICEDU)." WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' AND groupid = {$groupid} AND gchatid = {$groupchat['id']}");
		$nowtime = TIMESTAMP;
		if(empty($hasgroupvoicedu)){
			$data = array(
				'weid'=>$_W['uniacid'],
				'groupid'=>$groupid,
				'gchatid'=>$groupchat['id'],
				'openid'=>$openid,
				'content'=>$chatcon,
				'time'=>$nowtime
			);
			pdo_insert(BEST_GROUPVOICEDU,$data);
		}
		$donetime = $nowtime - 24*3600*3;
		$nextvoice = pdo_fetch("SELECT content FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND openid != '{$openid}' AND groupid = {$groupid} AND time > {$donetime} AND time > {$groupchat['time']} AND type = 5 ORDER BY time ASC");
		$resArr['error'] = 0;
		$resArr['content'] = $nextvoice['content'];
		$resArr['message'] = "读取语音成功！";
		echo json_encode($resArr);
		exit;
	}
	
	
	public function doMobileGroupchatajax(){
		global $_W,$_GPC;
		$openid = $_W['fans']['from_user'];
		$groupid = intval($_GPC['groupid']);
		$isin = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$openid}' AND groupid = {$groupid} AND status = 1 AND isdel = 0");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_GROUPCONTENT)." WHERE groupid = {$groupid} AND weid = {$_W['uniacid']} AND time >= {$isin['intime']}");
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		if($total > $pindex*$psize){
			$tolimit = $psize;
		}else{
			$tolimit = $psize-($pindex*$psize-$total);
		}		
		$groupcontent = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND time >= {$isin['intime']} ORDER BY time ASC LIMIT ".$nowjl.",".$tolimit);
		$chatcontime = 0;
		foreach($groupcontent as $k=>$v){			
			if(($v['time'] - $chatcontime) > 7200){
				$groupcontent[$k]['time'] = $v['time'];
			}else{
				$groupcontent[$k]['time'] = '';
			}
			$chatcontime = $v['time'];
			$groupcontent[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$groupcontent[$k]['content'] = $this->guolv($groupcontent[$k]['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$groupcontent[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$groupcontent[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$groupcontent[$k]['content']);
					}
				}
			}
			if($v['type'] == 5){
				$donetime = $timestamp - $v['time'];
				if($donetime >= 24*3600*3){
					unset($groupcontent[$k]);
				}
			}
		}
		$html = '';
		foreach($groupcontent as $k=>$v){
			$htmltime = !empty($v['time']) ? '<div class="time text-c">'.date('Y-m-d H:i:s',$v['time']).'</div>' : '';
			if($v['openid'] != $openid){
				$class = 'left';
				$conhtml = '<div class="groupnickname n-left">'.$v['nickname'].'</div><div class="con flex1 flex" style="margin-top:0.4rem;">';
			}else{
				$class = 'right';
				$conhtml = '<div class="con flex1 flex">';
			}	
			if($v['type'] == 3 || $v['type'] == 4){
				$chatconhtml = '<div class="concon"><img src="'.$v['content'].'" class="sssbbb" /></div>';
			}elseif($v['type'] == 5){
				if($v['hasyuyindu'] == 0 && $openid == $v['toopenid']){
					$weidu = '<span class="weidu">未读</span>';
				}else{
					$weidu = '';
				}
				$chatconhtml = '<div class="concon voiceplay flex1">
									<i class="a-icon iconfont">&#xe601;</i>
									<span class="miao">'.$v['yuyintime'].'\'\'</span>
									'.$weidu.'
									<div class="flex1"></div>
								</div>';
			}else{
				$chatconhtml = '<div class="concon">'.$v['content'].'</div>';
			}
			$html .= $htmltime.'<div class="'.$class.' flex">
									<img src="'.$v['avatar'].'" class="avatar" />
									'.$conhtml.'
										<div class="triangle-'.$class.'"></div>
										'.$chatconhtml.'
										<div class="flex1"></div>
									</div>
								</div>';
		}
		echo $html;
		exit;
	}
	
	public function doMobileTuichuqun(){
		global $_GPC, $_W;
		$groupid= intval($_GPC['groupid']);
		$openid = $_W['fans']['from_user'];
		if($groupid == 0 || $openid == ''){
			$resArr['error'] = 1;
			$resArr['msg'] = '参数传输错误！';
			echo json_encode($resArr);
			exit;
		}
		$group = pdo_fetch("SELECT groupname,admin FROM ".tablename(BEST_GROUP)." WHERE weid = {$_W['uniacid']} AND id = {$groupid}");
		if($group['admin'] == $openid){
			$resArr['error'] = 1;
			$resArr['msg'] = '管理员不能退群！';
			echo json_encode($resArr);
			exit;
		}
		$has = pdo_fetch("SELECT id,nickname FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND openid = '{$openid}'");
		if(empty($has)){
			$resArr['error'] = 1;
			$resArr['msg'] = '您不是群成员！';
			echo json_encode($resArr);
			exit;
		}
		$datag['isdel'] = 1;
		pdo_update(BEST_GROUPMEMBER,$datag,array('weid'=>$_W['uniacid'],'groupid'=>$groupid,'openid'=>$openid));
		//提醒管理员
		$concon = $has['nickname'].'退出了'.$group['groupname'].'！';
		$send['touser'] = $group['admin'];
		$send['msgtype'] = 'text';
		$send['text'] = array('content' => urlencode($concon));
		$acc = WeAccount::create($_W['uniacid']);
		$res = $acc->sendCustomNotice($send);
		$resArr['error'] = 1;
		$resArr['msg'] = '退出成功！';
		echo json_encode($resArr);
		exit;
	}
	
	public function doWebAdv() {
		include_once ROOT_PATH.'inc/web/adv.php';
	}
	
	public function doWebKehu() {
		include_once ROOT_PATH.'inc/web/kehu.php';
	}
	
	public function doWebZaixian() {
		include_once ROOT_PATH.'inc/web/zaixian.php';
	}
	
	public function doWebCjwt() {
		include_once ROOT_PATH.'inc/web/cjwt.php';
	}
	
	public function doWebZdhf() {
		include_once ROOT_PATH.'inc/web/zdhf.php';
	}
	
	public function doWebTongji() {
		include_once ROOT_PATH.'inc/web/tongji.php';
	}
	
	public function doWebXcx() {
		include_once ROOT_PATH.'inc/web/xcx.php';
	}
	
	public function doWebXcxcservice() {
		include_once ROOT_PATH.'inc/web/xcxcservice.php';
	}
	
	public function isValid($xcx){
		$token = $xcx['token'];
		$echoStr = $_GET["echostr"];
		if ($this->checkSignature($xcx)) {
			echo $echoStr;
			exit;
		}
	}
	
	public function checkSignature($xcx){
		$token = $xcx['token'];
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if($tmpStr == $signature){
			pdo_update(BEST_XCX,array('status'=>1),array('id'=>$xcx['id']));
			return true;
		}else{
			pdo_update(BEST_XCX,array('status'=>2),array('id'=>$xcx['id']));
			return false;
		}
	}
	
	public function doMobileXcxjt() {
		global $_GPC, $_W;
		$xcxid= intval($_GPC['id']);
		$xcx = pdo_fetch("SELECT * FROM ".tablename(BEST_XCX)." WHERE id = {$xcxid}");
		if (isset($_GET['echostr'])) {
            $this->isValid($xcx);
        }else{
            $this->responseMsg();
        }
	}
	
	public function testwri($txt){
		$myfile = fopen("xcx.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $txt);
		fclose($myfile);
	}
	
	public function responseMsg(){
        //$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postStr = file_get_contents("php://input");
		//$this->testwri($postStr);
        if (!empty($postStr) && is_string($postStr)){
            //禁止引用外部xml实体
            //libxml_disable_entity_loader(true);
            
            //$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $postArr = json_decode($postStr,true);
            if(!empty($postArr['MsgType']) && $postArr['MsgType'] == 'text'){   //文本消息
                /*$fromUsername = $postArr['FromUserName'];   //发送者openid
                $toUserName = $postArr['ToUserName'];       //小程序id
                $textTpl = array(
                    "ToUserName"=>$fromUsername,
                    "FromUserName"=>$toUserName,
                    "CreateTime"=>time(),
                    "MsgType"=>"transfer_customer_service",
                );
                exit(json_encode($textTpl));*/
				$this->addxcxchat($postArr);
            }elseif(!empty($postArr['MsgType']) && $postArr['MsgType'] == 'image'){ //图文消息
                 /*$fromUsername = $postArr['FromUserName'];   //发送者openid
                $toUserName = $postArr['ToUserName'];       //小程序id
                $textTpl = array(
                    "ToUserName"=>$fromUsername,
                    "FromUserName"=>$toUserName,
                    "CreateTime"=>time(),
                    "MsgType"=>"transfer_customer_service",
                );
                exit(json_encode($textTpl));*/
				$this->addxcxchat($postArr);
            }elseif($postArr['MsgType'] == 'event' && $postArr['Event']=='user_enter_tempsession'){ //进入客服会话动作
                /*$fromUsername = $postArr['FromUserName'];   //发送者openid
                $content = '您好，有什么能帮助你?';
                $data=array(
                    "touser"=>$fromUsername,
                    "msgtype"=>"text",
                    "text"=>array("content"=>$content)
                );
                $json = json_encode($data,JSON_UNESCAPED_UNICODE);  //php5.4+*/
                
                $this->addxcxfanskefu($postArr);
                //以'json'格式发送post的https请求
            }else{
                exit('aaa');
            }
        }else{
            echo "success";
            exit;
        }
    }
	
	public function addxcxchat($postArr){
		global $_GPC, $_W;
		include_once ROOT_PATH.'emoji/emoji.php';
		$xcx = pdo_fetch("SELECT * FROM ".tablename(BEST_XCX)." WHERE uniacid = {$_W['uniacid']} AND gh_id = '{$postArr['ToUserName']}'");
		$fansopenid = $postArr['FromUserName'];
		$gh_id = $postArr['ToUserName'];
		$has = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$fansopenid}' AND nowkefu = 1 AND gh_id = '{$gh_id}'");
		
		$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXCSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$has['kefuopenid']}'");
		$kefuopenid = $cservice['content'];
		if(!empty($has)){
			if($postArr['MsgType'] == 'text'){
				$content = emoji_docomo_to_unified($postArr['Content']);
				$content = emoji_unified_to_html($content);
				$data['content'] = $content;
				$dataup['lastcon'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $postArr['Content']);
			}
			if($postArr['MsgType'] == 'image'){
				$picres = $this->xcximgsave($postArr['PicUrl']);
				if($picres != ''){
					$data['content'] = $picres;
				}else{
					$data['content'] = $postArr['PicUrl'];
				}
				$data['mediaId'] = $postArr['MediaId'];
				$dataup['lastcon'] = '[图片消息]';
			}
			$data['weid'] = $_W['uniacid'];
			$data['fkid'] = $has['id'];
			$data['openid'] = $fansopenid;
			$data['toopenid'] = $kefuopenid;
			$data['msgtype'] = $postArr['MsgType'];
			$data['gh_id'] = $gh_id;
			$data['time'] = $postArr['CreateTime'];
			$data['msgid'] = $postArr['MsgId'];
			pdo_insert(BEST_XCXCHAT,$data);
			
			$dataup['notread'] = $has['notread'] + 1;
			$dataup['lasttime'] = $postArr['CreateTime'];
			$dataup['msgtype'] = $postArr['MsgType'];
			pdo_update(BEST_XCXFANSKEFU,$dataup,array('id'=>$has['id']));
			
			
			$hasauto = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXAUTO)." WHERE weid = {$_W['uniacid']} AND kfid = {$cservice['id']} AND iszdhf = 1 AND zdhftype = 1 AND zdhftitle = '{$data['content']}'");
			if(empty($hasauto)){
				$hasauto = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXAUTO)." WHERE weid = {$_W['uniacid']} AND kfid = {$cservice['id']} AND iszdhf = 1 AND zdhftype = 0 AND zdhftitle like '%{$data['content']}%'");
			}
			if(!empty($hasauto)){
				if($hasauto['msgtype'] == "text"){
					$chatcontent = $hasauto['title'];
					$addres = $this->addxcxchat2($has['fansopenid'],$hasauto['title'],$hasauto['msgtype'],$has['gh_id']);
				}
				if($hasauto['msgtype'] == "image"){
					$chatcontent = $hasauto['thumb'] = tomedia($hasauto['thumb']);
					$addres = $this->addxcxchat2($has['fansopenid'],$hasauto['thumb'],$hasauto['msgtype'],$has['gh_id']);
				}
				if($hasauto['msgtype'] == "link"){
					$zdconlink['title'] = $hasauto['title'];
					$zdconlink['description'] = $hasauto['description'];
					$zdconlink['url'] = $hasauto['url'];
					$zdconlink['thumb_url'] = tomedia($hasauto['thumb_url']);
					$addres = $this->addxcxchat2($has['fansopenid'],$zdconlink,$hasauto['msgtype'],$has['gh_id']);
				}
				
				if($addres['errcode'] == "0"){
					if($hasauto['msgtype'] == "link"){
						$datachat['title'] = $hasauto['title'];
						$datachat['description'] = $hasauto['description'];
						$datachat['url'] = $hasauto['url'];
						$datachat['thumb_url'] = tomedia($hasauto['thumb_url']);
					}else{
						$datachat['content'] = $chatcontent;
					}
					$datachat['openid'] = $has['kefuopenid'];
					$datachat['toopenid'] = $has['fansopenid'];
					$datachat['gh_id'] = $has['gh_id'];
					$datachat['time'] = TIMESTAMP;
					$datachat['weid'] = $_W['uniacid'];
					$datachat['fkid'] = $has['id'];
					$datachat['msgtype'] = $hasauto['msgtype'];
					pdo_insert(BEST_XCXCHAT,$datachat);
				}
			}
			
			$post_url = 'https://api.qiumipai.com:2121/?type=xcxpublish&to='.$has['kefuopenid'].'&content='.$data['content'].'&msgtype='.$data['msgtype'].'&toopenid='.$has['fansopenid'];
			load()->func('communication'); 
			ihttp_request($post_url);
			$this->xcxtzkefu($kefuopenid,$has['lasttime'],$has['id'],$dataup['lastcon'],$xcx['name'],$has['fansnickname']);
		}
	}
	
	public function xcximgsave($picUrl){
		global $_W,$_GPC;
		$updir = "../attachment/images/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/";
        if (!file_exists($updir)) {
            mkdir($updir, 0777, true);
        }
		$randimgurl = "images/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/".date('YmdHis').rand(1000,9999).'.jpg';
        $targetName = "../attachment/".$randimgurl;
        $ch = curl_init($picUrl); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
		if(file_exists($targetName)){
			$this->mkThumbnail($targetName,640,0,$targetName);
			//上传到远程
			if($this->module['config']['isqiniu'] == 1){
				$remotestatus = $this->doQiuniu($randimgurl,true);
				if (is_error($remotestatus)) {
					return 0;
					exit;
				} else {
					return $this->module['config']['qiniuurl']."/".$randimgurl;
					exit;
				}
			}elseif($this->module['config']['isqiniu'] == 3){
				if(!empty($_W['setting']['remote']['type'])){
					load()->func('file');
					$remotestatus = file_remote_upload($randimgurl,true);
					if (is_error($remotestatus)) {
						return 0;
						exit;
					} else {
						return tomedia($randimgurl);
						exit;
					}
				}
			}
			return tomedia($randimgurl);
			exit;
		}else{
			return 0;
			exit;
		}
	}
	
	
	public function doMobileServicechatajaxxcx(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE id = {$fkid}");
		
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_XCXCHAT)." WHERE fkid = {$fkid}");		
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		if($total > $pindex*$psize){
			$tolimit = $psize;
		}else{
			$tolimit = $psize-($pindex*$psize-$total);
		}
		$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCHAT)." WHERE fkid = {$fkid} ORDER BY time ASC LIMIT ".$nowjl.",".$tolimit);
		$timestamp = TIMESTAMP;
		$chatcontime = 0;
		foreach($chatcon as $k=>$v){
			if(($v['time'] - $chatcontime) > 7200){
				$chatcon[$k]['time'] = $v['time'];
			}else{
				$chatcon[$k]['time'] = '';
			}
		}
		$html = '';
		foreach($chatcon as $k=>$v){
			$htmltime = !empty($v['time']) ? '<div class="time text-c">'.date('Y-m-d H:i:s',$v['time']).'</div>' : '';
			if($v['openid'] != $hasfanskefu['kefuopenid']){
				if($v['msgtype'] == 'image'){
					$chatconhtml = '<div class="concon"><img src="'.$v['content'].'" class="sssbbb" /></div>';
				}
				if($v['msgtype'] == 'text'){
					$chatconhtml = '<div class="concon">'.$v['content'].'</div>';
				}
				$iimmgg = $hasfanskefu['fansavatar'] != "" ? $hasfanskefu['fansavatar'] : '../addons/cy163_customerservice/static/xcx.png';
				$html .= $htmltime.'<div class="left flex">
										<img src="'.$iimmgg.'" class="avatar" />
										<div class="con flex flex1">
											<div class="triangle-left"></div>
											'.$chatconhtml.'
											<div class="flex1"></div>
										</div>
									</div>';
			}else{
				if($v['msgtype'] == 'image'){
					$chatconhtml = '<div class="concon"><img src="'.$v['content'].'" class="sssbbb" /></div>';
				}
				if($v['msgtype'] == 'text'){
					$chatconhtml = '<div class="concon">'.$v['content'].'</div>';
				}
				$html .= '<div class="right flex">
							<img src="'.$hasfanskefu['kefuavatar'].'" class="avatar" />
							<div class="con flex flex1">
								<div class="triangle-right"></div>
								'.$chatconhtml.'
								<div class="flex1"></div>
							</div>
						</div>';
			}
		}
		echo $html;
		exit;
	}
	
	public function doMobileMychatxcx(){
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$psize = 20;
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_XCXFANSKEFU)." WHERE kefuopenid = '{$openid}' AND lastcon != ''");
			$allpage = ceil($total/$psize)+1;
			$page = intval($_GPC["page"]);
			$pindex = max(1, $page);
			$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE kefuopenid = '{$openid}' AND lastcon != '' ORDER BY notread DESC,lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
			foreach($chatlist as $kk=>$vv){
				$xcxres = pdo_fetch("SELECT name FROM ".tablename(BEST_XCX)." WHERE gh_id = '{$vv['gh_id']}'");
				$biaoqian = pdo_fetch("SELECT name FROM ".tablename(BEST_BIAOQIAN)." WHERE kefuopenid = '{$vv['kefuopenid']}' AND fensiopenid = '{$vv['fansopenid']}'");
				$vv['fansnickname'] = $vv['fansnickname'] == "" ? "用户" : $vv['fansnickname'];
				if(!empty($biaoqian)){
					$chatlist[$kk]['fansnickname'] = '['.$xcxres['name'].']['.$biaoqian['name'].']'.$vv['fansnickname'];
				}else{
					$chatlist[$kk]['fansnickname'] = '['.$xcxres['name'].']'.$vv['fansnickname'];
				}
			}
			
			$isajax = intval($_GPC['isajax']);
			if($isajax == 1){
				$html = '';
				foreach($chatlist as $kk=>$vv){
					if($vv['msgtype'] == 'text'){
						$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['lastcon']);
					}else{
						$con = '<span style="color:#900;">[图片消息]</span>';
					}
					$avatar = $vv['fansavatar'] != "" ? $vv['fansavatar'] : MD_ROOT.'static/xcx.png';
					$mychatbadge = $vv['notread'] > 0 ? '<span class="mychatbadge">'.$vv['notread'].'</span>' : '';
					$html .= '<div class="item flex textellipsis1 fkid'.$vv['id'].'">
								<a href="'.$this->createMobileUrl('xcxchat',array('fkid'=>$vv['id'])).'" class="flex tohref textellipsis1">
									<img src="'.$avatar.'">'.$mychatbadge.'
									<div class="text textellipsis1">
										<div class="name textellipsis1">'.$vv['fansnickname'].'</div>
										<div class="lastmsg textellipsis1">'.$con.'</div>
									</div>
								</a>
								<div class="timedo">
									<div class="time">'.$this->format_date($vv['lasttime']).'</div>
									<div class="dodel" data-fkid="'.$vv['id'].'">删除</div>
								</div>
							</div>';
				}
				echo $html;
				exit;
			}
			include $this->template('mychatxcx');
		}elseif($operation == 'delete'){
			$fkid = intval($_GPC['fkid']);
			pdo_delete(BEST_XCXCHAT,array('fkid'=>$fkid));
			pdo_delete(BEST_XCXFANSKEFU,array('id'=>$fkid));
			$resArr['error'] = 0;
			$resArr['message'] = '恭喜您，删除聊天记录成功！';
			echo json_encode($resArr,true);
			exit;
		}
	}
	
	public function doMobileXcxchat(){
		global $_GPC, $_W;
		include_once ROOT_PATH.'qqface.php';
		$fkid = intval($_GPC['fkid']);
		$openid = $_W['fans']['from_user'];
		if(empty($openid)){
			$message = '请在微信浏览器中打开！';
			include $this->template('error');
			exit;
		}
		$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXCSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$openid}'");
		if(empty($cservice)){
			$message = '你不是客服身份，请联系管理员查看具体信息！';
			include $this->template('error');
			exit;
		}
		$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE id = {$fkid}");
		$xcxres = pdo_fetch("SELECT * FROM ".tablename(BEST_XCX)." WHERE gh_id = '{$hasfanskefu['gh_id']}'");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_XCXCHAT)." WHERE fkid = {$hasfanskefu['id']}");		
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCHAT)." WHERE fkid = {$hasfanskefu['id']} ORDER BY time ASC LIMIT ".$nowjl.",".$psize);
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
		$kefuauto = empty($cservice['kefuauto']) ? '' : explode("|",$cservice['kefuauto']);
		pdo_update(BEST_XCXFANSKEFU,array('notread'=>0),array('id'=>$hasfanskefu['id']));
		
		$biaoqian = pdo_fetch("SELECT * FROM ".tablename(BEST_BIAOQIAN)." WHERE kefuopenid = '{$openid}' AND fensiopenid = '{$hasfanskefu['fansopenid']}'");
		include $this->template("newservicechatxcx");
	}
	
	
	public function doMobileAddchatxcx(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$type = intval($_GPC['type']);
		$chatcontent = trim($_GPC['content']);		
		if(empty($chatcontent)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入对话内容！';
			echo json_encode($resArr);
			exit;
		}
		$fanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE id = {$fkid}");
		if($type == 2){
			$msgtype = 'text';
		}
		if($type == 3){
			$msgtype = 'image';
			$chatcontent = tomedia($chatcontent);
		}
		$addres = $this->addxcxchat2($fanskefu['fansopenid'],$chatcontent,$msgtype,$fanskefu['gh_id']);
		if($addres['errcode'] != "0"){
			$resArr['error'] = 1;
			$resArr['msg'] = $this->getwxerrormsg($addres);
			echo json_encode($resArr);
			exit;
		}else{
			$data['openid'] = $fanskefu['kefuopenid'];
			$data['toopenid'] = $fanskefu['fansopenid'];
			$data['gh_id'] = $fanskefu['gh_id'];
			$data['time'] = TIMESTAMP;
			$data['content'] = $chatcontent;
			$data['weid'] = $_W['uniacid'];
			$data['fkid'] = $fkid;
			$data['msgtype'] = $msgtype;
			pdo_insert(BEST_XCXCHAT,$data);
		
			if($type == 2){
				$resArr['content'] = '<div class="concon">'.$chatcontent.'</div>';
			}else{
				$resArr['content'] = '<div class="concon"><img src="'.$chatcontent.'" class="sssbbb" /></div>';
			}
			$resArr['error'] = 0;
			$resArr['msg'] = '';
			$resArr['datetime'] = date("Y-m-d H:i:s",$data['time']);
			echo json_encode($resArr);
			exit;
		}
	}
	
	public function getwxerrormsg($addres){
		if($addres['errcode'] == "45047"){
			$errmsg = "客服接口下行条数超过上限！";
		}elseif($addres['errcode'] == "48001"){
			$errmsg = "API 功能未授权，请确认小程序已获得该接口！";
		}elseif($addres['errcode'] == "45015"){
			$errmsg = "回复时间超过限制！";
		}elseif($addres['errcode'] == "40003"){
			$errmsg = "不合法的 OpenID，请开发者确认 OpenID 是否是其他小程序的 OpenID！";
		}elseif($addres['errcode'] == "40002"){
			$errmsg = "不合法的凭证类型！";
		}elseif($addres['errcode'] == "40001"){
			$errmsg = "获取 access_token 时 AppSecret 错误，或者 access_token 无效。请开发者认真比对 AppSecret 的正确性，或查看是否正在为恰当的小程序调用接口！";
		}elseif($addres['errcode'] == "-1"){
			$errmsg = "系统繁忙，此时请开发者稍候再试！";
		}else{
			$errmsg = $addres['errmsg'];
		}
		return $errmsg;
	}
	
	public function xcxtzkefu($openid,$lasttime,$fkid,$content,$xcxname,$fansnickname){
		global $_GPC, $_W;
		$guotime = TIMESTAMP-$lasttime;
		if($this->module['config']['istplon'] == 1 && $guotime > $this->module['config']['kefutplminute']){
			if(!empty($this->module['config']['tpl_kefu'])){				
				$or_paysuccess_redirect = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("xcxchat",array('fkid'=>$fkid)));		
				$postdata = array(
					'first' => array(
						'value' => $xcxname.'[小程序]的用户'.$fansnickname.'向你发起了咨询！',
						'color' => '#990000'
					),
					'keyword1' => array(
						'value' => $content,
						'color' => '#ff510'
					),
					'keyword2' => array(
						'value' => "点击此消息尽快回复",
						'color' => '#ff510'
					),
					'remark' => array(
						'value' => '咨询时间：'.date("Y-m-d H:i:s",TIMESTAMP),
						'color' => '#ff510'
					),							
				);
				$account_api = WeAccount::create();
				$account_api->sendTplNotice($openid,$this->module['config']['tpl_kefu'],$postdata,$or_paysuccess_redirect,'#980000');
				
			}else{
				$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("xcxchat",array('fkid'=>$fkid)));	
				$concon = $xcxname.'[小程序]的用户向你发起了咨询！！'.$content.'。';
				$row = array();
				$row['title'] = urlencode('新消息提醒');
				$row['description'] = urlencode($concon);
				$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
				$row['url'] = $texturl;
				$news[] = $row;
				$send['touser'] = $openid;
				$send['msgtype'] = 'news';
				$send['news']['articles'] = $news;
				$account_api = WeAccount::create();
				$account_api->sendCustomNotice($send);
			}
		}
	}
	
	public function addxcxchat2($touser,$content,$msgtype,$gh_id){
		global $_GPC, $_W;
		$xcx = pdo_fetch("SELECT * FROM ".tablename(BEST_XCX)." WHERE uniacid = {$_W['uniacid']} AND gh_id = '{$gh_id}'");
		if($xcx['guoqitime'] < TIMESTAMP){
			$nowtime = TIMESTAMP;
			$access_token = $this->get_xcx_accessToken($xcx['appid'],$xcx['secret']);
			if($access_token == 'error'){
				$resArr['error'] = 1;
				$resArr['msg'] = '获取AccessToken失败！';
				echo json_encode($resArr);
				exit;
			}
			$dataup['access_token'] = $access_token;
			$dataup['guoqitime'] = $nowtime+6500;
			pdo_update(BEST_XCX,$dataup,array('id'=>$xcx['id']));
		}else{
			$access_token = $xcx['access_token'];
		}
		
		if($msgtype == "text"){
			$data = array(
				"touser"=>$touser,
				"msgtype"=>"text",
				"text"=>array("content"=>$content)
			);
		}
		if($msgtype == "image"){
			$fileName = time().'.jpg';       
			$source = file_get_contents($content);
			file_put_contents('../addons/cy163_customerservice/'.$fileName,$source);   
			//$josnimg = array('media' => '@../addons/cy163_customerservice/'.$fileName);
			$imgurl = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=image";
			$imgres = $this->curl_post2($imgurl,'../addons/cy163_customerservice/'.$fileName);	
      	    unlink('../addons/cy163_customerservice/'.$fileName);			
			/*$path = ROOT_PATH.'messi.txt';
			$myfile = fopen($path, "w") or die("Unable to open file!");
			fwrite($myfile, $imgres);
			fclose($myfile);*/
			$data = array(
				"touser"=>$touser,
				"msgtype"=>"image",
				"image"=>array("media_id"=>$imgres['media_id'])
			);
		}
		
		if($msgtype == "link"){
			$data = array(
				"touser"=>$touser,
				"msgtype"=>"link",
				"link"=>array(
					"title"=>$content['title'],
					"description"=>$content['description'],
					"url"=>$content['url'],
					"thumb_url"=>$content['thumb_url']
				)
			);
		}
		
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
		$json = json_encode($data,JSON_UNESCAPED_UNICODE);
		$kefutokehures = $this->curl_post($url,$json);
		$kefutokehures = json_decode($kefutokehures,true);
		return $kefutokehures;
	}
	
	public function addxcxfanskefu($postArr){
		global $_GPC, $_W;
		$sessionform = $postArr['SessionFrom'];
		$sessionformarr = explode("-",$sessionform);
		$jhtext = $sessionformarr[0];
		$fansnickname = $sessionformarr[1];
		$fansavatar = $sessionformarr[2];
		$xcx = pdo_fetch("SELECT * FROM ".tablename(BEST_XCX)." WHERE uniacid = {$_W['uniacid']} AND gh_id = '{$postArr['ToUserName']}'");
		
		$allqzvals = pdo_fetchcolumn("SELECT SUM(gzhqzval) FROM ".tablename(BEST_XCXCSERVICE)." WHERE weid = {$_W['uniacid']} AND xcxid = {$xcx['id']}");
		$allqznum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_XCXCSERVICE)." WHERE weid = {$_W['uniacid']} AND xcxid = {$xcx['id']}");
		$pjval = intval($allqzvals/$allqznum);
		
		$nowhour = intval(date("H",TIMESTAMP));
		$nowhouradd = $nowhour+1;
		$condition = "weid = {$_W['uniacid']} AND xcxid = {$xcx['id']} AND gzhqzval <= {$pjval}";
		$condition .= " AND (
			(lingjie = 0 AND endhour >= {$nowhouradd} AND starthour <= {$nowhour}) OR 
			(lingjie = 1 AND 
				(starthour < {$nowhouradd} OR endhour > {$nowhour})
			)
		)";
		$zhouji = date("w");
		if($zhouji == "1" ){
			$condition .= " AND ((day1 = 1 AND isxingqi = 1) OR isxingqi = 0)";
		}
		if($zhouji == "2" ){
			$condition .= " AND ((day2 = 1 AND isxingqi = 1) OR isxingqi = 0)";
		}
		if($zhouji == "3" ){
			$condition .= " AND ((day3 = 1 AND isxingqi = 1) OR isxingqi = 0)";
		}
		if($zhouji == "4" ){
			$condition .= " AND ((day4 = 1 AND isxingqi = 1) OR isxingqi = 0)";
		}
		if($zhouji == "5" ){
			$condition .= " AND ((day5 = 1 AND isxingqi = 1) OR isxingqi = 0)";
		}
		if($zhouji == "6" ){
			$condition .= " AND ((day6 = 1 AND isxingqi = 1) OR isxingqi = 0)";
		}
		if($zhouji == "0" ){
			$condition .= " AND ((day7 = 1 AND isxingqi = 1) OR isxingqi = 0)";
		}
		$cservice = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCSERVICE)." WHERE ".$condition." AND jhtext = '{$jhtext}' ORDER BY gzhqzval ASC LIMIT 1");
		if(empty($cservice)){
			$cservice = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCSERVICE)." WHERE ".$condition." AND jhtext = '' ORDER BY gzhqzval ASC LIMIT 1");
		}
		$cservice = $cservice[0];
		
		if(!empty($cservice)){
			//更新权重值
			$dataqz['gzhqzval'] = $cservice['gzhqzval'] + 1;
			pdo_update(BEST_XCXCSERVICE,$dataqz,array('id'=>$cservice['id']));

			$kefuopenid = $cservice['content'];
			$gh_id = $postArr['ToUserName'];
			$fansopenid = $postArr['FromUserName'];
			$has = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$kefuopenid}' AND fansopenid = '{$fansopenid}' AND gh_id = '{$gh_id}'");
			if(empty($has) && !empty($cservice)){
				$data['weid'] = $_W['uniacid'];
				$data['fansopenid'] = $fansopenid;
				$data['fansnickname'] = $fansnickname;
				$data['fansavatar'] = $fansavatar;
				$data['kefuopenid'] = $kefuopenid;
				$data['kefuavatar'] = tomedia($cservice['thumb']);
				$data['kefunickname'] = $cservice['name'];
				$data['msgtype'] = $postArr['Event'];
				$data['gh_id'] = $gh_id;
				$data['createtime'] = $postArr['CreateTime'];
				$data['sessionfrom'] = $postArr['SessionFrom'];
				pdo_insert(BEST_XCXFANSKEFU,$data);
			}
			if(!empty($has) && !empty($fansnickname) && !empty($fansavatar) && ($fansnickname != $has['fansnickname'] || $fansavatar != $has['fansavatar'])){
				$dataup['fansnickname'] = $fansnickname;
				$dataup['fansavatar'] = $fansavatar;
				pdo_update(BEST_XCXFANSKEFU,$dataup,array('id'=>$has['id']));
			}
			
			$fanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_XCXFANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$kefuopenid}' AND fansopenid = '{$fansopenid}' AND gh_id = '{$gh_id}'");
			
			pdo_update(BEST_XCXFANSKEFU,array('nowkefu'=>0),array('fansopenid'=>$fansopenid));
			pdo_update(BEST_XCXFANSKEFU,array('nowkefu'=>1),array('id'=>$fanskefu['id']));
			if($cservice['autoreply'] != "" && $fanskefu['lastcon'] != ""){
				$this->addxcxchat2($fanskefu['fansopenid'],$cservice['autoreply'],"text",$fanskefu['gh_id']);
			}
		}
	}
	
	/* 调用微信api，获取access_token，有效期7200s -xzz0704 */
    public function get_xcx_accessToken($appid,$appsecret){
        /* 在有效期，直接返回access_token */
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
		$result = $this->curl_get_https($url);
		$res = json_decode($result,true);   //json字符串转数组
		if($res){
			return $res['access_token'];
		}else{
			return 'error';
		}
    }
	
	public function curl_get_https($url){
		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
		$tmpInfo = curl_exec($curl);     //返回api的json对象
		//关闭URL请求
		curl_close($curl);
		return $tmpInfo;    //返回json对象
	}
	
	public function curl_post($url,$data=array()){		
		//发送curl，返回arr。
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, 'Content-Type:text/html;charset=utf-8'); 
        if(!empty($data)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,20);//超时时间
        $rs=curl_exec($ch);
        if(curl_errno($ch)){//出错则显示错误信息
           $s = "{\"success\": false,\"msg\":\"".curl_error($ch)."\" }";
           return $s;
        }else{
            curl_close($ch);
			return $rs;
        }
    }
	
	public function curl_post2($url ='' , $path = '' ){
        $curl = curl_init();
        if (class_exists('CURLFile')){
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            $data = array('media' => new CURLFile($path));
        }else{
            curl_setopt($curl,CURLOPT_SAFE_UPLOAD,false);
            $data = array('media'=>'@'.$path);
        }
 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1 );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
        $result = curl_exec($curl);
        $res=json_decode($result,true);
        return $res;
    }

	
	public function checkmain($url){
		global $_GPC, $_W;
		$con = $this->get_url_content(BEST_DOMAIN);
		$con = json_decode($con,true);
		if(in_array($url,$con)){
			$cando = 1;
		}else{
			$cando = 0;
		}
		if($_W["account"]["type_name"] != "公众号"){
			$cando = 0;
		}
		return $cando;
	} 
	
	public function get_url_content($url){
	  if(function_exists("curl_init")){
		$ch = curl_init();
		$timeout = 30;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
	  }else{
		$is_auf=ini_get('allow_url_fopen')?true:false;
		if($is_auf){
		  $file_contents = file_get_contents($url);
		}
	  }
	  return $file_contents;
	}
	
	//随机字符串
	public function randCharNumber($length){
		$str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$tmp="";
		for($i=0;$i<$length;$i++){
			$tmp.=$str[mt_rand(0,61)];
		}  
		return $tmp;
	} 
	
	public function doWebYouhua() {
		global $_GPC, $_W;
		$days = intval($_GPC['days']);
		$days2 = intval($_GPC['days2']);
		$count = $count2 = 0;
		if($days > 0){
			$endtime = TIMESTAMP-$days*24*3600;
			$count = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time <= {$endtime}");
			pdo_query("DELETE FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time <= {$endtime}");
		}
		if($days2 > 0){
			$endtime2 = TIMESTAMP-$days2*24*3600;
			$count2 = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND time <= {$endtime2}");
			pdo_query("DELETE FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND time <= {$endtime2}");
		}
		$resArr['msg'] = "共删除".$count."条客服功能记录，".$count2."条群聊功能记录。";
		echo json_encode($resArr);
		exit;
	}
	
	public function doWebTongbu() {
		global $_GPC, $_W;
		/*$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND fkid = 0 AND type in (1,4,5)");
		foreach($chatlist as $k=>$v){
			$fansopenid = $v['openid'];
			$kefuopenid = $v['toopenid'];
			$hasfanskefu = pdo_fetch("SELECT id FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$fansopenid}' AND kefuopenid = '{$kefuopenid}'");
			if(empty($hasfanskefu)){
				$datafanskefu['weid'] = $_W['uniacid'];
				$datafanskefu['fansopenid'] = $fansopenid;
				$datafanskefu['kefuopenid'] = $kefuopenid;
				$datafanskefu['fansavatar'] = $v['avatar'];
				$datafanskefu['fansnickname'] = $v['nickname'];
				$cservice = pdo_fetch("SELECT name,thumb FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$kefuopenid}' AND ctype = 1");
				if(!empty($cservice)){
					$datafanskefu['kefuavatar'] = tomedia($cservice['thumb']);
					$datafanskefu['kefunickname'] = $cservice['name'];
					pdo_insert(BEST_FANSKEFU,$datafanskefu);
					$fkid = pdo_insertid();
					$dataup['fkid'] = $fkid;
					pdo_update(BEST_CHAT,$dataup,array('openid'=>$fansopenid,'weid'=>$_W['uniacid'],'toopenid'=>$kefuopenid));
					pdo_update(BEST_CHAT,$dataup,array('openid'=>$kefuopenid,'weid'=>$_W['uniacid'],'toopenid'=>$fansopenid));
				}
			}else{
				$dataup['fkid'] = $hasfanskefu['id'];
				pdo_update(BEST_CHAT,$dataup,array('openid'=>$fansopenid,'weid'=>$_W['uniacid'],'toopenid'=>$kefuopenid));
				pdo_update(BEST_CHAT,$dataup,array('openid'=>$kefuopenid,'weid'=>$_W['uniacid'],'toopenid'=>$fansopenid));
			}
		}*/
		
		//更新最后条内容和最后时间，粉丝头像昵称
		$fklast = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansnickname = '匿名用户' OR fansnickname = ''");
		foreach($fklast as $k=>$v){
			$oplen = strlen($v['fansopenid']);
			if($oplen == 28){
				$account_api = WeAccount::create();
				$info = $account_api->fansQueryInfo($v['fansopenid']);
				if($info['subscribe'] == 1){
					$datalast['fansavatar'] = $info['headimgurl'];
					$datalast['fansnickname'] = $info['nickname'];
					//更新客服粉丝对应表
					pdo_update(BEST_FANSKEFU,$datalast,array('id'=>$v['id'],'weid'=>$_W['uniacid']));
				}
			}
		}	
		message('同步客服数据成功！', "", 'success');
	}
	
	public function doWebTongbugroup() {
		global $_GPC, $_W;
		$groupmemberlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']}");
		foreach($groupmemberlist as $kk=>$vv){
			$account_api = WeAccount::create();
			$info = $account_api->fansQueryInfo($vv['openid']);
			if($info['subscribe'] == 1){
				$dgroup['avatar'] = $info['headimgurl'];
				$dgroup['nickname'] = $info['nickname'];
				//更新客服粉丝对应表
				pdo_update(BEST_GROUPMEMBER,$dgroup,array('id'=>$vv['id']));
			}
		}
		message('同步群聊数据成功！', "", 'success');
	}
	
	public function doWebChatlist() {
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
			if(!empty($kefuopenid)){				
				$allfkid = pdo_fetchall("SELECT fkid FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time > {$starttime} AND time < {$endtime} AND toopenid = '{$kefuopenid}'");
				$fkidarr[] = 0;
				foreach($allfkid as $k=>$v){
					$fkidarr[] = $v['fkid'];
				}
				pdo_query("UPDATE ".tablename(BEST_FANSKEFU)." set guanlinum = 0 WHERE id in (".implode(",",$fkidarr).")");
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND kefuopenid = '{$kefuopenid}' AND lasttime > 0");
				$allpage = ceil($total/10)+1;
				$page = intval($_GPC["page"]);
				$pindex = max(1, $page);
				$psize = 10;
				$fanslist = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND kefuopenid = '{$kefuopenid}' AND lasttime > 0 ORDER BY lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
				foreach($fanslist as $k=>$v){
					$fanslist[$k]['chat'] = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$v['id']} AND time > {$starttime} AND time < {$endtime} ORDER BY time DESC");
				}
				$pager = pagination($total, $pindex, $psize);
				if ($_GPC['export'] == 'export') {	
					$fanslistdaochu = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND id in (".implode(",",$fkidarr).") AND kefuopenid = '{$kefuopenid}' AND lasttime > 0 ORDER BY lasttime DESC");
					foreach($fanslistdaochu as $k=>$v){
						$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$v['id']} AND time > {$starttime} AND time < {$endtime} ORDER BY time DESC");
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
			}
			
			include $this->template('web/chatlist');
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$kefuopenid = trim($_GPC['kefuopenid']);
			if (empty($id)) {
				message('抱歉，参数传入错误！', $this->createWebUrl('chatlist', array('op' => 'display')), 'error');
			}
			pdo_query("DELETE FROM ".tablename(BEST_CHAT)." WHERE fkid = {$id}");
			pdo_query("DELETE FROM ".tablename(BEST_FANSKEFU)." WHERE id = {$id}");
			message('删除聊天记录成功！', $this->createWebUrl('chatlist', array('kefuopenid'=>$kefuopenid)), 'success');
		}elseif ($operation == 'deletedu') {
			$id = intval($_GPC['id']);
			$chat = pdo_fetch("SELECT id FROM ".tablename(BEST_CHAT)." WHERE id = {$id}");
			if (empty($chat)) {
				$resarr['error'] = 1;
				$resarr['msg'] = '不存在该聊天记录！';
				echo json_encode($resarr);
				exit();
			}
			pdo_delete(BEST_CHAT,array('id'=>$id));
			$resarr['error'] = 0;
			$resarr['msg'] = '删除成功！';
			echo json_encode($resarr);
			exit();
		}
	}
	
	public function exportexcel($data=array(),$title=array(),$header,$footer,$filename='report'){
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel");  
		header("Content-Disposition:attachment;filename=".$filename.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$header = iconv("UTF-8", "GB2312",$header);
		echo $header;
		if (!empty($title)){
			foreach ($title as $k => $v) {
				$title[$k]=iconv("UTF-8", "GB2312",$v);
			}
			$title= implode("\t", $title);
			echo "$title\r\n";
		}
		if (!empty($data)){
			foreach($data as $key=>$val){
				foreach ($val as $ck => $cv) {
					$data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
				}
				$data[$key]=implode("\t", $data[$key]);
				
			}
			echo implode("\n",$data);
		}
		echo "\r\n";
		$footer = iconv("UTF-8", "GB2312",$footer);
		echo $footer;
	}
	
	public function doWebCservice() {
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
			foreach($cservicelist as $k=>$v){
				if($v['ctype'] == 1){
					$cservicelist[$k]['serviceurl'] = $_W['siteroot'].'app/'.str_replace('./','',$this->createMobileUrl('chat',array('toopenid'=>$v['content'])));
					$cservicelist[$k]['qrcode'] = "http://qr.liantu.com/api.php?text=".urlencode($cservicelist[$k]['serviceurl']);
				}
				if($v['ctype'] == 2){
					$cservicelist[$k]['serviceurl'] = "http://wpa.qq.com/msgrd?v=3&uin=".$v['content'];
				}
				if($v['ctype'] == 3 || $v['ctype'] == 4){
					$cservicelist[$k]['serviceurl'] = "tel:".$v['content'];
				}
			}
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
				if (empty($_GPC['ctype'])) {
					message('抱歉，请选择客服类型！');
				}
				if (empty($id) && empty($_GPC['content'])) {
					message('抱歉，请输入客服内容！');
				}
				if (empty($_GPC['thumb'])) {
					message('抱歉，请上传客服头像！');
				}
				$ctype = intval($_GPC['ctype']);
				if($ctype == 1){
					$starthour = intval($_GPC['starthour']);
					$endhour = intval($_GPC['endhour']);
					$autoreply = trim($_GPC['autoreply']);
				}else{
					$starthour = 0;
					$endhour = 0;
					$autoreply = '';
				}
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
					'displayorder' => intval($_GPC['displayorder']),
					'fansauto'=>trim($_GPC['fansauto']),
					'kefuauto'=>trim($_GPC['kefuauto']),
					'isautosub'=>intval($_GPC['isautosub']),
					'ishow' => intval($_GPC['ishow']),
					'notonline'=>trim($_GPC['notonline']),
					'lingjie' => intval($_GPC['lingjie']),
					'groupid'=>0,
					'isgly' => intval($_GPC['isgly']),
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
					if($ctype != 1){
						$data['content'] = trim($_GPC['content']);
					}
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
				pdo_delete(BEST_CHAT, array('openid' => $cservice['content']));
				pdo_delete(BEST_CHAT, array('toopenid' => $cservice['content']));
				pdo_delete(BEST_FANSKEFU, array('kefuopenid' => $cservice['content']));
				pdo_delete(BEST_BIAOQIAN, array('kefuopenid' => $cservice['content']));
			}
			pdo_delete(BEST_CSERVICE, array('id' => $id));
			message('删除客服信息成功！', $this->createWebUrl('cservice', array('op' => 'display')), 'success');
		}
	}
	
	public function doWebGroup() {
		include_once ROOT_PATH.'inc/web/group.php';
	}

	public function doWebCservicegroup() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update(BEST_CSERVICEGROUP, array('displayorder' => $displayorder), array('id' => $id));
				}
				message('客服组排序更新成功！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'success');
			}
			$cservicegrouplist = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = {$_W['uniacid']} ORDER BY displayorder ASC");
			foreach($cservicegrouplist as $k=>$v){
				$cservicegrouplist[$k]['servicegroupurl'] = $_W['siteroot'].'app/'.str_replace('./','',$this->createMobileUrl('groupchat',array('id'=>$v['id'])));
			}
			include $this->template('web/cservicegroup');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			$othergroup = pdo_fetchall("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE weid = {$_W['uniacid']} AND id != {$id}");
			if (!empty($id)) {
				$cservicegroup = pdo_fetch("SELECT * FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE id = :id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['name'])) {
					message('抱歉，请输入客服组名称！');
				}	
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => trim($_GPC['name']),
					'typename' => trim($_GPC['typename']),
					'thumb' => $_GPC['thumb'],
					'displayorder' => intval($_GPC['displayorder']),
					'ishow' => intval($_GPC['ishow']),
					'sanbs'=>trim($_GPC['sanbs']),
					'sanremark'=>trim($_GPC['sanremark']),
					'bsid' => intval($_GPC['bsid']),
					'fid' => intval($_GPC['fid']),
				);
				if (!empty($id)) {
					pdo_update(BEST_CSERVICEGROUP, $data, array('id' => $id, 'weid' => $_W['uniacid']));
				} else {
					pdo_insert(BEST_CSERVICEGROUP, $data);
				}
				message('操作成功！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'success');
			}
			include $this->template('web/cservicegroup');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$cservicegroup = pdo_fetch("SELECT id FROM " . tablename(BEST_CSERVICEGROUP) . " WHERE id = {$id}");
			if (empty($cservicegroup)) {
				message('抱歉，该客服组不存在或是已经被删除！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'error');
			}

			pdo_delete(BEST_CSERVICEGROUP, array('id' => $id));
			message('删除客服组成功！', $this->createWebUrl('cservicegroup', array('op' => 'display')), 'success');
		}
	}
	
		
	public function doMobileWtest() {
		include $this->template('wtest');
	}
	
	public function doMobileKefucenter() {
		include_once ROOT_PATH.'inc/mobile/kefucenter.php';
	}
	
	public function doMobileSearchkefus() {
		include_once ROOT_PATH.'inc/mobile/searchkefus.php';
	}

	
	public function doMobileChosekefu() {
		global $_W, $_GPC;
		$nowtime = TIMESTAMP;
		$advlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_ADV) . " WHERE weid = '{$_W['uniacid']}' AND (endtime > {$nowtime} OR endtime = 0) AND isdadi = 0 ORDER BY displayorder ASC");
		if(empty($advlist)){
			$advlist = pdo_fetchall("SELECT * FROM " . tablename(BEST_ADV) . " WHERE weid = '{$_W['uniacid']}' AND isdadi = 1 ORDER BY displayorder ASC");
		}
		$this->module['config']['shareurl'] = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl('chosekefu'));
		if($this->module['config']['suiji'] == 1){
			$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND (ishow = 0 OR (isrealzx = 1 AND ishow = 2 AND iszx = 1)) AND groupid = 0 ORDER BY rand()");
		}else{
			$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND (ishow = 0 OR (isrealzx = 1 AND ishow = 2 AND iszx = 1)) AND groupid = 0 ORDER BY displayorder ASC");
		}
		foreach($cservicelist as $k=>$v){
			$kefuandgroup = pdo_fetch("SELECT id FROM ".tablename(BEST_KEFUANDGROUP)." WHERE kefuid = {$v['id']}");
			if(!empty($kefuandgroup)){
				unset($cservicelist[$k]);
			}
		}
		$cservicegrouplist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICEGROUP)." WHERE weid = {$_W['uniacid']} AND ishow = 1 AND fid = 0 ORDER BY displayorder ASC");
		$iscservice = pdo_fetch("SELECT id FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");
		if(!empty($iscservice)){
			$notread = pdo_fetchcolumn("SELECT SUM(a.notread) FROM ".tablename(BEST_FANSKEFU)." as a,".tablename(BEST_CHAT)." as b WHERE a.weid = {$_W['uniacid']} AND a.kefuopenid = '{$_W['fans']['from_user']}' AND a.id = b.fkid AND b.kefudel = 0");
		}else{
			$notread = pdo_fetchcolumn("SELECT SUM(a.kefunotread) FROM ".tablename(BEST_FANSKEFU)." as a,".tablename(BEST_CHAT)." as b WHERE a.weid = {$_W['uniacid']} AND a.fansopenid = '{$_W['fans']['from_user']}' AND a.id = b.fkid AND b.fansdel = 0");
		}
		if($this->module['config']['chosekefutem'] == 0){
			include $this->template('chosekefu');
		}
		if($this->module['config']['chosekefutem'] == 1 || $this->module['config']['chosekefutem'] == 2){
			include $this->template('chosekefu2');
		}
	}
	
	public function doMobileDisanfang() {
		include_once ROOT_PATH.'inc/mobile/disanfang.php';
	}
	
	public function doMobileSanchat() {
		include_once ROOT_PATH.'inc/mobile/sanchat.php';
	}
	
	public function doMobileGroupchat() {
		global $_W, $_GPC;
		$openid = $_W['fans']['from_user'];
		$id = intval($_GPC['id']);
		$this->module['config']['shareurl'] = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl('groupchat',array('id'=>$id)));
		
		$qudao = trim($_GPC['qudao']);
		if($qudao == 'renren'){
			$goodsid = intval($_GPC['goodsid']);
			if($goodsid <= 0){
				$sanreferer = $_SERVER['HTTP_REFERER'];
				$arr = parse_url($sanreferer);
				$arr = explode('&',$arr['query']);
				$index = count($arr)-1;
				$strgg = str_replace("id=","",$arr[$index]);
				$goodsid = intval($strgg);	
			}
			if(pdo_tableexists('ewei_shop_goods')){
				$goodsres = pdo_fetch("SELECT shopid,merchid FROM ".tablename('ewei_shop_goods')." WHERE id = {$goodsid} AND uniacid = {$_W['uniacid']}");
				if($goodsres['merchid'] > 0){
					$cservicegroup = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICEGROUP)." WHERE sanbs = '{$qudao}' AND weid = {$_W['uniacid']} AND bsid = {$goodsres['merchid']}");
				}else{
					$cservicegroup = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICEGROUP)." WHERE sanbs = '{$qudao}' AND weid = {$_W['uniacid']} AND bsid = 0");
				}
			}
		}else{
			$cservicegroup = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICEGROUP)." WHERE weid = {$_W['uniacid']} AND id = {$id}");
		}
		
		
		$kefuandgroup = pdo_fetchall("SELECT kefuid FROM ".tablename(BEST_KEFUANDGROUP)." WHERE weid = {$_W['uniacid']} AND groupid = {$cservicegroup['id']}");
		$kefuids = array(0);
		foreach($kefuandgroup as $k=>$v){
			$kefuids[] = $v['kefuid'];
		}
		if($this->module['config']['suiji'] == 1){
			$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND (ishow = 0 OR (isrealzx = 1 AND ishow = 2 AND iszx = 1)) AND id in ( " . implode(',', $kefuids) . ") ORDER BY rand()");
		}else{
			$cservicelist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND (ishow = 0 OR (isrealzx = 1 AND ishow = 2 AND iszx = 1)) AND id in ( " . implode(',', $kefuids) . ") ORDER BY displayorder ASC");
		}
		$cservicegrouplist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICEGROUP)." WHERE weid = {$_W['uniacid']} AND ishow = 1 AND fid = {$cservicegroup['id']} ORDER BY displayorder ASC");
		
		
		$iscservice = pdo_fetch("SELECT id FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_W['fans']['from_user']}'");		
		if(!empty($iscservice)){
			$notread = pdo_fetchcolumn("SELECT SUM(a.notread) FROM ".tablename(BEST_FANSKEFU)." as a,".tablename(BEST_CHAT)." as b WHERE a.weid = {$_W['uniacid']} AND a.kefuopenid = '{$_W['fans']['from_user']}' AND a.id = b.fkid AND b.kefudel = 0");
		}else{
			$notread = pdo_fetchcolumn("SELECT SUM(a.kefunotread) FROM ".tablename(BEST_FANSKEFU)." as a,".tablename(BEST_CHAT)." as b WHERE a.weid = {$_W['uniacid']} AND a.fansopenid = '{$_W['fans']['from_user']}' AND a.id = b.fkid AND b.fansdel = 0");
		}
		if($this->module['config']['chosekefutem'] == 0){
			include $this->template('groupchat');
		}
		if($this->module['config']['chosekefutem'] == 1 || $this->module['config']['chosekefutem'] == 2){
			include $this->template('groupchat2');
		}
	}
	
	public function doMobileGetchatbigimg() {
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$con = trim($_GPC['con']);
		$imglist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$fkid} AND weid = {$_W['uniacid']} AND (type = 3 OR type = 4) ORDER BY time ASC");
		$oneimg = pdo_fetch("SELECT id FROM ".tablename(BEST_CHAT)." WHERE fkid = {$fkid} AND content = '{$con}' AND weid = {$_W['uniacid']} AND (type = 3 OR type = 4)");
		$myid = $oneimg['id'];
		if(!empty($imglist)){
			$imglistval = '';
			$nowindex = 0;
			foreach($imglist as $k=>$v){
				if($v['id'] == $myid){
					$nowindex = $k;
				}
				$imglistval .= $v['content'].',';
			}
			$imglistval = substr($imglistval,0,-1);
			$resArr['error'] = 0;
			$resArr['message'] = $imglistval;
			$resArr['index'] = $nowindex;
			echo json_encode($resArr);
			exit;
		}else{
			$resArr['error'] = 1;
			$resArr['message'] = "";
			echo json_encode($resArr);
			exit;
		}
	}
	
	public function doMobileGetchatbigimgxcx() {
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$con = trim($_GPC['con']);
		$imglist = pdo_fetchall("SELECT * FROM ".tablename(BEST_XCXCHAT)." WHERE fkid = {$fkid} AND weid = {$_W['uniacid']} AND msgtype = 'image' ORDER BY time ASC");
		$oneimg = pdo_fetch("SELECT id FROM ".tablename(BEST_XCXCHAT)." WHERE fkid = {$fkid} AND content = '{$con}' AND weid = {$_W['uniacid']} AND msgtype = 'image'");
		$myid = $oneimg['id'];
		if(!empty($imglist)){
			$imglistval = '';
			$nowindex = 0;
			foreach($imglist as $k=>$v){
				if($v['id'] == $myid){
					$nowindex = $k;
				}
				$imglistval .= $v['content'].',';
			}
			$imglistval = substr($imglistval,0,-1);
			$resArr['error'] = 0;
			$resArr['message'] = $imglistval;
			$resArr['index'] = $nowindex;
			echo json_encode($resArr);
			exit;
		}else{
			$resArr['error'] = 1;
			$resArr['message'] = "";
			echo json_encode($resArr);
			exit;
		}
	}
	
	public function doMobileGetgroupchatbigimg() {
		global $_W,$_GPC;
		$groupid = intval($_GPC['groupid']);
		$myin = pdo_fetch("SELECT intime FROM ".tablename(BEST_GROUPMEMBER)." WHERE groupid = {$groupid} AND openid = '{$_W['fans']['from_user']}'");		
		$imglist = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPCONTENT)." WHERE weid = {$_W['uniacid']} AND groupid = {$groupid} AND type = 3 AND time >= {$myin['intime']} ORDER BY time ASC");
		$con = trim($_GPC['con']);
		$oneimg = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPCONTENT)." WHERE groupid = {$groupid} AND content = '{$con}' AND weid = {$_W['uniacid']} AND type = 3");
		$myid = $oneimg['id'];
		if(!empty($imglist)){
			$imglistval = '';
			$nowindex = 0;
			foreach($imglist as $k=>$v){
				if($v['id'] == $myid){
					$nowindex = $k;
				}
				$imglistval .= $v['content'].',';
			}
			$imglistval = substr($imglistval,0,-1);
			$resArr['error'] = 0;
			$resArr['message'] = $imglistval;
			$resArr['index'] = $nowindex;
			echo json_encode($resArr);
			exit;
		}else{
			$resArr['error'] = 1;
			$resArr['message'] = "";
			echo json_encode($resArr);
			exit;
		}
	}

	
	public function doMobileGetsanchatbigimg() {
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$fkid2 = intval($_GPC['fkid2']);
		$myid = intval($_GPC['myid']);			
		$imglist = pdo_fetchall("SELECT * FROM ".tablename(BEST_SANCHAT)." WHERE (sanfkid = {$fkid} OR sanfkid = {$fkid2}) AND weid = {$_W['uniacid']} AND type = 2 ORDER BY time ASC");
		if(!empty($imglist)){
			$imglistval = '';
			$nowindex = 0;
			foreach($imglist as $k=>$v){
				if($v['id'] == $myid){
					$nowindex = $k;
				}
				$imglistval .= $v['content'].',';
			}
			$imglistval = substr($imglistval,0,-1);
			$resArr['error'] = 0;
			$resArr['message'] = $imglistval;
			$resArr['index'] = $nowindex;
			echo json_encode($resArr);
			exit;
		}else{
			$resArr['error'] = 1;
			$resArr['message'] = "";
			echo json_encode($resArr);
			exit;
		}
	}
	
	public function doMobileWenzhangdetail(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$wenzhang = pdo_fetch("SELECT * FROM ".tablename(BEST_WENZHANG)." WHERE weid = {$_W['uniacid']} AND id = {$id}");
		if(empty($wenzhang)){
			$message = '不存在该篇文章！';
			include $this->template('error');
			exit;
		}
		include $this->template("wenzhangdetail");
	}

	
	//聊天
	public function doMobileChat(){
		global $_W,$_GPC;
		if(empty($_W['fans']['from_user'])){
			//message('请在微信浏览器中打开！','','error');
			$ssopenid = trim($_GPC['ssopenid']);
			if($ssopenid != ""){
				$openid = $ssopenid;
			}else{
				$openid = $_W['clientip'];
			}
		}else{
			$openid = $_W['fans']['from_user'];
		}
		$toopenid = trim($_GPC['toopenid']);
		$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$toopenid}'");
		if(empty($cservice)){
			$message = '获取客服信息失败！';
			include $this->template('error');
			exit;
		}
		if($this->module['config']['bdmodel'] == 1){
			$hasbd = pdo_fetch("SELECT kefuopenid FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$openid}' AND bdopenid != ''");
			if(!empty($hasbd)){
				if($hasbd['kefuopenid'] != $toopenid && $cservice['bdchat'] == 0){
					$cservicebd = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$hasbd['kefuopenid']}'");
					if(empty($cservicebd)){
						$message = '获取客服信息失败！';
						include $this->template('error');
						exit;
					}
					include $this->template('zhuanshu');
					exit;
				}
			}
		}
		$ishei = pdo_fetch("SELECT id FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND ishei = 1");
		if(!empty($ishei)){
			$message = '您暂时不能咨询！';
			include $this->template('error');
			exit;
		}
		
		if($openid == $toopenid){
			$message = '不能和自己聊天！';
			include $this->template('error');
			exit;
		}
			
		$cjwtlist = pdo_fetchall("SELECT a.* FROM ".tablename(BEST_WENZHANG)." as a,".tablename(BEST_KEFUANDCJWT)." as b WHERE a.weid = {$_W['uniacid']} AND b.kefuid = {$cservice['id']} AND a.id = b.wtid ORDER BY a.paixu DESC");
		$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND kefuopenid = '{$toopenid}'");
		if(empty($hasfanskefu)){
			$datafanskefu['weid'] = $_W['uniacid'];
			$datafanskefu['fansopenid'] = $openid;
			$datafanskefu['kefuopenid'] = $cservice['content'];
			if(empty($openid)){
				$datafanskefu['fansavatar'] = tomedia($this->module['config']['defaultavatar']);
				$datafanskefu['fansnickname'] = '游客';
			}else{
				$account_api = WeAccount::create();
				$info = $account_api->fansQueryInfo($openid);
				if($info['subscribe'] == 1){
					$datafanskefu['fansavatar'] = $info['headimgurl'];
					$datafanskefu['fansnickname'] = str_replace('\'', '\'\'',$info['nickname']);
				}else{
					$datafanskefu['fansavatar'] = tomedia($this->module['config']['defaultavatar']);
					$datafanskefu['fansnickname'] = '匿名用户';
				}		
			}
			$datafanskefu['kefuavatar'] = tomedia($cservice['thumb']);
			$datafanskefu['kefunickname'] = $cservice['name'];
			pdo_insert(BEST_FANSKEFU,$datafanskefu);
			$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND kefuopenid = '{$toopenid}'");
		}
		
		if($this->module['config']['bdmodel'] == 1 && $cservice['beibang'] == 1){
			$hasbd = pdo_fetch("SELECT kefuopenid FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$openid}' AND bdopenid != ''");
			if(empty($hasbd)){
				$databd['bdopenid'] = $cservice['content'];
				pdo_update(BEST_FANSKEFU,$databd,array('id'=>$hasfanskefu['id']));
			}
		}

		if($cservice['autoreply']){			
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$cservice['autoreply'],$array2);  
			if(!empty($array2[0])){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$cservice['autoreply'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$cservice['autoreply']);
					}
				}
			}
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_CHAT)." WHERE fkid = {$hasfanskefu['id']} AND weid = {$_W['uniacid']} AND fansdel = 0");		
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$hasfanskefu['id']} AND weid = {$_W['uniacid']} AND fansdel = 0 ORDER BY time ASC LIMIT ".$nowjl.",".$psize);
		$timestamp = TIMESTAMP;
		$chatcontime = 0;
		foreach($chatcon as $k=>$v){
			if($v['openid'] != $openid){
				$chatcon[$k]['class'] = 'left';
				$chatcon[$k]['avatar'] = $hasfanskefu['kefuavatar'];
			}else{
				$chatcon[$k]['class'] = 'right';
				$chatcon[$k]['avatar'] = $hasfanskefu['fansavatar'];
			}
			
			
			if(($v['time'] - $chatcontime) > 7200){
				$chatcon[$k]['time'] = $v['time'];
			}else{
				$chatcon[$k]['time'] = '';
			}
			$chatcontime = $v['time'];
			//$chatcon[$k]['content'] = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $v['content']);
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $v['content']);
			$chatcon[$k]['content'] = $this->guolv($chatcon[$k]['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$chatcon[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
					}
				}
			}
			if($v['type'] == 5 || $v['type'] == 6){
				$donetime = $timestamp - $v['time'];
				if($donetime >= 24*3600*3){
					unset($chatcon[$k]);
				}
			}
		}
		$fansauto = empty($cservice['fansauto']) ? '' : explode("|",$cservice['fansauto']);
		$goodsid = intval($_GPC['goodsid']);
		$qudao = trim($_GPC['qudao']);
		if($goodsid <= 0){
			$sanreferer = $_SERVER['HTTP_REFERER'];
			if($qudao == 'renren'){
				$arr = parse_url($sanreferer);
				$arr = explode('&',$arr['query']);
				$index = count($arr)-1;
				$strgg = str_replace("id=","",$arr[$index]);
				$goodsid = intval($strgg);
			}
		}
		if($qudao == 'renren' && $goodsid != 0){
			if(pdo_tableexists('ewei_shop_goods')) {
				$goodsres = pdo_fetch("SELECT title,thumb,id,productprice,costprice,marketprice FROM ".tablename('ewei_shop_goods')." WHERE id = {$goodsid} AND uniacid = {$_W['uniacid']}");
				$goods['title'] = $goodsres['title'];
				$goods['thumb'] = tomedia($goodsres['thumb']);
				$goods['id'] = $goodsres['id'];
				$goods['price'] = $goodsres['marketprice'];
			}
		}
		$kefupingfen = pdo_fetch("SELECT * FROM ".tablename(BEST_PINGJIA)." WHERE weid = {$_W['uniacid']} AND fensiopenid = '{$openid}' AND kefuopenid = '{$toopenid}'");
		pdo_update(BEST_FANSKEFU,array('kefunotread'=>0),array('id'=>$hasfanskefu['id']));
		include $this->template("newchat");
	}
	
	
	public function doMobileChatajax(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE id = {$fkid}");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_CHAT)." WHERE fkid = {$fkid} AND weid = {$_W['uniacid']} AND fansdel = 0");
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		
		if($total > $pindex*$psize){
			$tolimit = $psize;
		}else{
			$tolimit = $psize-($pindex*$psize-$total);
		}		
		$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$fkid} AND weid = {$_W['uniacid']} AND fansdel = 0 ORDER BY time ASC LIMIT ".$nowjl.",".$tolimit);
		$timestamp = TIMESTAMP;
		$chatcontime = 0;
		foreach($chatcon as $k=>$v){
			if(($v['time'] - $chatcontime) > 7200){
				$chatcon[$k]['time'] = $v['time'];
			}else{
				$chatcon[$k]['time'] = '';
			}
			$chatcontime = $v['time'];
			//$chatcon[$k]['content'] = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $v['content']);
			$chatcon[$k]['content'] = $this->guolv($v['content']);
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $chatcon[$k]['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$chatcon[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
					}
				}
			}
			if($v['type'] == 5 || $v['type'] == 6){
				$donetime = $timestamp - $v['time'];
				if($donetime >= 24*3600*3){
					unset($chatcon[$k]);
				}
			}
		}
		$html = '';
		foreach($chatcon as $k=>$v){
			$htmltime = !empty($v['time']) ? '<div class="time text-c">'.date('Y-m-d H:i:s',$v['time']).'</div>' : '';
			if($v['openid'] != $hasfanskefu['fansopenid']){
				$class = 'left';
				$avatar = $hasfanskefu['kefuavatar'];
			}else{
				$class = 'right';
				$avatar = $hasfanskefu['fansavatar'];
			}
			
			if($v['type'] == 3 || $v['type'] == 4){
				$chatconhtml = '<div class="concon"><img src="'.$v['content'].'" class="sssbbb" /></div>';
			}elseif($v['type'] == 5 || $v['type'] == 6){
				if($v['hasyuyindu'] == 0 && $hasfanskefu['kefuopenid'] == $v['toopenid']){
					$weidu = '<span class="weidu">未读</span>';
				}else{
					$weidu = '';
				}
				$chatconhtml = '<div class="concon playvoice flex" data-con="'.$chatcon[$k]['content'].'">
									<i class="a-icon iconfont">&#xe601;</i>
									<span class="miao">'.$v['yuyintime'].'\'\'</span>
									'.$weidu.'
									<div class="flex1"></div>
								</div>';
			}else{
				$chatconhtml = '<div class="concon">'.$v['content'].'</div>';
			}
			$html .= $htmltime.'<div class="'.$class.' flex">
									<img src="'.$avatar.'" class="avatar" />
									<div class="con flex flex1">
										<div class="triangle-'.$class.'"></div>
										'.$chatconhtml.'
										<div class="flex1"></div>
									</div>
								</div>';
		}
		echo $html;
		exit;
	}

	public function doMobileAddbiaoqian(){
		global $_W,$_GPC;
		$openid = $_W['fans']['from_user'];
		if(empty($openid)){
			$resArr['error'] = 1;
			$resArr['msg'] = '未获取到您的客服信息！';
			echo json_encode($resArr);
			exit;
		}
		$name = trim($_GPC['content']);
		if(empty($name)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请填写标签内容！';
			echo json_encode($resArr);
			exit;
		}
		$realname = trim($_GPC['realname']);
		$telphone = trim($_GPC['telphone']);
		$toopenid = trim($_GPC['toopenid']);
		$has = pdo_fetch("SELECT * FROM ".tablename(BEST_BIAOQIAN)." WHERE kefuopenid = '{$openid}' AND fensiopenid = '{$toopenid}' AND weid = {$_W['uniacid']}");
		if($has){
			pdo_update(BEST_BIAOQIAN,array('name'=>$name,'realname'=>$realname,'telphone'=>$telphone),array('kefuopenid'=>$openid,'fensiopenid'=>$toopenid,'weid'=>$_W['uniacid']));
		}else{
			$data['weid'] = $_W['uniacid'];
			$data['kefuopenid'] = $openid;
			$data['fensiopenid'] = $toopenid;
			$data['name'] = $name;
			$data['realname'] = $realname;
			$data['telphone'] = $telphone;
			pdo_insert(BEST_BIAOQIAN,$data);
		}
		$resArr['error'] = 0;
		$resArr['msg'] = '恭喜你添加用户标签成功！';
		echo json_encode($resArr);
		exit;
	}
	
	
	
	public function doMobileAddpingjia(){
		global $_W,$_GPC;
		$openid = $_W['fans']['from_user'];
		if(empty($openid)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请在微信端评价！';
			echo json_encode($resArr);
			exit;
		}
		$pingtype = intval($_GPC['pingtype']);
		if($pingtype <= 0){
			$resArr['error'] = 1;
			$resArr['msg'] = '请选择评价类型！';
			echo json_encode($resArr);
			exit;
		}
		$toopenid = trim($_GPC['toopenid']);
		$has = pdo_fetch("SELECT * FROM ".tablename(BEST_PINGJIA)." WHERE kefuopenid = '{$toopenid}' AND fensiopenid = '{$openid}' AND weid = {$_W['uniacid']}");
		if($has){
			$data['pingtype'] = $pingtype;
			$data['content'] = $_GPC['content'];
			$data['time'] = TIMESTAMP;
			pdo_update(BEST_PINGJIA,$data,array('kefuopenid'=>$toopenid,'fensiopenid'=>$openid,'weid'=>$_W['uniacid']));
		}else{
			$data['weid'] = $_W['uniacid'];
			$data['kefuopenid'] = $toopenid;
			$data['fensiopenid'] = $openid;
			$data['pingtype'] = $pingtype;
			$data['time'] = TIMESTAMP;
			$data['content'] = $_GPC['content'];
			pdo_insert(BEST_PINGJIA,$data);
		}
		$resArr['error'] = 0;
		$resArr['msg'] = '恭喜你评价成功！';
		echo json_encode($resArr);
		exit;
	}
	
	public function doMobileZhuanjie(){
		global $_W,$_GPC;
		$openid = $_W['fans']['from_user'];
		if(empty($openid)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请在微信浏览器中打开！';
			echo json_encode($resArr);
			exit;
		}
		$toopenid = trim($_GPC['toopenid']);
		if(empty($toopenid)){
			$resArr['error'] = 1;
			$resArr['msg'] = '获取用户数据失败！';
			echo json_encode($resArr);
			exit;
		}
		$content = trim($_GPC['content']);
		if(empty($content)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请选择要转接的客服！';
			echo json_encode($resArr);
			exit;
		}
		$tplcon = '您收到了一条客服转接请求！';
		
		$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' AND kefuopenid = '{$content}'");
		$zhuanjiekefu = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$content}'");
		if(empty($hasfanskefu)){
			$datafanskefu['weid'] = $_W['uniacid'];
			$datafanskefu['fansopenid'] = $toopenid;
			$datafanskefu['kefuopenid'] = $content;
			$account_api = WeAccount::create();
			$fansuser = $account_api->fansQueryInfo($toopenid);
			if(empty($fansuser)){
				$datafanskefu['fansavatar'] = tomedia($this->module['config']['defaultavatar']);
				$datafanskefu['fansnickname'] = '匿名用户';
			}else{
				$datafanskefu['fansavatar'] = empty($fansuser['headimgurl']) ? tomedia($this->module['config']['defaultavatar']) : $fansuser['headimgurl'];
				$datafanskefu['fansnickname'] = empty($fansuser['nickname']) ? '匿名用户' : $fansuser['nickname'];
			}
			$datafanskefu['kefuavatar'] = tomedia($zhuanjiekefu['thumb']);
			$datafanskefu['kefunickname'] = $zhuanjiekefu['name'];
			pdo_insert(BEST_FANSKEFU,$datafanskefu);
			$fkid = pdo_insertid();
		}else{
			$fkid = $hasfanskefu['id'];
		}
		$datachat['weid'] = $_W['uniacid'];
		$datachat['fkid'] = $fkid;
		$datachat['openid'] = $content;
		$datachat['toopenid'] = $toopenid;
		$datachat['content'] = '<span class="red">系统提醒：</span><span class="hui">已转接至'.$zhuanjiekefu['name'].'为您继续提供服务！</span>';
		$datachat['time'] = TIMESTAMPt;
		$datachat['nickname'] = $zhuanjiekefu['name'];
		$datachat['avatar'] = tomedia($zhuanjiekefu['thumb']);
		$datachat['type'] = 1;
		$datachat['time'] = TIMESTAMP;
		pdo_insert(BEST_CHAT,$datachat);
		if($this->module['config']['istplon'] == 1){
			if(!empty($this->module['config']['tpl_kefu'])){
				$or_paysuccess_redirect = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("servicechat",array('toopenid'=>$toopenid)));	
				$postdata = array(
					'first' => array(
						'value' => $tplcon,
						'color' => '#990000'
					),
					'keyword1' => array(
						'value' => $tplcon,
						'color' => '#ff510'
					),
					'keyword2' => array(
						'value' => "点击此消息查看",
						'color' => '#ff510'
					),
					'remark' => array(
						'value' => '转接时间：'.date("Y-m-d H:i:s",TIMESTAMP),
						'color' => '#ff510'
					),							
				);
				$account_api = WeAccount::create();
				$account_api->sendTplNotice($content,$this->module['config']['tpl_kefu'],$postdata,$or_paysuccess_redirect,'#980000');
			}else{
				$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("servicechat",array('toopenid'=>$toopenid)));
				$concon = $tplcon.'。';								
				$row = array();
				$row['title'] = urlencode('新消息提醒');
				$row['description'] = urlencode($concon);
				$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
				$row['url'] = $texturl;
				$news[] = $row;
				$send['touser'] = $content;
				$send['msgtype'] = 'news';
				$send['news']['articles'] = $news;
				$account_api = WeAccount::create();
				$account_api->sendCustomNotice($send);
			}
		}
        $resArr['error'] = 0;
		$resArr['toopenid'] = $content;
        $resArr['msg'] = '转接成功';
        echo json_encode($resArr);
        exit;
	}
	
	public function doMobileServicechat(){
		global $_W,$_GPC;
		include_once ROOT_PATH.'qqface.php';
		$openid = $_W['fans']['from_user'];
		if(empty($openid)){
			$ssopenid = $_GPC['ssopenid'];
			if($ssopenid != ""){
				$openid = $ssopenid;
			}else{
				$message = '请在微信浏览器中打开！';
				include $this->template('error');
				exit;
			}
		}
		$toopenid = trim($_GPC['toopenid']);
		$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$openid}'");
		if(empty($cservice)){
			$message = '你不是客服身份，请联系管理员查看具体信息！';
			include $this->template('error');
			exit;
		}
		$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE fansopenid = '{$toopenid}' AND kefuopenid = '{$openid}'");
		if(empty($hasfanskefu)){
			$datafanskefu['weid'] = $_W['uniacid'];
			$datafanskefu['fansopenid'] = $toopenid;
			$datafanskefu['kefuopenid'] = $openid;
			$datafanskefu['kefuavatar'] = tomedia($cservice['thumb']);
			$datafanskefu['kefunickname'] = $cservice['name'];
			$account_api = WeAccount::create();
			$fansinfos = $account_api->fansQueryInfo($toopenid);
			if(empty($fansinfos)){
				$datafanskefu['fansavatar'] = tomedia($this->module['config']['defaultavatar']);
				$datafanskefu['fansnickname'] = '匿名用户';
			}else{
				$datafanskefu['fansavatar'] = $fansinfos['headimgurl'];
				$datafanskefu['fansnickname'] = $fansinfos['nickname'];
			}
			pdo_insert(BEST_FANSKEFU,$datafanskefu);
			$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' AND kefuopenid = '{$openid}'");
		}
		
		//更新头像昵称
		if($hasfanskefu['fansnickname'] == '匿名用户' || $hasfanskefu['fansnickname'] == ''){
			$oplen = strlen($hasfanskefu['fansopenid']);
			if($oplen == 28){
				$account_api = WeAccount::create();
				$info = $account_api->fansQueryInfo($hasfanskefu['fansopenid']);
				if($info['subscribe'] == 1){
					$dataupna['fansavatar'] = $info['headimgurl'];
					$dataupna['fansnickname'] = $info['nickname'];
					//更新客服粉丝对应表
					pdo_update(BEST_FANSKEFU,$dataupna,array('id'=>$hasfanskefu['id'],'weid'=>$_W['uniacid']));
				}
				$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' AND kefuopenid = '{$openid}'");
			}
		}
		
		$othercservice = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content != '{$openid}' ORDER BY displayorder ASC");
		$biaoqian = pdo_fetch("SELECT * FROM ".tablename(BEST_BIAOQIAN)." WHERE kefuopenid = '{$openid}' AND fensiopenid = '{$toopenid}'");
		
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_CHAT)." WHERE fkid = {$hasfanskefu['id']} AND kefudel = 0");		
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$hasfanskefu['id']} AND kefudel = 0 ORDER BY time ASC LIMIT ".$nowjl.",".$psize);
		$timestamp = TIMESTAMP;
		$chatcontime = 0;
		foreach($chatcon as $k=>$v){
			if($v['openid'] != $openid){
				$chatcon[$k]['class'] = 'left';
				$chatcon[$k]['avatar'] = $hasfanskefu['fansavatar'];
			}else{
				$chatcon[$k]['class'] = 'right';
				$chatcon[$k]['avatar'] = $hasfanskefu['kefuavatar'];
			}
			
			if(($v['time'] - $chatcontime) > 7200){
				$chatcon[$k]['time'] = $v['time'];
			}else{
				$chatcon[$k]['time'] = '';
			}
			$chatcontime = $v['time'];
			//$chatcon[$k]['content'] = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $v['content']);
			$chatcon[$k]['content'] = $this->guolv($v['content']);
			$chatcon[$k]['content'] = qqface_convert_html($chatcon[$k]['content']);
			
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $chatcon[$k]['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$chatcon[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv) && strpos($vv,'https://res.wx.qq.com') === false){
						$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
					}
				}
			}
			if($v['type'] == 5 || $v['type'] == 6){
				$donetime = $timestamp - $v['time'];
				if($donetime >= 24*3600*3){
					unset($chatcon[$k]);
				}
			}
		}
		$kefuauto = empty($cservice['kefuauto']) ? '' : explode("|",$cservice['kefuauto']);
		$goodsid = intval($_GPC['goodsid']);
		$qudao = trim($_GPC['qudao']);
		if($qudao == 'renren' && $goodsid != 0){
			if(pdo_tableexists('ewei_shop_goods')) {
				$goodsres = pdo_fetch("SELECT title,thumb,id,productprice,costprice,marketprice FROM ".tablename('ewei_shop_goods')." WHERE id = {$goodsid} AND uniacid = {$_W['uniacid']}");
				$goods['title'] = $goodsres['title'];
				$goods['thumb'] = tomedia($goodsres['thumb']);
				$goods['id'] = $goodsres['id'];
				$goods['price'] = $goodsres['marketprice'];
			}
		}
		pdo_update(BEST_FANSKEFU,array('notread'=>0),array('id'=>$hasfanskefu['id']));
		include $this->template("newservicechat");
	}
	
	public function doMobileServicechatajax(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$hasfanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE id = {$fkid}");
		
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_CHAT)." WHERE fkid = {$fkid} AND kefudel = 0");		
		$page = intval($_GPC["page"]);
		$pindex = max(1, $page);
		$psize = 10;
		$allpage = ceil($total/$psize)+1;
		$nowjl = $total-$pindex*$psize;
		if($nowjl < 0){
			$nowjl = 0;
		}
		if($total > $pindex*$psize){
			$tolimit = $psize;
		}else{
			$tolimit = $psize-($pindex*$psize-$total);
		}
		$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE fkid = {$fkid} AND kefudel = 0 ORDER BY time ASC LIMIT ".$nowjl.",".$tolimit);
		$timestamp = TIMESTAMP;
		$chatcontime = 0;
		foreach($chatcon as $k=>$v){
			if(($v['time'] - $chatcontime) > 7200){
				$chatcon[$k]['time'] = $v['time'];
			}else{
				$chatcon[$k]['time'] = '';
			}
			$chatcontime = $v['time'];
			//$chatcon[$k]['content'] = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $v['content']);
			$chatcon[$k]['content'] = $this->guolv($v['content']);
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $chatcon[$k]['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$chatcon[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv)){
						$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
					}
				}
			}
			if($v['type'] == 5 || $v['type'] == 6){
				$donetime = $timestamp - $v['time'];
				if($donetime >= 24*3600*3){
					unset($chatcon[$k]);
				}
			}
		}
		$html = '';
		foreach($chatcon as $k=>$v){
			$htmltime = !empty($v['time']) ? '<div class="time text-c">'.date('Y-m-d H:i:s',$v['time']).'</div>' : '';
			if($v['openid'] != $hasfanskefu['kefuopenid']){
				$class = 'left';
				$avatar = $hasfanskefu['fansavatar'];
			}else{
				$class = 'right';
				$avatar = $hasfanskefu['kefuavatar'];
			}
			if($v['type'] == 3 || $v['type'] == 4){
				$chatconhtml = '<div class="concon"><img src="'.$v['content'].'" class="sssbbb" /></div>';
			}elseif($v['type'] == 5 || $v['type'] == 6){
				if($v['hasyuyindu'] == 0 && $hasfanskefu['kefuopenid'] == $v['toopenid']){
					$weidu = '<span class="weidu">未读</span>';
				}else{
					$weidu = '';
				}
				$chatconhtml = '<div class="concon playvoice flex" data-con="'.$chatcon[$k]['content'].'">
									<i class="a-icon iconfont">&#xe601;</i>
									<span class="miao">'.$v['yuyintime'].'\'\'</span>
									'.$weidu.'
									<div class="flex1"></div>
								</div>';
			}else{
				$chatconhtml = '<div class="concon">'.$v['content'].'</div>';
			}
			$html .= $htmltime.'<div class="'.$class.' flex">
									<img src="'.$avatar.'" class="avatar" />
									<div class="con flex flex1">
										<div class="triangle-'.$class.'"></div>
										'.$chatconhtml.'
										<div class="flex1"></div>
									</div>
								</div>';
		}
		echo $html;
		exit;
	}
	
	public function doMobilezhuizong(){
		include_once ROOT_PATH.'inc/mobile/zhuizong.php';
	}
		
	public function doMobileAllshare(){
		global $_W,$_GPC;
		include_once ROOT_PATH.'qqface.php';
		$openid = $_W['fans']['from_user'];
		if(empty($openid)){
			$message = '请在微信浏览器中打开！';
			include $this->template('error');
			exit;
		}
		$cservice = pdo_fetch("SELECT id,thumb FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$openid}'");
		if(empty($cservice)){
			$message = '您不是客服！';
			include $this->template('error');
			exit;
		}
		if($this->module['config']['issharemsg'] == 0){
			$message = '暂未开通客户记录共享功能，如需要请联系管理员在基本设置中开启！';
			include $this->template('error');
			exit;
		}
		if($this->module['config']['sharetype'] == 1){
			$isingroup = pdo_fetch("SELECT id FROM ".tablename(BEST_KEFUANDGROUP)." WHERE kefuid = {$cservice['id']}");
			if(empty($isingroup)){
				$message = '系统开启了客服组内共享功能，您不属于任何客服组！';
				include $this->template('error');
				exit;
			}
		}
		$toopenid = trim($_GPC['toopenid']);
		if($this->module['config']['sharetype'] == 0){
			$allfanskefu = pdo_fetchall("SELECT id FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}'");
			$fkids = "(";
			foreach($allfanskefu as $k=>$v){
				$fkids .= $v['id'].",";
			}
			$fkids = substr($fkids,0,-1).")";
		}else{
			$allingroup = pdo_fetchall("SELECT groupid FROM ".tablename(BEST_KEFUANDGROUP)." WHERE kefuid = {$cservice['id']}");
			$allingrouparr = array();
			foreach($allingroup as $kk=>$vv){
				$allingrouparr[] = $vv['groupid'];
			}
			$groupcservice = pdo_fetchall("SELECT b.content FROM ".tablename(BEST_KEFUANDGROUP)." as a,".tablename(BEST_CSERVICE)." as b WHERE a.weid = {$_W['uniacid']} AND a.groupid in (".implode(",",$allingrouparr).") AND a.kefuid = b.id AND b.ctype = 1");
			$groupcservicearr = "(";
			foreach($groupcservice as $kk=>$vv){
				$groupcservicearr .= "'".$vv['content']."',";
			}
			$groupcservicearr = substr($groupcservicearr,0,-1).")";
			$allfanskefu = pdo_fetchall("SELECT id FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$toopenid}' AND kefuopenid in {$groupcservicearr}");
			$fkids = "(";
			foreach($allfanskefu as $k=>$v){
				$fkids .= $v['id'].",";
			}
			$fkids = substr($fkids,0,-1).")";
		}
		$chatcon = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND fkid in {$fkids} AND kefudel = 0 AND fansdel = 0 ORDER BY time ASC");
		$timestamp = TIMESTAMP;
		foreach($chatcon as $k=>$v){
			if($v['openid'] != $openid){
				$chatcon[$k]['class'] = 'left';
				$chatcon[$k]['avatar'] = $v['avatar'];
			}else{
				$chatcon[$k]['class'] = 'right';
				$chatcon[$k]['avatar'] = tomedia($cservice['thumb']);
			}
			
			//$chatcon[$k]['content'] = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $v['content']);
			$chatcon[$k]['content'] = $this->guolv($v['content']);
			$chatcon[$k]['content'] = qqface_convert_html($chatcon[$k]['content']);
			
			$chatcon[$k]['content'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $chatcon[$k]['content']);
			$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
			preg_match_all($regex,$chatcon[$k]['content'],$array2);  
			if(!empty($array2[0]) && ($v['type'] == 1 || $v['type'] == 2)){
				foreach($array2[0] as $kk=>$vv){
					if(!empty($vv) && strpos($vv,'https://res.wx.qq.com') === false){
						$chatcon[$k]['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$chatcon[$k]['content']);
					}
				}
			}
			if($v['type'] == 5 || $v['type'] == 6){
				$donetime = $timestamp - $v['time'];
				if($donetime >= 24*3600*3){
					unset($chatcon[$k]);
				}
			}
		}
		$imglist = pdo_fetchall("SELECT * FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND fkid in {$fkids} AND (type = 3 OR type = 4) ORDER BY time DESC");
		include $this->template("allshare");
	}
	
	public function doMobileShuaxinyuyin(){
		global $_W,$_GPC;
		$content = trim($_GPC['content']);
		$chat = pdo_fetch("SELECT openid,toopenid FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND content = '{$content}'");
		if($chat['toopenid'] == $_W['fans']['from_user']){
			pdo_update(BEST_CHAT,array('hasyuyindu'=>1),array('weid'=>$_W['uniacid'],'content'=>$content));
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
	
	public function doMobileAddgroupchat(){
		global $_W,$_GPC;
		if(!checksubmit('submit')){
			exit;
		}
		include_once ROOT_PATH.'emoji/emoji.php';
		$groupid = intval($_GPC['groupid']);
		$group = pdo_fetch("SELECT * FROM ".tablename(BEST_GROUP)." WHERE weid = {$_W['uniacid']} AND id = {$groupid}");
		
		$isgmember = pdo_fetch("SELECT id FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND openid = '{$_W['fans']['from_user']}' AND groupid = {$groupid}");
		if(empty($isgmember)){
			$resArr['error'] = 1;
			$resArr['msg'] = '您不是群成员！';
			echo json_encode($resArr);
			exit;
		}
		if($group['jinyan'] == 1 && $_W['fans']['from_user'] != $group['admin']){
			$resArr['error'] = 1;
			$resArr['msg'] = '该群只能管理员发言！';
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
		$chatcontent = emoji_docomo_to_unified($chatcontent);
		$chatcontent = emoji_unified_to_html($chatcontent);
		$data['openid'] = $_W['fans']['from_user'];
		$data['groupid'] = $groupid;
		$data['time'] = TIMESTAMP;
		$data['content'] = $chatcontent;
		$data['weid'] = $_W['uniacid'];
		$data['type'] = intval($_GPC['type']);
		$data['yuyintime'] = intval($_GPC['yuyintime']/1000);
		$iscservice = pdo_fetch("SELECT name,thumb FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$data['openid']}'");
		if(!empty($iscservice)){
			$data['avatar'] = tomedia($iscservice['thumb']);
			$data['nickname'] = $iscservice['name'];
		}else{
			$data['avatar'] =  empty($_W['fans']['tag']['avatar']) ? tomedia($this->module['config']['defaultavatar']) : $_W['fans']['tag']['avatar'];
			$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : str_replace('\'', '\'\'',$_W['fans']['tag']['nickname']);
		}
		pdo_insert(BEST_GROUPCONTENT,$data);
		if($data['type'] == 3){
			$tplcon = '图片消息';
		}elseif($data['type'] == 5){
			$tplcon = '语音消息';
		}else{
			if(strpos($data['content'],'span class=')){
				$tplcon = '表情消息';
			}else{
				$tplcon = $data['content'];
			}
		}
		
		if($this->module['config']['isgrouptplon'] == 1 && $group['isfs'] == 1){
			$allgroupmember = pdo_fetchall("SELECT * FROM ".tablename(BEST_GROUPMEMBER)." WHERE weid = {$_W['uniacid']} AND groupid = {$data['groupid']} AND openid != '{$data['openid']}' AND status = 1 AND txkaiguan = 1");	
			if(!empty($this->module['config']['tpl_kefu'])){
				$guotime = TIMESTAMP-$group['lasttime'];
				if($guotime > $this->module['config']['grouptplminute']){
					foreach($allgroupmember as $k=>$v){
						$or_paysuccess_redirect = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("groupchatdetail",array('groupid'=>$data['groupid'])));		
						$postdata = array(
							'first' => array(
								'value' => $data['nickname'].'在['.$group['groupname'].']中发了新消息！',
								'color' => '#990000'
							),
							'keyword1' => array(
								'value' => $tplcon,
								'color' => '#ff510'
							),
							'keyword2' => array(
								'value' => '点击此消息查看',
								'color' => '#ff510'
							),
							'remark' => array(
								'value' => '发布时间：'.date("Y-m-d H:i:s",TIMESTAMP),
								'color' => '#ff510'
							),							
						);
						$account_api = WeAccount::create();
						$account_api->sendTplNotice($v['openid'],$this->module['config']['tpl_kefu'],$postdata,$or_paysuccess_redirect,'#980000');
					}
				}	
			}else{
				foreach($allgroupmember as $k=>$v){
					$guotime = TIMESTAMP-$group['lasttime'];
					if($guotime > $this->module['config']['grouptplminute']){
						$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("groupchatdetail",array('groupid'=>$data['groupid'])));
						$concon = $data['nickname'].'在['.$group['groupname'].']中发了新消息！';									
						$row = array();
						$row['title'] = urlencode('新消息提醒');
						$row['description'] = urlencode($concon);
						$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
						$row['url'] = $texturl;
						$news[] = $row;
						$send['touser'] = trim($v['openid']);
						$send['msgtype'] = 'news';
						$send['news']['articles'] = $news;
						$account_api = WeAccount::create();
						$account_api->sendCustomNotice($send);
					}
				}
			}	
		}
		pdo_update(BEST_GROUP,array('lasttime'=>TIMESTAMP),array('id'=>$data['groupid']));
        $resArr['error'] = 0;
        $resArr['msg'] = '';
		$resArr['content'] = $this->doReplacecon($data['content'],$data['type'],$data['yuyintime']);
		$resArr['yuyincon'] = $data['type'] == 5 ? '<span class="miaoshu">'.$data['yuyintime'].'\'\'</span>' : '';
		$resArr['datetime'] = date("Y-m-d H:i:s",$data['time']);
		$resArr['nickname'] = $data['nickname'];
		$resArr['avatar'] = $data['avatar'];
        echo json_encode($resArr);
        exit;
	}
	
	public function doMobileXiaxian(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$type = trim($_GPC['type']);
		if($type == 'fans'){
			$data['fszx'] = 0;
			$data['kefunotread'] = 0;
			pdo_update(BEST_FANSKEFU,$data,array('id'=>$fkid));
		}
		if($type == 'kefu'){
			$data['kfzx'] = 0;
			$data['notread'] = 0;
			pdo_update(BEST_FANSKEFU,$data,array('id'=>$fkid));
		}
	}
	
	public function doMobileShangxian(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$type = trim($_GPC['type']);
		if($type == 'fans'){
			$data['fszx'] = 1;
			$data['kefunotread'] = 0;
			pdo_update(BEST_FANSKEFU,$data,array('id'=>$fkid));
		}
		if($type == 'kefu'){
			$data['kfzx'] = 1;
			$data['notread'] = 0;
			pdo_update(BEST_FANSKEFU,$data,array('id'=>$fkid));
		}
	}
	
	public function doMobileAddchat(){
		global $_W,$_GPC;
		if(!checksubmit('submit')){
			exit;
		}
		include_once ROOT_PATH.'emoji/emoji.php';
		$chatcontent = trim($_GPC['content']);
		$lastcon = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $chatcontent);
		if(empty($chatcontent)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入对话内容！';
			echo json_encode($resArr);
			exit;
		}
		$qudao = trim($_GPC['qudao']);
		$goodsid = intval($_GPC['goodsid']);
		$cservice = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 AND content = '{$_GPC['toopenid']}'");
		if($cservice['iszx'] == 1){
			if($cservice['isrealzx'] == 0){
				$notonlinemsg = !empty($cservice['notonline']) ? $cservice['notonline'] : '客服不在线哦！';
				$resArr['error'] = 1;
				$resArr['msg'] = $notonlinemsg;
				echo json_encode($resArr);
				exit;
			}
		}else{
			if($cservice['lingjie'] == 1){
				$nowhour = intval(date("H",TIMESTAMP));
				if(($nowhour+1) > $cservice['endhour'] && $nowhour < $cservice['starthour']){
					$notonlinemsg = !empty($cservice['notonline']) ? $cservice['notonline'] : '客服不在线哦！';
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}else{
				$nowhour = intval(date("H",TIMESTAMP));
				if($nowhour < $cservice['starthour'] || ($nowhour+1) > $cservice['endhour']){
					$notonlinemsg = !empty($cservice['notonline']) ? $cservice['notonline'] : '客服不在线哦！';
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}
		}

		if($cservice['isxingqi'] == 1){
			$notonlinemsg = !empty($cservice['notonline']) ? $cservice['notonline'] : '客服不在线哦！';
			$zhouji = date("w");
			if( $zhouji == "1" ){ 
				if($cservice['day1'] == 0){
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}else if( $zhouji == "2" ){ 
				if($cservice['day2'] == 0){
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}else if( $zhouji == "3" ){ 
				if($cservice['day3'] == 0){
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}else if( $zhouji == "4" ){ 
				if($cservice['day4'] == 0){
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}else if( $zhouji == "5" ){ 
				if($cservice['day5'] == 0){
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}else if( $zhouji == "6" ){ 
				if($cservice['day6'] == 0){
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				}
			}else if( $zhouji == "0" ){ 
				if($cservice['day7'] == 0){
					$resArr['error'] = 1;
					$resArr['msg'] = $notonlinemsg;
					echo json_encode($resArr);
					exit;
				} 
			}else{ 
				$resArr['error'] = 1;
				$resArr['msg'] = $notonlinemsg;
				echo json_encode($resArr);
				exit;
			}; 
		}
		$chatcontent = emoji_docomo_to_unified($chatcontent);
		$chatcontent = emoji_unified_to_html($chatcontent);
		
		if(empty($_W['fans']['from_user'])){
			$data['openid'] = $_W['clientip'];
			$jqruserid = str_replace(".","",$data['openid']);
			$data['nickname'] = '游客';
			$data['avatar'] =  tomedia($this->module['config']['defaultavatar']);
		}else{
			$data['openid'] = $_W['fans']['from_user'];
			$jqruserid = $data['openid'];
			$data['nickname'] = empty($_W['fans']['tag']['nickname']) ? '匿名用户' : str_replace('\'', '\'\'',$_W['fans']['tag']['nickname']);
			$data['avatar'] =  empty($_W['fans']['tag']['avatar']) ? tomedia($this->module['config']['defaultavatar']) : $_W['fans']['tag']['avatar'];
		}
		
		$ishei = pdo_fetch("SELECT id FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$data['openid']}' AND ishei = 1");
		if(!empty($ishei)){			
			$resArr['error'] = 1;
			$resArr['msg'] = '您暂时不能咨询！';
			echo json_encode($resArr);
			exit;
		}
		
		$data['toopenid'] = trim($_GPC['toopenid']);
		$data['time'] = TIMESTAMP;
		$data['content'] = $chatcontent;
		$data['weid'] = $_W['uniacid'];
		$data['fkid'] = intval($_GPC['fkid']);
		$type = intval($_GPC['type']);
		$data['type'] = $type;
		$data['yuyintime'] = intval($_GPC['yuyintime']/1000);
		if($type == 3 || $type == 4){
			$tplcon = $data['nickname'].'发送了图片';
		}elseif($type == 5 || $type == 6){
			$tplcon = $data['nickname'].'发送了语音';
		}else{
			if(strpos($data['content'],'span class=')){
				$tplcon = $data['nickname'].'发送了表情';
			}else{
				$tplcon = $data['content'];
			}
		}
		$tplcon = $this->guolv($tplcon);
		pdo_insert(BEST_CHAT,$data);
		
		//是否触发机器人
		if($this->module['config']['zdhf'] == 1){
			$zdhf = pdo_fetch("select * from ".tablename(BEST_ZIDONGHUIFU)." where weid = {$_W['uniacid']} AND ((title = '{$chatcontent}' AND type = 1) OR (title like '%{$chatcontent}%' AND type = 2)) ORDER BY paixu DESC");
			if(!empty($zdhf)){
				$resArr['jqr'] = 1;
				$jqrtime = $data['time']+1;
				$resArr['jqrtime'] = date("Y-m-d H:i:s",$jqrtime);
				$resArr['hftype'] = $zdhf['hftype'];
				
				if($zdhf['hftype'] == 0){
					$datajqr['content'] = $zdhf['content'];
					$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
					preg_match_all($regex,$zdhf['content'],$array2);  
					if(!empty($array2[0])){
						foreach($array2[0] as $kk=>$vv){
							if(!empty($vv)){
								$zdhf['content'] = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$zdhf['content']);
							}
						}
					}
					$resArr['jqrcontent'] = $zdhf['content'];
				}else{
					$resArr['jqrcontent'] = $datajqr['content'] = tomedia($zdhf['imgcon']);
				}
				$resArr['jqravatar'] = tomedia($cservice['thumb']);
				
				$datajqr['weid'] = $_W['uniacid'];
				$datajqr['fkid'] = intval($_GPC['fkid']);
				$datajqr['openid'] = trim($_GPC['toopenid']);
				$datajqr['toopenid'] = $data['openid'];
				$datajqr['time'] = $jqrtime;
				$datajqr['nickname'] = $cservice['name'];
				$datajqr['avatar'] = tomedia($cservice['thumb']);
				$datajqr['type'] = $zdhf['hftype'] == 0 ? 2 : 4;
				$datajqr['isjqr'] = 1;
				pdo_insert(BEST_CHAT,$datajqr);
			}
		}

		
		$fanskefu = pdo_fetch("SELECT lasttime,kfzx FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$data['toopenid']}' AND fansopenid = '{$data['openid']}'");
		//if($fanskefu['kfzx'] == 1){
		//	pdo_query("update ".tablename(BEST_FANSKEFU)." set guanlinum=guanlinum+1 where id=:id", array(":id" => $data['fkid']));
		//}else{
			pdo_query("update ".tablename(BEST_FANSKEFU)." set notread=notread+1,guanlinum=guanlinum+1 where id=:id", array(":id" => $data['fkid']));
		//}
		
		$guotime = TIMESTAMP-$fanskefu['lasttime'];
		//if($this->module['config']['istplon'] == 1 && $guotime > $this->module['config']['kefutplminute'] && $fanskefu['kfzx'] == 0){
			
		/*$path = ROOT_PATH.'messi.txt';
		$myfile = fopen($path, "w") or die("Unable to open file!");
		fwrite($myfile, '2222');
		fclose($myfile);*/
			
		if($this->module['config']['istplon'] == 1 && $guotime > $this->module['config']['kefutplminute']){
			
			/*$myfile = fopen($path, "w") or die("Unable to open file!");
			fwrite($myfile, '3333');
			fclose($myfile);*/
			
			if(!empty($this->module['config']['tpl_kefu'])){				
				$or_paysuccess_redirect = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("servicechat",array('toopenid'=>$data['openid'],'qudao'=>$qudao,'goodsid'=>$goodsid)));		
				$postdata = array(
					'first' => array(
						'value' => $data['nickname'].'向你发起了咨询！',
						'color' => '#990000'
					),
					'keyword1' => array(
						'value' => $tplcon,
						'color' => '#ff510'
					),
					'keyword2' => array(
						'value' => "点击此消息尽快回复",
						'color' => '#ff510'
					),
					'remark' => array(
						'value' => '咨询时间：'.date("Y-m-d H:i:s",TIMESTAMP),
						'color' => '#ff510'
					),							
				);
				$account_api = WeAccount::create();
				$account_api->sendTplNotice($data['toopenid'],$this->module['config']['tpl_kefu'],$postdata,$or_paysuccess_redirect,'#980000');
				
			}else{
				$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("servicechat",array('ssopenid'=>$data['toopenid'],'toopenid'=>$data['openid'],'qudao'=>$qudao,'goodsid'=>$goodsid)));
				$concon = $data['nickname'].'发起了咨询！'.$tplcon.'。';
				$row = array();
				$row['title'] = urlencode('新消息提醒');
				$row['description'] = urlencode($concon);
				$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
				$row['url'] = $texturl;
				$news[] = $row;
				$send['touser'] = $data['toopenid'];
				$send['msgtype'] = 'news';
				$send['news']['articles'] = $news;
				$account_api = WeAccount::create();
				$account_api->sendCustomNotice($send);
			}
		}
		
		pdo_query("update ".tablename(BEST_FANSKEFU)." set fansdel=0,kefudel=0,lastcon='{$lastcon}',msgtype={$type},lasttime=:lasttime where id=:id", array(":lasttime" => TIMESTAMP, ":id" => $data['fkid']));
        $resArr['error'] = 0;
        $resArr['msg'] = '';
		$resArr['content'] = $this->doReplacecon($data['content'],$data['type'],$data['yuyintime']);
		$resArr['datetime'] = date("Y-m-d H:i:s",$data['time']);
        echo json_encode($resArr);
        exit;
	}
	
	public function doMobileDonotread(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$type = trim($_GPC['type']);
		if($type == 'fans'){
			$data['kefunotread'] = 0;
			pdo_update(BEST_FANSKEFU,$data,array('id'=>$fkid));
		}
		if($type == 'kefu'){
			$data['notread'] = 0;
			pdo_update(BEST_FANSKEFU,$data,array('id'=>$fkid));
		}
	}
	
	public function doMobileDonotreadxcx(){
		global $_W,$_GPC;
		$fkid = intval($_GPC['fkid']);
		$data['notread'] = 0;
		pdo_update(BEST_XCXFANSKEFU,$data,array('id'=>$fkid));
	}
	
	public function doMobileAddchat2(){
		global $_W,$_GPC;
		if(!checksubmit('submit')){
			exit;
		}
		include_once ROOT_PATH.'emoji/emoji.php';
		$chatcontent = trim($_GPC['content']);
		$kefulastcon = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $chatcontent);		
		if(empty($chatcontent)){
			$resArr['error'] = 1;
			$resArr['msg'] = '请输入对话内容！';
			echo json_encode($resArr);
			exit;
		}
		$qudao = trim($_GPC['qudao']);
		$goodsid = intval($_GPC['goodsid']);
		$chatcontent = emoji_docomo_to_unified($chatcontent);
		$chatcontent = emoji_unified_to_html($chatcontent);
		$data['openid'] = $_W['fans']['from_user'];
		$touser = pdo_fetch("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND content = '{$data['openid']}'");
		$data['nickname'] = $touser['name'];
		$data['avatar'] = tomedia($touser['thumb']);
		$data['toopenid'] = trim($_GPC['toopenid']);
		$data['time'] = TIMESTAMP;
		$data['content'] = $chatcontent;
		$data['weid'] = $_W['uniacid'];
		$data['fkid'] = intval($_GPC['fkid']);
		$type = intval($_GPC['type']);
		$data['type'] = $type;
		$data['yuyintime'] = intval($_GPC['yuyintime']/1000);
		if($type == 3 || $type == 4){
			$tplcon = $data['nickname'].'给您发送了图片';
		}elseif($type == 5 || $type == 6){
			$tplcon = $data['nickname'].'给您发送了语音';
		}else{
			if(strpos($data['content'],'span class=')){
				$tplcon = $data['nickname'].'给您发送了表情';
			}else{
				$tplcon = $data['content'];
			}
		}
		$tplcon = $this->guolv($tplcon);	
		pdo_insert(BEST_CHAT,$data);
		
		$fanskefu = pdo_fetch("SELECT kefulasttime,fszx FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND kefuopenid = '{$data['openid']}' AND fansopenid = '{$data['toopenid']}'");
		//if($fanskefu['fszx'] == 1){
		//	pdo_query("update ".tablename(BEST_FANSKEFU)." set guanlinum=guanlinum+1 where id=:id", array(":id" => $data['fkid']));
		//}else{
			pdo_query("update ".tablename(BEST_FANSKEFU)." set kefunotread=kefunotread+1,guanlinum=guanlinum+1 where id=:id", array(":id" => $data['fkid']));
		//}
		
		$guotime = TIMESTAMP-$fanskefu['kefulasttime'];
		//if($this->module['config']['istplon'] == 1 && $guotime > $this->module['config']['kefutplminute'] && $fanskefu['fszx'] == 0){
		if($this->module['config']['istplon'] == 1 && $guotime > $this->module['config']['kefutplminute']){
			if(!empty($this->module['config']['tpl_kefu'])){
				$lastmsg = pdo_fetch("SELECT content,type FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND openid = '{$data['toopenid']}' AND toopenid = '{$data['openid']}' ORDER BY time DESC");
				if($lastmsg['type'] == 3 || $lastmsg['type'] == 4){
					$lastmsgcon = '图片消息';
				}elseif($lastmsg['type'] == 5 || $lastmsg['type'] == 6){
					$lastmsgcon = '语音消息';
				}else{
					$lastmsgcon = $lastmsg['content'];
				}
				$or_paysuccess_redirect = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("chat",array('toopenid'=>$data['openid'],'qudao'=>$qudao,'goodsid'=>$goodsid)));		
				$postdata = array(
					'first' => array(
						'value' => $data['nickname'].'回复了您！',
						'color' => '#990000'
					),
					'keyword1' => array(
						'value' => $lastmsgcon,
						'color' => '#ff510'
					),
					'keyword2' => array(
						'value' => $tplcon,
						'color' => '#ff510'
					),
					'remark' => array(
						'value' => '回复时间：'.date("Y-m-d H:i:s",TIMESTAMP),
						'color' => '#ff510'
					),							
				);
				$account_api = WeAccount::create();
				$account_api->sendTplNotice($data['toopenid'],$this->module['config']['tpl_kefu'],$postdata,$or_paysuccess_redirect,'#980000');
			}else{
				$texturl = $_W["siteroot"].'app/'.str_replace("./","",$this->createMobileUrl("chat",array('ssopenid'=>$data['toopenid'],'toopenid'=>$data['openid'],'qudao'=>$qudao,'goodsid'=>$goodsid)));
				$concon = $data['nickname'].'回复了您！回复内容：'.$tplcon.'。';								
				$row = array();
				$row['title'] = urlencode('新消息提醒');
				$row['description'] = urlencode($concon);
				$row['picurl'] = $_W["siteroot"].'/addons/cy163_customerservice/static/tuwen.jpg';
				$row['url'] = $texturl;
				$news[] = $row;
				$send['touser'] = trim($_GPC['toopenid']);
				$send['msgtype'] = 'news';
				$send['news']['articles'] = $news;
				$account_api = WeAccount::create();
				$account_api->sendCustomNotice($send);
			}
		}
		pdo_query("update ".tablename(BEST_FANSKEFU)." set fansdel=0,kefudel=0,kefulastcon='{$kefulastcon}',kefulasttime=:kefulasttime,kefumsgtype={$type} where id=:id", array(":kefulasttime" => TIMESTAMP, ":id" => $data['fkid']));
        $resArr['error'] = 0;
        $resArr['msg'] = '';
		$resArr['content'] = $this->doReplacecon($data['content'],$data['type'],$data['yuyintime']);
		$resArr['datetime'] = date("Y-m-d H:i:s",$data['time']);
        echo json_encode($resArr);
        exit;
	}
	
	
	public function doReplacecon($content,$msgtype,$yuyintime){
		$content = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $content);
		$content = $this->guolv($content);
		$regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
		preg_match_all($regex,$content,$array2);  
		if(!empty($array2[0]) && ($msgtype == 1 || $msgtype == 2)){
			foreach($array2[0] as $kk=>$vv){
				if(!empty($vv)){
					$content = str_replace($vv,"<a href='".$vv."'>".$vv."</a>",$content);
				}
			}
		}
		if($msgtype == 1 || $msgtype == 2){
			$content = '<div class="concon">'.$content.'</div>';
		}elseif($msgtype == 3 || $msgtype == 4){					
			$content = '<div class="concon"><img src="'.$content.'" class="sssbbb" /></div>';
		}elseif($msgtype == 5 || $msgtype == 6){					
			$content = '<div class="concon voiceplay flex" data-con="'.$content.'">
							<i class="a-icon iconfont">&#xe601;</i>
							<span class="miao">'.$yuyintime.'\'\'</span>
							<div class="flex1"></div>
						</div>';
		}
		return $content;
	}
	
	public function doMobileGetmedia(){
		global $_W, $_GPC;
		//$access_token = WeAccount::token();
		$account_api = WeAccount::create();
		$access_token = $account_api->getAccessToken();
		$media_id = $_GPC['media_id'];
		if(empty($media_id)){
			$resarr['error'] = 1;
			$resarr['message'] = '获取微信媒体参数失败！';
			die(json_encode($resarr));
		}
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
		
		$response = ihttp_get($url);
		if(is_error($response)) {			
			$resarr['error'] = 1;
			$resarr['message'] = "访问公众平台接口失败, 错误: {$response['message']}";
			die(json_encode($resarr));
		}
		
		$result = @json_decode($response['content'], true);
		if(!empty($result['errcode'])) {
			$resarr['error'] = 1;
			$resarr['message'] = "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}";
			die(json_encode($resarr));
		}
		
		$updir = "../attachment/images/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/";
        if (!file_exists($updir)) {
            mkdir($updir, 0777, true);
        }
		$randimgurl = "images/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/".date('YmdHis').rand(1000,9999).'.jpg';
        $targetName = "../attachment/".$randimgurl;
		
		//保存头像图片
		$fp = @fopen($targetName, 'wb');
		@fwrite($fp, $response['content']);
		@fclose($fp);
		
		if(file_exists($targetName)){
			$resarr['error'] = 0;			
			$this->mkThumbnail($targetName,640,0,$targetName);
			//上传到远程
			if($this->module['config']['isqiniu'] == 1){
				$remotestatus = $this->doQiuniu($randimgurl,true);
				if (is_error($remotestatus)) {
					$resarr['error'] = 1;
					$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
					die(json_encode($resarr));
				} else {
					$resarr['realimgurl'] = $randimgurl;
					$resarr['imgurl'] =  $this->module['config']['qiniuurl']."/".$randimgurl;
					$resarr['message'] = '上传成功';
					die(json_encode($resarr));
				}
			}elseif($this->module['config']['isqiniu'] == 3){
				if(!empty($_W['setting']['remote']['type'])){
					load()->func('file');
					$remotestatus = file_remote_upload($randimgurl,true);
					if (is_error($remotestatus)) {
						$resarr['error'] = 1;
						$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
						die(json_encode($resarr));
					} else {
						$resarr['realimgurl'] = $randimgurl;
						$resarr['imgurl'] = tomedia($randimgurl);
						$resarr['message'] = '上传成功';
						die(json_encode($resarr));
					}
				}
			}
			$resarr['realimgurl'] = $randimgurl;
			$resarr['imgurl'] = tomedia($randimgurl);
			$resarr['message'] = '上传成功';
		}else{
			$resarr['error'] = 1;
			$resarr['message'] = '上传失败';
		}
		echo json_encode($resarr,true);
		exit;
    }
	
	
	/**uploadify上传图片用**/
	public function doMobilePcupload(){		
		global $_W,$_FILES,$_GPC;
		$url = $_FILES["jUploaderFile"]["tmp_name"];
		$updir = "../attachment/images/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/";
        if (!file_exists($updir)) {
            mkdir($updir, 0777, true);
        }
		$randimgurl = "images/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/".date('YmdHis').rand(1000,9999).".jpg";		
		$targetName = "../attachment/".$randimgurl;
		move_uploaded_file($url,$targetName);
		if(file_exists($targetName)){
			$resarr['error'] = 0;			
			$this->mkThumbnail($targetName,640,0,$targetName);
			//上传到远程
			if(!empty($_W['setting']['remote']['type'])){
				load()->func('file');
				$remotestatus = file_remote_upload($randimgurl,true);
				if (is_error($remotestatus)) {
					$resarr['error'] = 1;
					$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
					die(json_encode($resarr));
				} else {
					$resarr['realimgurl'] = $randimgurl;
					$resarr['imgurl'] = tomedia($randimgurl);
					$resarr['message'] = '上传成功';
					die(json_encode($resarr));
				}
			}
			$resarr['realimgurl'] = $randimgurl;
			$resarr['imgurl'] = tomedia($randimgurl);
			$resarr['message'] = '上传成功';
		}else{
			$resarr['error'] = 1;
			$resarr['message'] = '上传文件失败';
		}
		echo json_encode($resarr,true);
		exit;
	}
	
	
	public function doQiuniu($filename, $auto_delete_local = true){
		global $_W;
		load()->func('file');
		require_once(IA_ROOT . '/framework/library/qiniu/autoload.php');
		$auth = new Qiniu\Auth($this->module['config']['qiniuaccesskey'],$this->module['config']['qiniusecretkey']);
		$config = new Qiniu\Config();
		$uploadmgr = new Qiniu\Storage\UploadManager($config);
		$putpolicy = Qiniu\base64_urlSafeEncode(json_encode(array('scope' => $this->module['config']['qiniubucket'].':'. $filename)));
		$uploadtoken = $auth->uploadToken($this->module['config']['qiniubucket'], $filename, 3600, $putpolicy);
		list($ret, $err) = $uploadmgr->putFile($uploadtoken, $filename, ATTACHMENT_ROOT. '/'.$filename);
		if ($auto_delete_local) {
			file_delete($filename);
		}
		if ($err !== null) {
			$resarr['error'] = 1;
			$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
			die(json_encode($resarr));
		} else {
			return true;
		}
	}
	
	public function doMobileGetvoice(){
		global $_W, $_GPC;
		$access_token = WeAccount::token();
		$media_id = $_GPC['media_id'];
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
		$updir = "../attachment/audios/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/";
        if (!file_exists($updir)) {
            mkdir($updir, 0777, true);
        }
		$randvoiceurl = "audios/".$_W['uniacid']."/".date("Y",time())."/".date("m",time())."/".date('YmdHis').rand(1000,9999).'.amr';
        $targetName = "../attachment/".$randvoiceurl;
        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
		if(file_exists($targetName)){
			$resarr['error'] = 0;			
			//上传到远程
			if(!empty($_W['setting']['remote']['type'])){
				load()->func('file');
				$remotestatus = file_remote_upload($randvoiceurl,true);
				if (is_error($remotestatus)) {
					$resarr['error'] = 1;
					$resarr['message'] = '远程附件上传失败，请检查配置并重新上传';
					file_delete($randvoiceurl);
					die(json_encode($resarr));
				} else {
					file_delete($randvoiceurl);
					$resarr['realvoiceurl'] = $randvoiceurl;
					$resarr['voiceurl'] = tomedia($randvoiceurl);
					$resarr['message'] = '上传成功';
					die(json_encode($resarr));
				}
			}
			$resarr['realvoiceurl'] = $randvoiceurl;
			$resarr['voiceurl'] = tomedia($randvoiceurl);
			$resarr['message'] = '上传成功';
		}else{
			$resarr['error'] = 1;
			$resarr['message'] = '上传失败';
		}
		echo json_encode($resarr,true);
		exit;
	}
	
	
	public function doMobileMychat(){
		global $_W, $_GPC;
		if(empty($_W['fans']['from_user'])){
			$openid = $_W['clientip'];
		}else{
			$openid = $_W['fans']['from_user'];
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$isservice = pdo_fetch("SELECT id FROM ".tablename(BEST_CSERVICE)." WHERE ctype = 1 AND weid = {$_W['uniacid']} AND content = '{$openid}'");
			$psize = 20;
			if(!empty($isservice)){
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE kefuopenid = '{$openid}' AND (lastcon != '' OR notread > 0) AND kefudel = 0");
				$allpage = ceil($total/$psize)+1;
				$page = intval($_GPC["page"]);
				$pindex = max(1, $page);
				
				$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE kefuopenid = '{$openid}' AND (lastcon != '' OR notread > 0) AND kefudel = 0 ORDER BY notread DESC,lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
			}else{
				$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND lastcon != '' AND fansdel = 0");
				$allpage = ceil($total/$psize)+1;
				$page = intval($_GPC["page"]);
				$pindex = max(1, $page);
				$chatlist = pdo_fetchall("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE weid = {$_W['uniacid']} AND fansopenid = '{$openid}' AND lastcon != '' AND fansdel = 0 ORDER BY notread DESC,lasttime DESC LIMIT ".($pindex - 1)*$psize.",".$psize);
			}
			$isajax = intval($_GPC['isajax']);
			if($isajax == 1){
				$html = '';
				foreach($chatlist as $kk=>$vv){
					if(!empty($isservice)){
						if($vv['msgtype'] == 4){
							$con = '<span style="color:#900;">[图片消息]</span>';
						}elseif($vv['msgtype'] == 5){
							$con = '<span style="color:green;">[语音消息]</span>';
						}else{
							$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['lastcon']);
						}
						$mychatbadge = $vv['notread'] > 0 ? '<span class="mychatbadge">'.$vv['notread'].'</span>' : '';
						$html .= '<div class="item flex textellipsis1 fkid'.$vv['id'].'">
									<a href="'.$this->createMobileUrl('servicechat',array('toopenid'=>$vv['fansopenid'])).'" class="flex tohref textellipsis1">
										<img src="'.$vv['fansavatar'].'">'.$mychatbadge.'
										<div class="text textellipsis1">
											<div class="name textellipsis1">'.$vv['fansnickname'].'</div>
											<div class="lastmsg textellipsis1">'.$con.'</div>
										</div>
									</a>
									<div class="timedo">
										<div class="time">'.$this->format_date($vv['lasttime']).'</div>
										<div class="dodel" data-fkid="'.$vv['id'].'">删除</div>
									</div>
								</div>';
				
					}else{
						if($vv['kefumsgtype'] == 3){
							$con = '<span style="color:#900;">[图片消息]</span>';
						}elseif($vv['kefumsgtype'] == 6){
							$con = '<span style="color:green;">[语音消息]</span>';
						}else{
							$con = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '[无法识别字符]', $vv['kefulastcon']);
						}
						$mychatbadge = $vv['kefunotread'] > 0 ? '<span class="mychatbadge">'.$vv['kefunotread'].'</span>' : '';
						$html .= '<div class="item flex textellipsis1 fkid'.$vv['id'].'">
									<a href="'.$this->createMobileUrl('chat',array('toopenid'=>$vv['kefuopenid'])).'" class="flex tohref textellipsis1">
										<img src="'.$vv['kefuavatar'].'">'.$mychatbadge.'
										<div class="text textellipsis1">
											<div class="name textellipsis1">'.$vv['kefuonickname'].'</div>
											<div class="lastmsg textellipsis1">'.$con.'</div>
										</div>
									</a>
									<div class="timedo">
										<div class="time">'.$this->format_date($vv['kefuolasttime']).'</div>
										<div class="dodel" data-fkid="'.$vv['id'].'">删除</div>
									</div>
								</div>';
					}
				}
				echo $html;
				exit;
			}
			include $this->template('mychat');
		}elseif($operation == 'delete'){
			$fkid = intval($_GPC['fkid']);
			$type = trim($_GPC['type']);
			$fanskefu = pdo_fetch("SELECT * FROM ".tablename(BEST_FANSKEFU)." WHERE id = {$fkid}");
			if($type == 'kehu'){
				$dataup['fansdel'] = 1;
			}
			if($type == 'kefu'){
				$dataup['kefudel'] = 1;
			}
			pdo_update(BEST_CHAT,$dataup,array('fkid'=>$fkid));
			pdo_update(BEST_FANSKEFU,$dataup,array('id'=>$fkid));
			//pdo_delete(BEST_FANSKEFU,array('id'=>$fkid));
			$resArr['error'] = 0;
			$resArr['message'] = '恭喜您，删除聊天记录成功！';
			echo json_encode($resArr,true);
			exit;
		}
	}
	
	/** 
	 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp） 
	 * @author ruxing.li 
	 * @param  string $src      源图片路径 
	 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放） 
	 * @param  int    $width    缩略图高度（只指定宽度时进行等比缩放） 
	 * @param  string $filename 保存路径（不指定时直接输出到浏览器） 
	 * @return bool 
	 */  
	public function mkThumbnail($src, $width = null, $height = null, $filename = null) {  
		if (!isset($width) && !isset($height))  
			return false;  
		if (isset($width) && $width <= 0)  
			return false;  
		if (isset($height) && $height <= 0)  
			return false;  
	  
		$size = getimagesize($src);  
		if (!$size)  
			return false;  
	  
		list($src_w, $src_h, $src_type) = $size;  
		$src_mime = $size['mime'];  
		switch($src_type) {  
			case 1 :  
				$img_type = 'gif';  
				break;  
			case 2 :  
				$img_type = 'jpeg';  
				break;  
			case 3 :  
				$img_type = 'png';  
				break;  
			case 15 :  
				$img_type = 'wbmp';  
				break;  
			default :  
				return false;  
		}  
	  
		if (!isset($width))  
			$width = $src_w * ($height / $src_h);  
		if (!isset($height))  
			$height = $src_h * ($width / $src_w);  
	  
		$imagecreatefunc = 'imagecreatefrom' . $img_type;  
		$src_img = $imagecreatefunc($src);  
		$dest_img = imagecreatetruecolor($width, $height);  
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);  
	  
		$imagefunc = 'image' . $img_type;  
		if ($filename) {  
			$imagefunc($dest_img, $filename);  
		} else {  
			header('Content-Type: ' . $src_mime);  
			$imagefunc($dest_img);  
		}  
		imagedestroy($src_img);  
		imagedestroy($dest_img);  
		return true;  
	}
	
	public function format_date($time){
		$t=time()-$time;
		$f=array(
			'31536000'=>'年',
			'2592000'=>'个月',
			'604800'=>'星期',
			'86400'=>'天',
			'3600'=>'小时',
			'60'=>'分钟',
			'1'=>'秒'
		);
		foreach ($f as $k=>$v)    {
			if (0 !=$c=floor($t/(int)$k)) {
				return $c.$v.'前';
			}
		}
	}

}