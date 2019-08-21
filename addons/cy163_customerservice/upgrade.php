<?php
//升级数据表
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `endtime` int(11) NOT NULL,
  `isdadi` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_adv','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_adv','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD   `weid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('messikefu_adv','advname')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD   `advname` varchar(50) DEFAULT ''");}
if(!pdo_fieldexists('messikefu_adv','link')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD   `link` varchar(255) DEFAULT ''");}
if(!pdo_fieldexists('messikefu_adv','thumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD   `thumb` varchar(255) DEFAULT ''");}
if(!pdo_fieldexists('messikefu_adv','displayorder')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD   `displayorder` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('messikefu_adv','endtime')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD   `endtime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_adv','isdadi')) {pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD   `isdadi` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_biaoqian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuopenid` varchar(200) NOT NULL,
  `fensiopenid` varchar(200) NOT NULL,
  `name` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `telphone` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_biaoqian','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_biaoqian','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_biaoqian','kefuopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD   `kefuopenid` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_biaoqian','fensiopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD   `fensiopenid` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_biaoqian','name')) {pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD   `name` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_biaoqian','realname')) {pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD   `realname` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_biaoqian','telphone')) {pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD   `telphone` varchar(50) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `toopenid` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `hasread` tinyint(1) NOT NULL,
  `fkid` int(11) NOT NULL,
  `yuyintime` smallint(6) NOT NULL,
  `hasyuyindu` tinyint(1) NOT NULL,
  `isjqr` tinyint(1) NOT NULL,
  `fansdel` tinyint(1) NOT NULL,
  `kefudel` tinyint(1) NOT NULL,
  `isck` tinyint(1) NOT NULL,
  `mp3du` tinyint(1) NOT NULL,
  `istuwen` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_chat','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_chat','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','openid')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `openid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','toopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `toopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `content` text NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `time` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','nickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `nickname` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','avatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `avatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','type')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `type` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','hasread')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `hasread` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','fkid')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `fkid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','yuyintime')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `yuyintime` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','hasyuyindu')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `hasyuyindu` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','isjqr')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `isjqr` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','fansdel')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `fansdel` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','kefudel')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `kefudel` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','isck')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `isck` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','mp3du')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `mp3du` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_chat','istuwen')) {pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD   `istuwen` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_cservice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ctype` tinyint(1) NOT NULL,
  `content` varchar(100) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `starthour` smallint(6) NOT NULL,
  `endhour` smallint(6) NOT NULL,
  `autoreply` varchar(200) NOT NULL,
  `isonline` tinyint(1) NOT NULL,
  `groupid` int(11) NOT NULL,
  `fansauto` text NOT NULL,
  `kefuauto` text NOT NULL,
  `isautosub` tinyint(1) NOT NULL,
  `iskefuqrcode` tinyint(1) NOT NULL,
  `kefuqrcode` varchar(200) NOT NULL,
  `ishow` tinyint(1) NOT NULL,
  `notonline` varchar(255) NOT NULL,
  `lingjie` tinyint(1) NOT NULL,
  `typename` varchar(50) NOT NULL,
  `isgly` tinyint(1) NOT NULL,
  `iszx` tinyint(1) NOT NULL,
  `isrealzx` tinyint(1) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `djkey` varchar(30) NOT NULL,
  `isxingqi` tinyint(1) NOT NULL,
  `day1` tinyint(1) NOT NULL,
  `day2` tinyint(1) NOT NULL,
  `day3` tinyint(1) NOT NULL,
  `day4` tinyint(1) NOT NULL,
  `day5` tinyint(1) NOT NULL,
  `day6` tinyint(1) NOT NULL,
  `day7` tinyint(1) NOT NULL,
  `beibang` tinyint(1) NOT NULL,
  `bdchat` tinyint(1) NOT NULL,
  `cangzh` tinyint(1) NOT NULL,
  `gzhqzval` int(11) NOT NULL,
  `gzhqzval2` int(11) NOT NULL,
  `nowfkid` int(11) NOT NULL,
  `nowjdnum` int(11) NOT NULL,
  `ispczx` tinyint(1) NOT NULL,
  `autoreplyimg` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_cservice','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_cservice','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','name')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `name` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','ctype')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `ctype` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `content` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','thumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `thumb` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','displayorder')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `displayorder` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','starthour')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `starthour` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','endhour')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `endhour` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','autoreply')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `autoreply` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','isonline')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `isonline` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','groupid')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `groupid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','fansauto')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `fansauto` text NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','kefuauto')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `kefuauto` text NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','isautosub')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `isautosub` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','iskefuqrcode')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `iskefuqrcode` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','kefuqrcode')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `kefuqrcode` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','ishow')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `ishow` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','notonline')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `notonline` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','lingjie')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `lingjie` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','typename')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `typename` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','isgly')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `isgly` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','iszx')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `iszx` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','isrealzx')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `isrealzx` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','username')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `username` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','pwd')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `pwd` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','djkey')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `djkey` varchar(30) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','isxingqi')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `isxingqi` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','day1')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `day1` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','day2')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `day2` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','day3')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `day3` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','day4')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `day4` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','day5')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `day5` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','day6')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `day6` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','day7')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `day7` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','beibang')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `beibang` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','bdchat')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `bdchat` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','cangzh')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `cangzh` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','gzhqzval')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `gzhqzval` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','gzhqzval2')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `gzhqzval2` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','nowfkid')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `nowfkid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','nowjdnum')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `nowjdnum` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','ispczx')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `ispczx` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservice','autoreplyimg')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD   `autoreplyimg` varchar(200) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_cservicegroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `qrtext` varchar(50) NOT NULL,
  `qrbg` varchar(20) NOT NULL,
  `qrcolor` varchar(20) NOT NULL,
  `cangroup` tinyint(1) NOT NULL,
  `typename` varchar(50) NOT NULL,
  `ishow` tinyint(1) NOT NULL,
  `sanbs` varchar(50) NOT NULL,
  `sanremark` varchar(200) NOT NULL,
  `bsid` int(11) NOT NULL,
  `qrright` int(11) NOT NULL,
  `qrbottom` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `notext` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_cservicegroup','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_cservicegroup','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','name')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `name` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','thumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `thumb` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','displayorder')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `displayorder` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','qrtext')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `qrtext` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','qrbg')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `qrbg` varchar(20) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','qrcolor')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `qrcolor` varchar(20) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','cangroup')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `cangroup` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','typename')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `typename` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','ishow')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `ishow` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','sanbs')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `sanbs` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','sanremark')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `sanremark` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','bsid')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `bsid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','qrright')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `qrright` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','qrbottom')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `qrbottom` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','fid')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `fid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_cservicegroup','notext')) {pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD   `notext` varchar(100) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_fanskefu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `fansopenid` varchar(100) NOT NULL,
  `kefuopenid` varchar(100) NOT NULL,
  `fansavatar` varchar(200) NOT NULL,
  `kefuavatar` varchar(200) NOT NULL,
  `fansnickname` varchar(100) NOT NULL,
  `kefunickname` varchar(100) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `notread` int(11) NOT NULL,
  `lastcon` varchar(255) NOT NULL,
  `kefulasttime` int(11) NOT NULL,
  `kefulastcon` varchar(255) NOT NULL,
  `kefunotread` int(11) NOT NULL,
  `msgtype` smallint(6) NOT NULL,
  `kefumsgtype` smallint(6) NOT NULL,
  `guanlinum` int(11) NOT NULL,
  `ishei` tinyint(1) NOT NULL,
  `fansdel` tinyint(1) NOT NULL,
  `kefudel` tinyint(1) NOT NULL,
  `fszx` tinyint(1) NOT NULL,
  `kfzx` tinyint(1) NOT NULL,
  `isxcx` tinyint(1) NOT NULL,
  `bdopenid` varchar(100) NOT NULL,
  `fangke` text NOT NULL,
  `nowjd` tinyint(1) NOT NULL,
  `jdtime` int(11) NOT NULL,
  `wherefrom` tinyint(1) NOT NULL,
  `goodsmsg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_fanskefu','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_fanskefu','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','fansopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `fansopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefuopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefuopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','fansavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `fansavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefuavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefuavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','fansnickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `fansnickname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefunickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefunickname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','lasttime')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `lasttime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','notread')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `notread` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','lastcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `lastcon` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefulasttime')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefulasttime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefulastcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefulastcon` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefunotread')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefunotread` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','msgtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `msgtype` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefumsgtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefumsgtype` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','guanlinum')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `guanlinum` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','ishei')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `ishei` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','fansdel')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `fansdel` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kefudel')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kefudel` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','fszx')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `fszx` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','kfzx')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `kfzx` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','isxcx')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `isxcx` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','bdopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `bdopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','fangke')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `fangke` text NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','nowjd')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `nowjd` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','jdtime')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `jdtime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','wherefrom')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `wherefrom` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_fanskefu','goodsmsg')) {pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD   `goodsmsg` text NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_fromck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `msgid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `msgid` (`msgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_fromck','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_fromck')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_fromck','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_fromck')." ADD   `weid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('messikefu_fromck','msgid')) {pdo_query("ALTER TABLE ".tablename('messikefu_fromck')." ADD   `msgid` varchar(100) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_fromck','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_fromck')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupname` varchar(100) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `autoreply` varchar(200) NOT NULL,
  `quickcon` text NOT NULL,
  `isautosub` tinyint(1) NOT NULL,
  `cservicegroupid` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `maxnum` int(11) NOT NULL,
  `isguanzhu` tinyint(1) NOT NULL,
  `jinyan` tinyint(1) NOT NULL,
  `isshenhe` tinyint(1) NOT NULL,
  `autotx` tinyint(1) NOT NULL,
  `isfs` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_group','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_group','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','groupname')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `groupname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','thumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `thumb` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','admin')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `admin` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `time` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','autoreply')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `autoreply` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','quickcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `quickcon` text NOT NULL");}
if(!pdo_fieldexists('messikefu_group','isautosub')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `isautosub` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','cservicegroupid')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `cservicegroupid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','lasttime')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `lasttime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','maxnum')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `maxnum` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','isguanzhu')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `isguanzhu` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','jinyan')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `jinyan` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','isshenhe')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `isshenhe` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','autotx')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `autotx` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_group','isfs')) {pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD   `isfs` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_groupchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(100) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `weid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `yuyintime` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_groupchat','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_groupchat','nickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `nickname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','avatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `avatar` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','groupid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `groupid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','openid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `openid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `content` text NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `time` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','type')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `type` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupchat','yuyintime')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD   `yuyintime` smallint(6) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_groupmember` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `intime` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `notread` int(11) NOT NULL,
  `txkaiguan` tinyint(1) NOT NULL,
  `isdel` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_groupmember','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_groupmember','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','groupid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `groupid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','openid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `openid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','nickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `nickname` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','avatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `avatar` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','type')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `type` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','status')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `status` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','intime')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `intime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','lasttime')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `lasttime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','notread')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `notread` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','txkaiguan')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `txkaiguan` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupmember','isdel')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD   `isdel` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_groupvoicedu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `gchatid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `content` varchar(200) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_groupvoicedu','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupvoicedu')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_groupvoicedu','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupvoicedu')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupvoicedu','groupid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupvoicedu')." ADD   `groupid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupvoicedu','gchatid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupvoicedu')." ADD   `gchatid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupvoicedu','openid')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupvoicedu')." ADD   `openid` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupvoicedu','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupvoicedu')." ADD   `content` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_groupvoicedu','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_groupvoicedu')." ADD   `time` int(11) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_kefuandcjwt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuid` int(11) NOT NULL,
  `wtid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_kefuandcjwt','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandcjwt')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_kefuandcjwt','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandcjwt')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_kefuandcjwt','kefuid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandcjwt')." ADD   `kefuid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_kefuandcjwt','wtid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandcjwt')." ADD   `wtid` int(11) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_kefuandgroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_kefuandgroup','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_kefuandgroup','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_kefuandgroup','kefuid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD   `kefuid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_kefuandgroup','groupid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD   `groupid` int(11) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_kuaijie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `kjtype` tinyint(1) DEFAULT '0',
  `con` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `kfid` int(11) NOT NULL,
  `allcon` text NOT NULL,
  `displayorder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_kuaijie','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_kuaijie','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD   `weid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('messikefu_kuaijie','kjtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD   `kjtype` tinyint(1) DEFAULT '0'");}
if(!pdo_fieldexists('messikefu_kuaijie','con')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD   `con` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_kuaijie','thumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD   `thumb` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_kuaijie','kfid')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD   `kfid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_kuaijie','allcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD   `allcon` text NOT NULL");}
if(!pdo_fieldexists('messikefu_kuaijie','displayorder')) {pdo_query("ALTER TABLE ".tablename('messikefu_kuaijie')." ADD   `displayorder` int(11) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_pingjia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuopenid` varchar(200) NOT NULL,
  `fensiopenid` varchar(200) NOT NULL,
  `pingtype` tinyint(1) NOT NULL,
  `content` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_pingjia','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_pingjia','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_pingjia','kefuopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD   `kefuopenid` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_pingjia','fensiopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD   `fensiopenid` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_pingjia','pingtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD   `pingtype` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_pingjia','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD   `content` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_pingjia','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD   `time` int(11) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_sanchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `sanfkid` int(11) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `yuyintime` smallint(6) NOT NULL,
  `hasyuyindu` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_sanchat','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_sanchat','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanchat','sanfkid')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `sanfkid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanchat','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `content` text NOT NULL");}
if(!pdo_fieldexists('messikefu_sanchat','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `time` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanchat','type')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `type` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanchat','openid')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `openid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanchat','yuyintime')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `yuyintime` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanchat','hasyuyindu')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD   `hasyuyindu` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_sanfanskefu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `fansopenid` varchar(100) NOT NULL,
  `kefuopenid` varchar(100) NOT NULL,
  `fansavatar` varchar(200) NOT NULL,
  `kefuavatar` varchar(200) NOT NULL,
  `fansnickname` varchar(100) NOT NULL,
  `kefunickname` varchar(100) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `notread` int(11) NOT NULL,
  `lastcon` varchar(255) NOT NULL,
  `msgtype` smallint(6) NOT NULL,
  `seetime` int(11) NOT NULL,
  `qudao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_sanfanskefu','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_sanfanskefu','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','fansopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `fansopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','kefuopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `kefuopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','fansavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `fansavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','kefuavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `kefuavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','fansnickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `fansnickname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','kefunickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `kefunickname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','lasttime')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `lasttime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','notread')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `notread` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','lastcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `lastcon` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','msgtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `msgtype` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','seetime')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `seetime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_sanfanskefu','qudao')) {pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD   `qudao` varchar(50) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_wenzhang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `des` text NOT NULL,
  `views` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `paixu` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_wenzhang','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_wenzhang')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_wenzhang','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_wenzhang')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_wenzhang','title')) {pdo_query("ALTER TABLE ".tablename('messikefu_wenzhang')." ADD   `title` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_wenzhang','des')) {pdo_query("ALTER TABLE ".tablename('messikefu_wenzhang')." ADD   `des` text NOT NULL");}
if(!pdo_fieldexists('messikefu_wenzhang','views')) {pdo_query("ALTER TABLE ".tablename('messikefu_wenzhang')." ADD   `views` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_wenzhang','addtime')) {pdo_query("ALTER TABLE ".tablename('messikefu_wenzhang')." ADD   `addtime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_wenzhang','paixu')) {pdo_query("ALTER TABLE ".tablename('messikefu_wenzhang')." ADD   `paixu` int(11) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_xcx` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `gh_id` varchar(30) DEFAULT NULL,
  `appid` varchar(30) DEFAULT NULL,
  `secret` varchar(50) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `aeskey` varchar(50) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `access_token` text NOT NULL,
  `guoqitime` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `admins` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_xcx','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_xcx','uniacid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','name')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `name` varchar(30) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','gh_id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `gh_id` varchar(30) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','appid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `appid` varchar(30) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','secret')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `secret` varchar(50) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','token')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `token` varchar(50) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','aeskey')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `aeskey` varchar(50) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','url')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `url` varchar(200) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcx','access_token')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `access_token` text NOT NULL");}
