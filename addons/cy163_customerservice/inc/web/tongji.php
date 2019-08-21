<?php
global $_W, $_GPC;
$cservicelist = pdo_fetchall("SELECT content,name,thumb FROM ".tablename(BEST_CSERVICE)." WHERE weid = {$_W['uniacid']} AND ctype = 1 ORDER BY displayorder ASC");
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if($operation == 'display'){
	$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
	
	$beginThisweek = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('y'));  
    $endThisweek = TIMESTAMP;
	
	$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
	$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
	
	foreach($cservicelist as $k=>$v){
		$toadyjd = pdo_fetchall("SELECT id FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time > {$beginToday} AND time < {$endToday} AND openid = '{$v['content']}' GROUP BY fkid");
		$cservicelist[$k]['todayjdnum'] = count($toadyjd);
		
		$weekjd = pdo_fetchall("SELECT id FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time > {$beginThisweek} AND time < {$endThisweek} AND openid = '{$v['content']}' GROUP BY fkid");
		$cservicelist[$k]['weekjdnum'] = count($weekjd);
		
		$monthjd = pdo_fetchall("SELECT id FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time > {$beginThismonth} AND time < {$endThismonth} AND openid = '{$v['content']}' GROUP BY fkid");
		$cservicelist[$k]['monthjdnum'] = count($monthjd);
		
		
		$toadyhf = pdo_fetchall("SELECT id FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time > {$beginToday} AND time < {$endToday} AND openid = '{$v['content']}'");
		$cservicelist[$k]['todayhfnum'] = count($toadyhf);
		
		$weekhf = pdo_fetchall("SELECT id FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time > {$beginThisweek} AND time < {$endThisweek} AND openid = '{$v['content']}'");
		$cservicelist[$k]['weekhfnum'] = count($weekhf);
		
		$monthhf = pdo_fetchall("SELECT id FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time > {$beginThismonth} AND time < {$endThismonth} AND openid = '{$v['content']}'");
		$cservicelist[$k]['monthhfnum'] = count($monthhf);
	}
}
include $this->template('web/tongji');
?>