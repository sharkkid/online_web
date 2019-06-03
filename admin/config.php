<?php
session_start();
require_once dirname(__FILE__) . './../_setting.php';

if (!isset($_SESSION['user']) || $_SESSION['key'] != md5($_SESSION['user']['jsuser_account'] . get_ip() . 'online_web')){
	if (!preg_match("/sys_login.php/", $_SERVER['SCRIPT_NAME'])) {
		$_SESSION['LOGIN_REDIRECT'] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location: http://{$_SERVER['HTTP_HOST']}/online_web/admin/sys/sys_login.php");
		exit;
	}
}

function record_online_time() {
	$now = time();
	$end = $now+180;
	$jsuser_sn = $_SESSION['user']['jsuser_sn'];
	if(!empty($jsuser_sn)) {
		$conn = getDB();
		try {
			$conn->autocommit(false);
		
			$sql = "UPDATE js_online SET jsol_end='{$end}', jsol_count={$end}-jsol_start WHERE jsuser_sn='{$jsuser_sn}' and jsol_start<='{$now}' and jsol_end>='{$now}'";
			if(!$conn->query($sql))
				throw new Exception('error');
			$rows = mysqli_affected_rows($conn);
			if($rows==0) {
				$count = $end - $now;
				$sql = "INSERT INTO js_online (jsuser_sn, jsol_start, jsol_end, jsol_count) VALUES ('{$jsuser_sn}', '{$now}', '{$end}', '{$count}')";
				if(!$conn->query($sql))
				throw new Exception('error');
			}
			$conn->commit();
		} catch (Exception $e) {
			$conn->rollback();
		}
		$conn->close();
	}
}
record_online_time();

function returnChkText($before, $after, $colValue) {
	$chk = ($before != $after)?("$colValue"."由 $before 修改為 $after, "):("");
	return $chk;
}

$DEVICE_SYSTEM = array(
		1=>"Power",
		2=>"Water",
		3=>"Bulk Gas",
		4=>"Specialty Gas",
		5=>"Chemical",
		6=>"Exhaust",
		7=>"Drain",
		8=>"Vacuum",
		9=>"Pumping Line",
		10=>"防滴蓋板",
		11=>"Seismic",
		12=>"Foundation"
);


$MAP_AREA_NAME = array(
		"6a2f"=>"6A 2F",
		"6a3f"=>"6A 3F",
		"6b2f"=>"6B 2F",
		"6b3f"=>"6B 3F",
		"6c1f"=>"6C 1F",
		"6c2f"=>"6C 2F",
		"6c3f"=>"6C 3F"
);

$LOCATION_DB_AREA_MAPPING = array(
		"12"=>"6a2f",
		"13"=>"6a3f",
		"22"=>"6b2f",
		"23"=>"6b3f",
		"31"=>"6c1f",
		"32"=>"6c2f",
		"33"=>"6c3f"
);

$VERSION_NUMBER_MAPPING = array(
        "A"=>1,  
        "B"=>2,  
        "C"=>3,  
        "D"=>4,  
        "E"=>5,  
        "F"=>6,  
        "G"=>7,  
        "H"=>8,  
        "I"=>9,  
        "J"=>10,  
        "K"=>11,  
        "L"=>12,  
        "M"=>13,  
        "N"=>14,  
        "O"=>15,  
        "P"=>16,  
        "Q"=>17,  
        "R"=>18,  
        "S"=>19,  
        "T"=>20,  
        "U"=>21,  
        "V"=>22,  
        "W"=>23,  
        "X"=>24, 
        "Y"=>25,
        "Z"=>26  
); 

// 權限
// $admin_pass_page = array('sys_user.php', 'sys_history.php', 'sys_history_online.php', 'sys_history_edit.php','plant_user.php');

// if($_SESSION['user']['jsuser_admin_permit']!=1) {
// 	if(in_array(basename($_SERVER['SCRIPT_NAME']), $admin_pass_page)) {
// 		die('no permission');
// 	}
// }
?>
