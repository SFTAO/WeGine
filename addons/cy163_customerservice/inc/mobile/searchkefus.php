<?php
global $_W, $_GPC;
$styletype = intval($_GPC['styletype']);
$keyword = $_GPC['keyword'];
if(empty($keyword)){
	$resArr['error'] = 1;
	$resArr['message'] = "请输入关键词搜索！";
	echo json_encode($resArr);
	exit;
}

$html = '';
$cservicegroup = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICEGROUP)." WHERE weid = {$_W['uniacid']} AND ishow = 1 AND fid = 0 AND name like '%{$keyword}%' ORDER BY displayorder");
$cservice = pdo_fetchall("SELECT * FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND (ishow = 0 OR (isrealzx = 1 AND ishow = 2 AND iszx = 1)) AND ctype = 1 AND name like '%{$keyword}%' ORDER BY displayorder");
if($styletype == 0){
	if(!empty($cservicegroup)){
		foreach($cservicegroup as $k=>$v){
			$html .= '<a href="'.$this->createMobileUrl('groupchat',array('id'=>$v['id'])).'">
						<div class="item flex">
							<img src="'.tomedia($v['thumb']).'">
							<div class="right flex">
								<div class="kefuname">'.$v['name'].'</div>
								<div class="fname text-r">'.$v['typename'].'</div>
								<div class="iconfont text-r">&#xe6c2;</div>
							</div>
						</div>
					</a>';
		}
	}

	
	if(!empty($cservice)){
		foreach($cservice as $k=>$v){
			if($v['ctype'] == 1){
				$url = $this->createMobileUrl('chat',array('toopenid'=>$v['content']));
			}
			if($v['ctype'] == 2){
				$url = "http://wpa.qq.com/msgrd?v=3&uin={$v['content']}&site=qq&menu=yes";
			}
			if($v['ctype'] == 3 || $v['ctype'] == 4){
				$url = "tel:{$v['content']}";
			}
			$html .= '<a href="'.$url.'">
						<div class="item flex">
							<img src="'.tomedia($v['thumb']).'">
							<div class="right flex">
								<div class="kefuname">'.$v['name'].'</div>
								<div class="fname text-r">'.$v['typename'].'</div>
								<div class="iconfont text-r">&#xe6c2;</div>
							</div>
						</div>
					</a>';
		}
	}
}else{
	if(!empty($cservicegroup)){
		foreach($cservicegroup as $k=>$v){
			$html .= '<div class="item">
						<a href="'.$this->createMobileUrl('groupchat',array('id'=>$v['id'])).'">
							<img src="'.tomedia($v['thumb']).'">
							<div class="text">
								<div class="name textellipsis1">'.$v['name'].'</div>
								<div class="zu">'.$v['typename'].'</div>
							</div>
						</a>
					</div>';
		}
	}

	
	if(!empty($cservice)){
		foreach($cservice as $k=>$v){
			if($v['ctype'] == 1){
				$url = $this->createMobileUrl('chat',array('toopenid'=>$v['content']));
			}
			if($v['ctype'] == 2){
				$url = "http://wpa.qq.com/msgrd?v=3&uin={$v['content']}&site=qq&menu=yes";
			}
			if($v['ctype'] == 3 || $v['ctype'] == 4){
				$url = "tel:{$v['content']}";
			}
			$html .= '<div class="item"><a href="'.$url.'">
						<img src="'.tomedia($v['thumb']).'">
						<div class="text">
							<div class="name textellipsis1">'.$v['name'].'</div>
							<div class="zu">'.$v['typename'].'</div>
						</div>
					</a></div>';
		}
	}
	$html .= '<div style="flex:1;"></div>';
}

$resArr['error'] = 0;
$resArr['html'] = $html;
echo json_encode($resArr);
exit;
?>