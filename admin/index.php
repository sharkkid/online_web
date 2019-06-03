<?php
include_once(dirname(__FILE__).'/config.php');

//search--------------------------------------------------------------------------------------
// search
if(($onadd_part_no = GetParam('onadd_part_no'))) {
    $search_where[] = "onadd_part_no like '%{$onadd_part_no}%'";
    $search_query_string['onadd_part_no'] = $onadd_part_no;
}
if(($onadd_part_name = GetParam('onadd_part_name'))) {
    $search_where[] = "onadd_part_name like '%{$onadd_part_name}%'";
    $search_query_string['onadd_part_name'] = $onadd_part_name;
}
if(($onadd_supplier = GetParam('onadd_supplier'))) {
    $search_where[] = "onadd_supplier like '%{$onadd_supplier}%'";
    $search_query_string['onadd_supplier'] = $onadd_supplier;
}
if(($onadd_status = GetParam('onadd_status', -1))>=0) {
    $search_where[] = "onadd_status='{$onadd_status}'";
    $search_query_string['onadd_status'] = $onadd_status;
}
if(($onadd_growing = GetParam('onadd_growing', -1))>=0) {
    $search_where[] = "onadd_growing='{$onadd_growing}'";
    $search_query_string['onadd_growing'] = $onadd_growing;
}
$search_where = isset($search_where) ? implode(' and ', $search_where) : '';
$_SESSION['query']['index'] = $search_where;
$search_query_string = isset($search_query_string) ? http_build_query($search_query_string) : '';

 // page
$pg_page = GetParam('pg_page', 1);統計
$pg_rows = 20;
$pg_total = GetParam('pg_total')=='' ? getUserQty($search_where) : GetParam('pg_total');
$pg_offset = $pg_rows * ($pg_page - 1);
$pg_pages = $pg_rows == 0 ? 0 : ( (int)(($pg_total + ($pg_rows - 1)) /$pg_rows) );

$user_list = getUser($search_where, $pg_offset, $pg_rows);
$list17 = getDetails('1');//計算1.7
$list25 = getDetails('2');//計算2.5
$list35 = getDetails('5');//計算3.5

$sum17 = $list17['SUM(onadd_quantity)'];
$sum25 = $list25['SUM(onadd_quantity)'];
$sum35 = $list35['SUM(onadd_quantity)'];

foreach ($list17 as $row) {
    $sum17 = $row['SUM(onadd_quantity)']; 
}
foreach ($list25 as $row) {
    $sum25 = $row['SUM(onadd_quantity)']; 
}
foreach ($list35 as $row) {
    $sum35 = $row['SUM(onadd_quantity)']; 
}
//search--------------------------------------------------------------------------------------

$search_where = $_SESSION['query']['ir_setting_data'];
$key_word_condition = $_SESSION['query']['key_word_condition'];
$device_list = getUser($search_where, $key_word_condition, 0, 100000000);

foreach ($user_list as $i=>$v) {
    $row = $i+5;

    $date = date ("m", $v['onadd_planting_date']);
    $date1 = date ("m", $v['onadd_cycle']);
    $date12 = ($date1 - $date);
    $onadd_sn = $v['onadd_sn'];
    $now = time();
    $conn = getDB();
    // $sql = "INSERT INTO onliine_add_data (onadd_cycle) " .
    // "VALUES ('{$now}');";
    $sql = "UPDATE onliine_add_data SET onadd_cycle='{$now}' WHERE onadd_sn='{$onadd_sn}'";
    if($conn->query($sql)) {
    } else {
    }
    $conn->close();
    
}

