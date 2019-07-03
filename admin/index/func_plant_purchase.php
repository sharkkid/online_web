<?php
include_once(dirname(__FILE__).'/../config.php');
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
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * , SUM(onadd_quantity) from onliine_add_data where onadd_growing='$onadd_growing' GROUP BY onadd_growing";
	else
		$sql="select * , SUM(onadd_quantity) from onliine_add_data where onadd_growing='$onadd_growing' GROUP BY onadd_growing";

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
	$firday = strtotime($day." 00:00:00");
	$endday = strtotime($day." 24:00:00");

	$sql="SELECT sum(onadd_quantity) as add_quantity FROM `onliine_add_data` WHERE onadd_mod_date Between {$firday} AND {$endday}}";
	$sql2="SELECT sum(onelda_quantity) as elda_quantity FROM `online_elimination_data` WHERE onelda_mod_date Between {$firday} AND {$endday}";
	$sql3="SELECT sum(onshda_quantity) as ship_quantity FROM `online_shipment_data` WHERE onshda_mod_date Between {$firday} AND {$endday}";
	// echo $sql;

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[0] = $row;
		}
		$qresult->free();
	}
	else{
		$ret_data[0] = array("add_quantity" => "0");
	}
	$qresult = $conn->query($sql2);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[1] = $row;
		}
		$qresult->free();
	}
	else{
		$ret_data[1] = array("elda_quantity" => "0");
	}
	$qresult = $conn->query($sql3);
		if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[2] = $row;
		}
		$qresult->free();
	}
	else{
		$ret_data[2] = array("ship_quantity" => "0");
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
	$list_setting1 = getSettingBySn(1.7);
	$list_setting2 = getSettingBySn(2.5);
	$list_setting5 = getSettingBySn(3.5);
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1";
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			switch ($row['onadd_growing']) {
        		case 1:
        			$onchba_cycle = $list_setting1['onchba_cycle'];
        			break;
        		case 2:
        			$onchba_cycle = $list_setting2['onchba_cycle'];
        			break;
        		case 5:
        			$onchba_cycle = $list_setting5['onchba_cycle'];
        			break;
        	}
        	if($row['onadd_plant_st']==2){
        		$onchba_cycle=1;
        		$test = date("Y/m/d", strtotime("+$onchba_cycle days", $row['onadd_planting_date']));
        	}else{
        		$test = date("Y/m/d", strtotime("+$onchba_cycle days", $row['onadd_planting_date']));
        	}
        	if(date('M',strtotime($test)) == date('M')){
        		$row['onadd_planting_date'] = date("Y/m/d",$row['onadd_planting_date']);
        		$row['expected_date'] = $test;
				$ret_data[] = $row;

        	}
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
//function--------------------------------------------------------------------------------------
?>