<?php
/**
* 【林心网络】https://alym.taobao.com/
 */
defined('IN_IA') or exit('Access Denied');
if ($do == 'online') {
	header('Location: //we7.jcxbhm.com/app/api.php?referrer='.$_W['setting']['site']['key']);
	exit;
} elseif ($do == 'offline') {
	header('Location: //we7.jcxbhm.com/app/api.php?referrer='.$_W['setting']['site']['key'].'&standalone=1');
	exit;
} else {
}
template('cloud/device');