foreach ($user_list as $i=>$v) {
    $row = $i+5;

    $date = date ("m", $v['onadd_planting_date']);
    $date1 = date ("m", $v['onadd_cycle']);
    $date12 = ($date1 - $date);
    $onadd_sn = $v['onadd_sn'];
    $now = time();
    $conn = getDB();
    // $sql = "INSERT INTO onliine_add_data (onadd_cycle) " .
    // "VALUES ('{$now}');";
    $sql = "UPDATE onliine_add_data SET onadd_cycle='{$now}' WHERE onadd_sn='{$onadd_sn}'";
    if($conn->query($sql)) {
    } else {
    }
    $conn->close();
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo CN_NAME;?></title>
    <!-- Common plugins -->
    <!-- <link href="./../img/apple-touch-icon.png" rel="apple-touch-icon"> -->
    <link href="./../images/favicon.png" rel="icon">
    <link href="./../css1/bootstrap.min.css" rel="stylesheet">
    <link href="./../css1/simple-line-icons.css" rel="stylesheet">
    <link href="./../css1/font-awesome.min.css" rel="stylesheet">
    <link href="./../css1/pace.css" rel="stylesheet">
    <link href="./../css1/jasny-bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../css1/nanoscroller.css">
    <link rel="stylesheet" href="./../css1/metismenu.min.css">
    <link href="./../css1/c3.min.css" rel="stylesheet">
    <link href="./../css1/blue.css" rel="stylesheet">
    <!-- dataTables -->
    <link href="./../css1/jquery.datatables.min.css" rel="stylesheet" type="text/css">
    <link href="./../css1/responsive.bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- <link href="./../css1/jquery.toast.min.css" rel="stylesheet"> -->
    <!--template css-->
    <link href="./../css1/style.css" rel="stylesheet">
  </head>
  <body>
    <?php    
    foreach ($user_list as $i=>$v) {
    $row = $i+5;

    $onadd_change_basin = $v['onadd_change_basin'];;
    $date1 = date ("m", $v['onadd_cycle']);
    $date12 = ($date1 - $onadd_change_basin);
    $onadd_part_no = $v['onadd_part_no'];
    $onadd_part_name = $v['onadd_part_name'];
    $onadd_planting_date = date ("Y/m/d", $v['onadd_planting_date']);
    // echo "<script>alert(\"品號：$onadd_part_no,品名：$onadd_part_name,下種日期：$onadd_planting_date,已到換盆時間\");</script>";    
    }
    ?>
    <?php include('./htmlModule/nav.php');?>
    <!--top bar start-->
    <div class="top-bar light-top-bar"><!--by default top bar is dark, add .light-top-bar class to make it light-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6">
                    <a href="" class="admin-logo">
                        <h1><img src="./../picture/logo-dark.png" alt=""></h1>
                    </a>
                    <div class="left-nav-toggle visible-xs visible-sm">
                        <a href="">
                            <i class="glyphicon glyphicon-menu-hamburger"></i>
                        </a>
                    </div><!--end nav toggle icon-->
                    <!--start search form-->
                    <div class="search-form hidden-xs">
                            <!-- <form>
                                <input type="text" class="form-control" placeholder="Search for...">
                                <button type="button" class="btn-search"><i class="fa fa-search"></i></button>
                            </form> -->
                        </div>
                        <!--end search form-->
                    </div>
                    <div class="col-xs-6">
                        <ul class="list-inline top-right-nav">
                            <li class="dropdown avtar-dropdown">
                                <a href="" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="./../picture/avtar-1.jpg" class="img-circle" width="30" alt="">

                                </a>
                                <ul class="dropdown-menu top-dropdown">
                                    <li class="divider"></li>
                                    <!-- <li><a href="javascript: void(0);"><i class="icon-logout"></i> Logout</a></li> -->
                                    <li><a href="<?php echo WT_SERVER;?>/admin/sys/sys_login.php?op=logout">Logout</a></li>
                                </ul>
                            </li>

                        </ul> 
                    </div>
                    
                </div>
            </div>

        </div>
        <!-- top bar end-->
        
        <!--main content start-->
        <section class="main-content">



            <!--page header start-->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h4>統計表圖表</h4>
                    </div>                    
                </div>
            </div>
            <!--page header end-->


            <!--start page content-->

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="widget bg-primary padding-0">
                        <div class="row row-table">
                            <div class="col-xs-4 text-center pv-15 bg-light-dark">
                                <em class=" fa-3x">1.7寸</em>
                            </div>
                            <div class="col-xs-8 pv-15 text-center">
                                <?php
                                echo "<h2 class='mv-0'>".$sum17."</h2>" ;
                                ?>
                            </div>
                        </div>
                    </div><!--end widget-->
                </div><!--end col-->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="widget bg-teal padding-0">
                        <div class="row row-table">
                            <div class="col-xs-4 text-center pv-15 bg-light-dark">
                                <em class="fa-3x">2.5寸</em>
                            </div>
                            <div class="col-xs-8 pv-15 text-center">
                                <?php
                                echo "<h2 class='mv-0'>".$sum25."</h2>" ;
                                ?>
                            </div>
                        </div>
                    </div><!--end widget-->
                </div><!--end col-->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="widget bg-success padding-0">
                        <div class="row row-table">
                            <div class="col-xs-4 text-center pv-15 bg-light-dark">
                                <em class="fa-3x">3.5寸</em>
                            </div>
                            <div class="col-xs-8 pv-15 text-center">
                                <?php
                                if($sum35==''){
                                    echo "<h2 class='mv-0'>".'0'."</h2>" ;
                                }else{
                                    echo "<h2 class='mv-0'>".$sum35."</h2>" ;
                                }
                                ?>
                            </div>
                        </div>
                    </div><!--end widget-->
                </div><!--end col-->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="widget bg-indigo padding-0">
                        <div class="row row-table">
                            <div class="col-xs-4 text-center pv-15 bg-light-dark">
                                <em class="fa-3x">其他</em>
                            </div>
                            <div class="col-xs-8 pv-15 text-center">
                                <h2 class="mv-0">0</h2>
                            </div>
                        </div>
                    </div><!--end widget-->
                </div><!--end col-->
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            出貨報表 <small class="text-muted">每月</small>

                        </div>
                        <div class="panel-body">
                            <div>
                                <div id="stocked"></div>
                            </div>
                        </div>
                    </div>
                </div><!--col-md-12-->
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            日報表
                        </div>
                        <div class="panel-body">
                            <div>
                                <div id="timeseriesChart"></div>
                            </div>

                        </div>
                    </div>
                </div><!--col-->
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            預留區
                        </div>
                        <div class="panel-body">
                            <div class="scrollDiv">
                                <ul class="sidebar-list projects-list">                           

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            空間統計 <small class="text-muted">剩餘存放空間</small>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div id="pieChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                </div><!--end row-->

                <!--end page content-->


                <!--Start footer-->
                <footer class="footer">
                    <span>Copyright &copy; 2019. Online Plant</span>
                </footer>
                <!--end footer-->

            </section>
            <!--end main content-->



            <!--Common plugins-->
            <script src="./../js1/jquery.min.js"></script>
            <script src="./../js1/bootstrap.min.js"></script>
            <script src="./../js1/pace.min.js"></script>
            <script src="./../js1/jasny-bootstrap.min.js"></script>
            <script src="./../js1/jquery.slimscroll.min.js"></script>
            <script src="./../js1/jquery.nanoscroller.min.js"></script>
            <script src="./../js1/metismenu.min.js"></script>
            <script src="./../js1/float-custom.js"></script>
            <!--page script-->
            <script src="./../js1/d3.min.js"></script>
            <script src="./../js1/c3.min.js"></script>
            <!-- iCheck for radio and checkboxes -->
            <script src="./../js1/icheck.min.js"></script>
            <!-- Datatables-->
            <script src="./../js1/jquery.datatables.min.js"></script>
            <script src="./../js1/datatables.responsive.min.js"></script>
            <script src="./../js1/jquery.toast.min.js"></script>
            <script src="./../js1/dashboard-alpha.js"></script>
        </body>
        </html>