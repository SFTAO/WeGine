<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('setting');

$_W['page']['title'] = '用户登录/注册设置 - 注册设置';

$copyright = $_W['setting']['copyright'];
if (checksubmit('submit')) {
	setting_save(array('open' => intval($_GPC['open']), 'verify' => intval($_GPC['verify']), 'code' => intval($_GPC['code']), 'groupid' => intval($_GPC['groupid']), 'safe' => intval($_GPC['safe'])), 'register');
	cache_delete(cache_system_key('defaultgroupid', array('uniacid' => $_W['uniacid'])));
	$copyright['mobile_status'] = intval($_GPC['mobile_status']);
	setting_save($copyright, 'copyright');
	itoast('更新设置成功！', url('user/registerset'), 'success');
}
$settings = $_W['setting']['register'];
$settings['mobile_status'] = $copyright['mobile_status'];
$groups = user_group();

template('user/registerset');