if(!pdo_fieldexists('messikefu_xcx','guoqitime')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `guoqitime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcx','status')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `status` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcx','admins')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcx')." ADD   `admins` varchar(100) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_xcxauto` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `kfid` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `msgtype` varchar(30) NOT NULL,
  `pagepath` varchar(100) NOT NULL,
  `pagethumb` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `thumb_url` varchar(200) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `paixu` int(11) NOT NULL,
  `iszdhf` tinyint(1) NOT NULL,
  `zdhftitle` varchar(100) NOT NULL,
  `zdhftype` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_xcxauto','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_xcxauto','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `weid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','kfid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `kfid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','name')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `name` varchar(30) DEFAULT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','msgtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `msgtype` varchar(30) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','pagepath')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `pagepath` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','pagethumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `pagethumb` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','title')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `title` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','description')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `description` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','url')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `url` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','thumb_url')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `thumb_url` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','thumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `thumb` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','paixu')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `paixu` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','iszdhf')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `iszdhf` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','zdhftitle')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `zdhftitle` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxauto','zdhftype')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxauto')." ADD   `zdhftype` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_xcxchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `toopenid` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `msgtype` varchar(50) NOT NULL,
  `fkid` int(11) NOT NULL,
  `gh_id` varchar(50) NOT NULL,
  `msgid` varchar(50) NOT NULL,
  `mediaId` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumb_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_xcxchat','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_xcxchat','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','openid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `openid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','toopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `toopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `content` text NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `time` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','msgtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `msgtype` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','fkid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `fkid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','gh_id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `gh_id` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','msgid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `msgid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','mediaId')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `mediaId` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','title')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `title` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','description')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `description` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','url')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `url` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxchat','thumb_url')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxchat')." ADD   `thumb_url` varchar(255) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_xcxcservice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `content` varchar(100) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `kefuauto` text NOT NULL,
  `isautosub` tinyint(1) NOT NULL,
  `xcxid` int(11) NOT NULL,
  `jhtext` varchar(50) NOT NULL,
  `jhname` varchar(100) NOT NULL,
  `autoreply` varchar(100) NOT NULL,
  `gzhqzval` int(11) NOT NULL,
  `lingjie` tinyint(1) NOT NULL,
  `starthour` smallint(6) NOT NULL,
  `endhour` smallint(6) NOT NULL,
  `isxingqi` tinyint(1) NOT NULL,
  `day1` tinyint(1) NOT NULL,
  `day2` tinyint(1) NOT NULL,
  `day3` tinyint(1) NOT NULL,
  `day4` tinyint(1) NOT NULL,
  `day5` tinyint(1) NOT NULL,
  `day6` tinyint(1) NOT NULL,
  `day7` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_xcxcservice','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_xcxcservice','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','name')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `name` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `content` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','thumb')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `thumb` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','displayorder')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `displayorder` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','kefuauto')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `kefuauto` text NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','isautosub')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `isautosub` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','xcxid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `xcxid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','jhtext')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `jhtext` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','jhname')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `jhname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','autoreply')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `autoreply` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','gzhqzval')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `gzhqzval` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','lingjie')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `lingjie` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','starthour')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `starthour` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','endhour')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `endhour` smallint(6) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','isxingqi')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `isxingqi` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','day1')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `day1` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','day2')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `day2` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','day3')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `day3` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','day4')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `day4` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','day5')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `day5` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','day6')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `day6` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxcservice','day7')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxcservice')." ADD   `day7` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_xcxfanskefu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `fansopenid` varchar(100) NOT NULL,
  `kefuopenid` varchar(100) NOT NULL,
  `fansavatar` varchar(200) NOT NULL,
  `kefuavatar` varchar(200) NOT NULL,
  `fansnickname` varchar(100) NOT NULL,
  `kefunickname` varchar(100) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `notread` int(11) NOT NULL,
  `lastcon` varchar(255) NOT NULL,
  `msgtype` varchar(30) NOT NULL,
  `gh_id` varchar(50) NOT NULL,
  `createtime` int(11) NOT NULL,
  `sessionfrom` varchar(100) NOT NULL,
  `huifunum` int(11) NOT NULL,
  `nowkefu` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_xcxfanskefu','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','fansopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `fansopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','kefuopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `kefuopenid` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','fansavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `fansavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','kefuavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `kefuavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','fansnickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `fansnickname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','kefunickname')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `kefunickname` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','lasttime')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `lasttime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','notread')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `notread` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','lastcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `lastcon` varchar(255) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','msgtype')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `msgtype` varchar(30) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','gh_id')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `gh_id` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','createtime')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `createtime` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','sessionfrom')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `sessionfrom` varchar(100) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','huifunum')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `huifunum` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_xcxfanskefu','nowkefu')) {pdo_query("ALTER TABLE ".tablename('messikefu_xcxfanskefu')." ADD   `nowkefu` tinyint(1) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_zdhf` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `type` tinyint(1) NOT NULL,
  `paixu` int(11) NOT NULL,
  `hftype` tinyint(1) NOT NULL,
  `imgcon` varchar(200) NOT NULL,
  `allcon` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_zdhf','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_zdhf','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_zdhf','title')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `title` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_zdhf','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `content` text NOT NULL");}
