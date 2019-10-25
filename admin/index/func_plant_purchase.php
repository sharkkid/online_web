<?php
include_once(dirname(__FILE__).'/../config.php');
$onchba_cycle = 0;
//function--------------------------------------------------------------------------------------
function consolelog($php) {
	echo '<script>console.log('.$php.');</script>';
}

function getUser($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 order by onadd_add_date desc, onadd_sn desc limit $offset, $rows";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 and ( $where ) order by onadd_add_date desc, onadd_sn desc limit $offset, $rows";
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getUserQty($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from onliine_add_data where onadd_status>=0";
	else
		$sql="select count(*) from onliine_add_data where onadd_status>=0 and ( $where )";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data = $row['count(*)'];
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getUserBySn($onadd_sn) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_sn='{$onadd_sn}'";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
function getUserByAccount() {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_status>=0";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getDetails($onadd_growing) {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select SUM(onadd_quantity) from onliine_add_data where onadd_cur_size='$onadd_growing' GROUP BY onadd_growing";
	
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data += (int)$row['SUM(onadd_quantity)'];
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getSellQuantity($year) {
	$firday = strtotime($year."/01/1");
	$endday = strtotime($year."/12/31");
	$ret_data = array();
	$conn = getDB();
	
	$sql="SELECT FROM_UNIXTIME(onshda_mod_date,'%m') as months,sum(onshda_quantity) as quantity FROM `online_shipment_data` WHERE onshda_mod_date IN (SELECT onshda_mod_date FROM `online_shipment_data` WHERE onshda_mod_date >= {$firday} AND onshda_mod_date <= {$endday})  group by months order by months";
	// echo $sql;

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getEliminationQuantity($year) {
	$firday = strtotime($year."/01/1");
	$endday = strtotime($year."/12/31");
	$ret_data = array();
	$conn = getDB();
	
	$sql="SELECT FROM_UNIXTIME(onelda_mod_date,'%m') as months,sum(onelda_quantity) as quantity FROM `online_elimination_data` WHERE onelda_mod_date IN (SELECT onelda_mod_date FROM `online_elimination_data` WHERE onelda_mod_date >= {$firday} AND onelda_mod_date <= {$endday})  group by months order by months";
	// echo $sql;

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getQuantity_Day($day) {
	$conn = getDB();
	// $firday = strtotime(date("Y/m/d",time())." 00:00:00");
	// $endday = strtotime(date("Y/m/d",time())." 24:00:00");
	$ret_data = array();
	$firday = strtotime("-7 days",strtotime($day." 00:00:00"));
	$endday = strtotime("-7 days",strtotime($day." 24:00:00"));

	for($i=0;$i<=7;$i++){
		$firday = strtotime("+".$i." days",strtotime($day." 00:00:00"));
		$endday = strtotime("+".$i." days",strtotime($day." 24:00:00"));
		$sql="SELECT sum(onadd_quantity) as add_quantity FROM `onliine_add_data` WHERE onadd_mod_date Between {$firday} AND {$endday}";
		$sql2="SELECT sum(onelda_quantity) as elda_quantity FROM `online_elimination_data` WHERE onelda_mod_date Between {$firday} AND {$endday}";
		$sql3="SELECT sum(onshda_quantity) as ship_quantity FROM `online_shipment_data` WHERE onshda_mod_date Between {$firday} AND {$endday}";
	
		$qresult = $conn->query($sql);
		if ($qresult->num_rows > 0) {
			while($row = $qresult->fetch_assoc()) {
				if($row['add_quantity'] != ''){
					$ret_data[$i][0] = $row['add_quantity'];
				}
				else{
					$ret_data[$i][0] = '0';
				}				
				$ret_data[$i]['date1'] = date("Y-m-d",strtotime("-".$i." days",strtotime($day." 00:00:00")));
				$ret_data[$i]['date2'] = date("Ymd",strtotime("-".$i." days",strtotime($day." 00:00:00")));
			}
			$qresult->free();
		}

		$qresult = $conn->query($sql2);
		if ($qresult->num_rows > 0) {
			while($row = $qresult->fetch_assoc()) {
				if($row['elda_quantity'] != ''){
					$ret_data[$i][1] = $row['elda_quantity'];
				}
				else{
					$ret_data[$i][1] = '0';
				}
				$ret_data[$i]['date1'] = date("Y-m-d",strtotime("-".$i." days",strtotime($day." 00:00:00")));
				$ret_data[$i]['date2'] = date("Ymd",strtotime("-".$i." days",strtotime($day." 00:00:00")));
			}
			$qresult->free();
		}

		$qresult = $conn->query($sql3);
		if ($qresult->num_rows > 0) {
			while($row = $qresult->fetch_assoc()) {
				if($row['ship_quantity'] != ''){
					$ret_data[$i][2] = $row['ship_quantity'];
				}
				else{
					$ret_data[$i][2] = '0';
				}
				$ret_data[$i]['date1'] = date("Y-m-d",strtotime("-".$i." days",strtotime($day." 00:00:00")));
				$ret_data[$i]['date2'] = date("Ymd",strtotime("-".$i." days",strtotime($day." 00:00:00")));
			}
			$qresult->free();
		}
	}

	$conn->close();
	return $ret_data;
}

function getUsedQuantity() {
	$conn = getDB();
	
	$sql="SELECT sum(onadd_quantity) as add_quantity FROM `onliine_add_data`";//園區全部的下種數量
	$sql2="SELECT sum(onelda_quantity) as elda_quantity FROM `online_elimination_data`";//園區全部的汰除數量
	$sql3="SELECT sum(onshda_quantity) as ship_quantity FROM `online_shipment_data`";//園區全部的出貨數量
	// echo $sql;

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[0] = $row;
		}
		$qresult->free();
	}
	$qresult = $conn->query($sql2);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[1] = $row;
		}
		$qresult->free();
	}
	$qresult = $conn->query($sql3);
		if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[2] = $row;
		}
		$qresult->free();
	}

	$conn->close();
	return $ret_data;
}


