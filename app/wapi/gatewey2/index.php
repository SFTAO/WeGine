<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
	<!--ie调用最新版本-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--360极速模式-->
	<meta name="renderer" content="webkit">
	<title>授权管理中心</title>
	<link rel="stylesheet" type="text/css" href="../imges/bootstrap.css">
	<!--css3动画-->
	<link rel="stylesheet" type="text/css" href="../imges/animate.min.css">
	<!-- 自定义图标-->	
	<link rel="stylesheet" type="text/css" href="../imges/iconfont.css">
	<!-- 公共css-->
	<link rel="stylesheet" type="text/css" href="../imges/style.css">
	<!--加载条css微擎原有-->
	<link rel="stylesheet" type="text/css" href="../imges/pace-theme-minimal.css">
	<!-- 商城css-->
	<link rel="stylesheet" type="text/css" href="../imges/site.css">
	
	<script type="text/javascript" src="./imges/jquery-1.11.1.min.js"></script>
		<!--加载条js微擎原有-->
	<script type="text/javascript" src="./imges/pace.min.js"></script>
	<script type="text/javascript" src="./imges/bootstrap.min.js"></script>
	<script type="text/javascript" src="./imges/util.js"></script>
	<script type="text/javascript" src="./imges/require.js"></script>
	<!--<script type="text/javascript" src="/public/static/we8auth/js/config.js"></script>-->
</head>
<body class=" pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
<link rel="stylesheet" type="text/css" href="../imges/we8.css">
<style type="text/css">
	body {
		background-color: #fff !important;
		padding: 0;
		min-width: 969px !important;
	}
	.pace {
		display: none;
	}
</style>
<div class="website-register">
	<div class="panel we7-panel">
		<div class="panel-heading row">
			<div class="col-sm-4 text-center">
				<span class="color-default order">1</span>，获取授权码
			</div>
			<div class="col-sm-4 text-center">
				<span class="color-default order">2</span>，输入正确授权码
			</div>
			<div class="col-sm-4 text-center">
				<span class="color-default order">3</span>，授权成功
			</div>
		</div>
		<div class="panel-body">
		<form action="" method="post" class="form we7-form" onSubmit="return check_()">
			<div class="we7-form">
				<div class="col-sm-6 we7-padding">
					<div class="color-gray we7-margin-bottom">站点信息</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">站点ID</label>
					  <div class="form-controls col-sm-9">
						  <input type="text" readonly="readonly" name="key_t" value="888888" class="form-control">
					  </div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">网站IP</label>
					  <div class="form-controls col-sm-9 ">
						  <input type="text" readonly="readonly" name="ip_t" value="127.0.0.1" class="form-control">
					  </div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">网站URL</label>
					  <div class="form-controls col-sm-9 ">
						  <input type="text" readonly="readonly" name="url_t" value="https://shop504817250.taobao.com"  class="form-control">
					  </div>
					</div>
					<div class="color-gray we7-margin-bottom">授权后后可用功能 </div>
					<div class="form-group">
						<div class="form-controls">
							<input id="check-1" type="checkbox" name="check-1" checked="checked">
							<label for="check-1">更新系统，及时修补BUG和更多的新功能</label>
						</div>
					</div>
					<div class="form-group">
						<div class="form-controls">
							<input id="check-2" type="checkbox" name="check-1" checked="checked">
							<label for="check-2">升级系统，商业版为您带来更好的体验</label>
						</div>
					</div>
					<div class="form-group">
						<div class="form-controls">
							<input id="check-3" type="checkbox" name="check-1" checked="checked">
							<label for="check-3">后台一键更新文件与数据库</label>
						</div>
					</div>
				</div>
				<div class="col-sm-6 we7-padding">
						<div class="color-gray we7-margin-bottom-sm">用户信息</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">服务到期时间</label>
							<div class="form-controls col-sm-9">
								<input type="text" readonly="readonly" name="expire_time" value="已获得授权" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">联系人</label>
						  <div class="form-controls col-sm-9 ">
							  <input name="contact" type="text" class="form-control"  value="淘宝大众乐科技"  id="contact" readonly="readonly" >
							<div class="help-block"><p style="color:#a94442">注意：请如实填写，不可修改，如为虚假信息则导致授权无效！</p></div>
						  </div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">授权码</label>
						  <div class="form-controls col-sm-9 ">
							  <input type="text" id="we8code" name="we8code" value="8888888888" class="form-control" readonly="readonly" >
							<div class="help-block"><p style="color:#a94442">注意：请妥善备份保管授权码！丢失或遗忘概不负责！</p></div>
						  </div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">充值卡</label>
						  <div class="form-controls col-sm-9 ">
							  <input type="text" id="we8card" name="we8card" class="form-control" readonly="readonly" >
							<div class="help-block"><p style="color:#a94442">注意：为避免无法正常使用授权更新功能，请及时充值</p></div>
						  </div>
						</div>						
						<div class="col-sm-12 text-center">
							<input type="submit" name="submit" value="提交授权" class="btn btn-primary we7-padding-horizontal">
						</div>
					<script type="text/javascript">
					function check_(){
							var msg = '';
							var contact = $('#contact').val();
							if (contact == '' || contact == null) {
								msg += '联系QQ不能为空';
							};
							var authcode = $('#we8code').val();
							if (authcode == ''  || authcode == null) {
								msg += '授权码不能为空';
							};
							if (msg != '') {
								util.message(msg);
								return false;
							};
					}
				</script>
				</div>
				
			</div></form>
		</div>
	</div>
</div>
</body></html>