if(!pdo_fieldexists('messikefu_zdhf','type')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `type` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_zdhf','paixu')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `paixu` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_zdhf','hftype')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `hftype` tinyint(1) NOT NULL");}
if(!pdo_fieldexists('messikefu_zdhf','imgcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `imgcon` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_zdhf','allcon')) {pdo_query("ALTER TABLE ".tablename('messikefu_zdhf')." ADD   `allcon` text NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_zhuizong` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuopenid` varchar(200) NOT NULL,
  `kefuname` varchar(50) NOT NULL,
  `kefuavatar` varchar(200) NOT NULL,
  `fansopenid` varchar(200) NOT NULL,
  `fansavatar` varchar(200) NOT NULL,
  `fansname` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL,
  `zztype` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

");

if(!pdo_fieldexists('messikefu_zhuizong','id')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD 
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('messikefu_zhuizong','weid')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `weid` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','kefuopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `kefuopenid` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','kefuname')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `kefuname` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','kefuavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `kefuavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','fansopenid')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `fansopenid` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','fansavatar')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `fansavatar` varchar(200) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','fansname')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `fansname` varchar(50) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','content')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `content` text NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','time')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `time` int(11) NOT NULL");}
if(!pdo_fieldexists('messikefu_zhuizong','zztype')) {pdo_query("ALTER TABLE ".tablename('messikefu_zhuizong')." ADD   `zztype` tinyint(1) NOT NULL");}