function getSpace() {
	$ret_data = array();
	$conn = getDB();
	$sql="SELECT * FROM `online_space`";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getSettingBySn($onchba_size) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from online_change_basin where onchba_size='{$onchba_size}'";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getWorkListByMonth() {
	$list_setting1 = getSettingBySn('1.7');
	$list_setting2 = getSettingBySn('2.5');
	$list_setting3 = getSettingBySn('2.8');
	$list_setting4 = getSettingBySn('3.0');
	$list_setting5 = getSettingBySn('3.5');
	$list_setting6 = getSettingBySn('3.6');
	$list_setting7 = getSettingBySn('其他');
	$list_setting8 = getSettingBySn('瓶苗下種');
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1";
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			switch ($row['onadd_growing']) {
        		case 1:
        			$GLOBALS['onchba_cycle'] = $list_setting1['onchba_cycle'];
        			break;
        		case 2:
        			$GLOBALS['onchba_cycle'] = $list_setting2['onchba_cycle'];
        			break;
        		case 3:
        			$GLOBALS['onchba_cycle'] = $list_setting3['onchba_cycle'];
        			break;
        		case 4:
        			$GLOBALS['onchba_cycle'] = $list_setting4['onchba_cycle'];
        			break;
        		case 5:
        			$GLOBALS['onchba_cycle'] = $list_setting5['onchba_cycle'];
        			break;
        		case 6:
        			$GLOBALS['onchba_cycle'] = $list_setting6['onchba_cycle'];
        			break;
        		case 7:
        			$GLOBALS['onchba_cycle'] = $list_setting8['onchba_cycle'];
        			break;
        		case 8:
        			$GLOBALS['onchba_cycle'] = $list_setting8['onchba_cycle'];
        			break;
        	}
        	$row['daaaaa'] = $GLOBALS['onchba_cycle'];
       		$row['onchba_cycle'] = $GLOBALS['onchba_cycle'];
        	$test = date("Y/m/d", strtotime("+".$GLOBALS['onchba_cycle']." days", $row['onadd_planting_date']));
        	$o_y = intval(date('Y',strtotime($test)));        	
        	$c_y = intval(date('Y'));
        	$o_m = intval(date('m',strtotime($test)));
        	$c_m = intval(date('m'));
        	$row['o_y'] = $o_y;
        	$row['c_y'] = $c_y;
        	$row['o_m'] = $o_m;
        	$row['c_m'] = $c_m;

        	if($o_y <= $c_y){
        		if($o_y == $c_y && $o_m <= $c_m){
        			$row['onadd_planting_date'] = date("Y/m/d",$row['onadd_planting_date']);
        			$row['expected_date'] = $test;
        			$row['onadd_planting_date_unix'] = strtotime('now');
        			$row['expected_date_unix'] = strtotime($test);
        			$ret_data[] = $row;
        		}
        		else if($o_y < $c_y){
        			$row['onadd_planting_date'] = date("Y/m/d",$row['onadd_planting_date']);
        			$row['expected_date'] = $test;
        			$row['onadd_planting_date_unix'] = strtotime('now');
        			$row['expected_date_unix'] = strtotime($test);
        			$ret_data[] = $row;
        		}        		
        	}

		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getTotalQty(){
	$location = array('A1','A2','A3','A4','A5','B1','B2','B3','B4','B5');
	$onadd_cur_size = array('1','2','3','4','5','6');
	$ret_data = array();
	$conn = getDB();

	for($i=0;$i<count($location);$i++){
		for($j=0;$j<count($onadd_cur_size);$j++){
			$sql="select SUM(onadd_quantity) from onliine_add_data where onadd_location like '".$location[$i]."' and onadd_cur_size = ".$onadd_cur_size[$j];
			$qresult = $conn->query($sql);
			if ($qresult->num_rows > 0) {
				if ($row = $qresult->fetch_assoc()) {
					$ret_data[$i]['location'] = $location[$i];
					if($row['SUM(onadd_quantity)'] != '')
						$ret_data[$i][$onadd_cur_size[$j]] = $row['SUM(onadd_quantity)'];
					else
						$ret_data[$i][$onadd_cur_size[$j]] = 0;
				}
				$qresult->free();
			}
		}
	}	
	$conn->close();
	return $ret_data;
}
//function--------------------------------------------------------------------------------------
?>