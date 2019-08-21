<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');
load()->model('setting');
load()->model('utility');
$dos = array('find_password', 'valid_mobile', 'valid_code', 'set_password', 'success');
$do = in_array($do, $dos) ? $do : 'find_password';

$setting_sms_sign = setting_load('site_sms_sign');
$find_password_sign = !empty($setting_sms_sign['site_sms_sign']['find_password']) ? $setting_sms_sign['site_sms_sign']['find_password'] : '';
$mobile = safe_gpc_string($_GPC['receiver']);
if (in_array($do, array('valid_code', 'set_password'))) {
	$check_res = user_check_mobile($mobile);
	if (is_error($check_res)) {
		iajax($check_res['errno'], $check_res['message']);
	}
}

if ($do == 'valid_mobile') {
	$check_res = user_check_mobile($mobile);
	iajax($check_res['errno'], $check_res['message']);
}

if ($do == 'valid_code') {
	if ($_W['isajax'] && $_W['ispost']) {
		$code = trim($_GPC['code']);

		if (empty($code)) {
			iajax(-1, '短信验证码不能为空');
		}

		$verify_res = utility_smscode_verify(0, $mobile, $code);
		if (is_error($verify_res)) {
			iajax($verify_res['errno'], $verify_res['message']);
		}
		iajax(0, '');
	} else {
		iajax(-1, '非法请求');
	}
}

if ($do == 'set_password') {
	if ($_W['isajax'] && $_W['ispost']) {
		$password = $_GPC['password'];
		$repassword = $_GPC['repassword'];
		if (empty($password) || empty($repassword)) {
			iajax(-1, '密码不能为空');
		}

		if ($password != $repassword) {
			iajax(-1, '两次密码不一致');
		}

		$user_info = user_single($find_mobile['uid']);
		$password = user_hash($password, $user_info['salt']);
		if ($password == $user_info['password']) {
			iajax(-2, '不能使用最近使用的密码');
		}
		$result = pdo_update('users', array('password' => $password), array('uid' => $user_info['uid']));
		if (empty($result)) {
			iajax(0, '设置密码成功');
		}
		iajax(0);
	}
}
template('user/find-password');