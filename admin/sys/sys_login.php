<?php
include_once("./func.php");
$op=GetParam('op');
if(!empty($op)) {
	$ret_code = 1;
	$ret_msg = '';
	$ret_data = array();
	switch ($op) {
		case 'login':
		$account=GetParam('account');
		$password=GetParam('password');

		$user = getUserByAccount($account);
		if ($user['jsuser_password']==$password && $user['jsuser_status']==1) {
			session_start();
			$ip = get_ip();
			$_SESSION['user'] = $user;
			$_SESSION['key'] = md5($_SESSION['user']['jsuser_account'] . $ip . 'online_web');
			addHistory(0, '後臺登入', 4, '登入帳號: '. $_SESSION['user']['jsuser_account'] . ', IP: ' . $ip);

			if(isset($_SESSION['LOGIN_REDIRECT'])) {
				$ret_data['url'] = $_SESSION['LOGIN_REDIRECT'];
				unset($_SESSION['LOGIN_REDIRECT']);
			} else {
				if ($account=='ir_guest') {
					$ret_data['url'] = WT_SERVER.'/admin/ir/ir_cheduling_guest.php';
				} else if($_SESSION['user']['jsdp_sn']==101 && $_SESSION['user']['jsuser_super_admin_permit']!=1) {
					$ret_data['url'] = WT_SERVER.'/admin/ir/ir_summary.php';
				} else {
					$ret_data['url'] = "";
				}
			}

			$ret_data['result'] = true;
		} else {
			$ret_data['result'] = false;
		}

		break;
		case 'logout':
		session_start();
		session_destroy();
		header('Location: '.WT_SERVER.'/admin/sys/sys_login.php');
		exit;
		break;
		default:
		$ret_msg = 'error!';
		break;
	}
	
	echo enclode_ret_data($ret_code, $ret_msg, $ret_data);
	exit;
} else {
	session_start();
	if(isset($_SESSION['user']) && $_SESSION['key'] == md5($_SESSION['user']['jsuser_account'] . get_ip() . 'online_web')){
		header('Location: '.WT_SERVER.'/admin');
	} else {
		session_destroy();
		session_start();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="Dashboard">
	<meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<!-- <title><?php echo CN_NAME;?></title> -->
	<title>Online_Web</title>
	<style>
	body 
	{
		background-image:url(./../img/login-bg.png);
		background-color:#cccccc;
	}
	</style>

<!-- Favicons -->
<link href="./../img/favicon.png" rel="icon">
<link href="./../img/apple-touch-icon.png" rel="apple-touch-icon">

<!-- Bootstrap core CSS -->
<link href="./../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!--external css-->
<link href="./../../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
<!-- Custom styles for this template -->
<link href="./../../css/style.css" rel="stylesheet">
<link href="./../../css/style-responsive.css" rel="stylesheet">


<script type="text/javascript" src="./../../lib/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="./../../lib/jquery.tablesorter.js"></script>
<!-- js placed at the end of the document so the pages load faster -->
<script src="./../../lib/jquery/jquery.min.js"></script>
<script src="./../../lib/bootstrap/js/bootstrap.min.js"></script>
<!--BACKSTRETCH-->
<!--  You can use an image of whatever size. This script will stretch to fit in any screen size.
<script type="text/javascript" src="./../../lib/jquery.backstretch.min.js"></script> -->


<script type="text/javascript">
	$(document).ready(function() {
		$('#form').on('submit', function(e) {
			e.preventDefault();
			var param = $(this).serializeArray();
			$.ajax({
				url: './sys_login.php',
				type: 'post',
				dataType: 'json',
				data: param,
				beforeSend: function(msg) {
					$("#ajax_loading").show();
				},
				complete: function(XMLHttpRequest, textStatus) {
					$("#ajax_loading").hide();
				},
				success: function(ret) {
					if (ret.data.result) {
						if(ret.data.url != "") {
							location.replace(ret.data.url);
						} else {
							location.replace('<?php echo WT_SERVER;?>/admin/index/index.php<?php echo "?year=".date("Y")."&day=".date("Y-m-d");?>');
						}
					} else {
						alert('帳號密碼錯誤！')
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
		                	// console.log('ajax error');
		                    // console.log(xhr);
		                }
		            });
		});
	});
</script>
</head>
<body>
	<center>
		<div class="center"
		style="width: 650px; height: 400px; margin-top: 100px; background-image: url(./../img/winbond_login-bg.png); background-repeat: no-repeat; position: relative;">
		<div style="position: absolute; bottom: 30px; right: 350px;">
			<table>
				<tbody>
					<tr>
						<td style="background-color: white; padding: 20px; filter:alpha(opacity=90); -moz-opacity:0.9; opacity:0.9;" class="box_shadow ui-corner-all">
							<form id="form" method="post" action="usercheck.php" autocomplete="off" novalidate="novalidate">
								<input type="hidden" name="op" value="login">
								<table>
									<tbody>
										<tr>
											<th style="white-space: nowrap;">帳號</th>
											<td><input type="text" name="account" style="width: 150px;" autofocus="autofocus" tabindex="1" class="required"></td>
										</tr>
										<tr>
											<th style="white-space: nowrap;">密碼</th>
											<td><input type="password" name="password" style="width: 150px;" tabindex="2" class="required"></td>
										</tr>
										<tr>
											<td colspan="2" align="right"><input type="submit" value="登入" tabindex="3"></td>
										</tr>
									</tbody>
								</table>
							</form>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</center>
</body>
</html>