<!-- nav -->
<div class="top-bar light-top-bar">
    <div class="container-fluid">
        <div class="row" >
            <div class="col-xs-4">
                <div>
                    <a href="" class="admin-logo">
                        <img class="img-rounded" style="width: 57px;height: 56px;max-width: 100%;max-height: 100%;" src="./../../picture/logo-dark.png" alt="">
                    </a>
                </div>
                <!--end search form-->
            </div>
            <div class="col-xs-4">
                <div class="left-nav-toggle visible-xs visible-sm" >
                    <a >
                        <i class="glyphicon glyphicon-menu-hamburger"></i>
                    </a>
                </div><!--end nav toggle icon-->
                <!--start search form-->
            </div>
            <div class="col-xs-4">
                <ul class="list-inline top-right-nav">
                    <li class="dropdown avtar-dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- <img src="./../../picture/avtar-1.jpg" class="img-circle" width="30" alt=""> -->
                            <span style="font-size: 2em;padding-right: 1pt;" class="glyphicon glyphicon-collapse-down"></span>
                        </a>
                        <ul class="dropdown-menu top-dropdown">
                            <!-- <li class="divider"></li> -->
                            <!-- <li><a href="javascript: void(0);"><i class="icon-logout"></i> Logout</a></li> -->
                            <li><a href="<?php echo WT_SERVER;?>/admin/sys/sys_login.php?op=logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;登出</a></li>
                        </ul>
                    </li>

                </ul> 
            </div>
        </div>
    </div>
</div>
    <!-- top bar end-->

        <!--left navigation start-->
        <aside class="float-navigation light-navigation"  style="background-color: #bce7f1;">
            <div class="nano">
                <div class="nano-content">
                    <ul class="metisMenu nav" id="menu">
                        <li class="nav-heading"><span style="color: #2e2e2e">主要項目</span></li>
                        <!-- <li class="active">
                            <a href="javascript: void(0);" aria-expanded="true"><i class="icon-pencil"></i> 品種建立 <span class="fa arrow"></span></a>
                            <ul class="nav-second-level nav" aria-expanded="true">
                                <li><a href="<?php echo WT_SERVER;?>/admin/purchase/plant_purchase_add.php">苗種資料建立</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/flask/plant_purchase_addflask.php">瓶苗資料建立</a></li>
                                 
                            </ul>
                        </li> -->
                        <li class="active">
                            <a href="javascript: void(0);" aria-expanded="true" ><i class="icon-home" ></i> 庫存管理 <span class="fa arrow"></span></a>
                            <ul class="nav-second-level nav" aria-expanded="true"  style="background-color: #f7efd8;">
                                <li><a href="<?php echo WT_SERVER;?>/admin/purchase/plant_purchase.php">苗株庫存管理</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/flask/plant_flask.php">瓶苗資料管理</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/purchase/plant_elimination.php">汰除統計報表</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/purchase/plant_shipment.php">出貨統計報表</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/purchase/plant_purchase_details1234.php">品種資料</a></li>                                 
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" aria-expanded="true"><i class="icon-grid"></i> 植床區域管理 <span class="fa arrow"></span></a>
                            <ul class="nav-second-level nav" aria-expanded="true" style="background-color: #f7efd8">
                                <li><a href="<?php echo WT_SERVER;?>/admin/map/map.php?area=0001">植物栽培區</a></li>
<!--                                 <li><a href="<?php echo WT_SERVER;?>/admin/map/map.php?area=6a3f">B區</a></li>
                                 <li><a href="<?php echo WT_SERVER;?>/admin/map/map.php?area=6a3f">冷房</a></li> -->
                            </ul>
                        </li>

                        <li><a href="<?php echo WT_SERVER;?>/admin/index/index.php<?php echo "?year=".date("Y")."&day=".date("Y-m-d");?>">"<i class="icon-bar-chart"></i> 統計圖表 </a></li>

                        <li>
                            <a href="javascript: void(0);" aria-expanded="true" ><i class="icon-list"></i> 工作排程管理 <span class="fa arrow"></span></a>
                            <ul class="nav-second-level nav" aria-expanded="true" style="background-color: #f7efd8">
                                <li><a href="<?php echo WT_SERVER;?>/admin/schedule/plant_schedule.php?area=6a3f">每月待辦事項</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/schedule/plant_re_schedule.php?area=6a3f">每週工作事項</a></li>

                            </ul>
                        </li>

                        <li>
                            <?php if($permmsion == 0 || $permmsion == 3){ ?>
                                <a href="javascript: void(0);" aria-expanded="true"><i class="icon-tag"></i> 業務專區 <span class="fa arrow"></span></a>
                                <!-- <ul class="nav-second-level nav" aria-expanded="true">
                                 <li><a href="<?php echo WT_SERVER;?>/admin/purchase/plant_purchase_pdetails.php">品種資料</a></li>
                                </ul> -->
                                <ul class="nav-second-level nav" aria-expanded="true" style="background-color: #f7efd8">
                                 <li><a href="<?php echo WT_SERVER;?>/admin/purchase/plant_purchase_details.php">可接出貨量</a></li>
                                </ul>
                            <?php } ?>
                        </li>
                         <li><a href="<?php echo WT_SERVER;?>/admin/business/plant_business.php">"<i class="icon-map"></i> 成本管理 </a></li>
                        <li class="nav-heading"><span style="color: #2e2e2e">管理員項目</span></li>
                        <!-- <li><a href="<?php echo WT_SERVER;?>/admin/sys/sys_user.php"><i class="icon-user"></i> 帳號管理</a></li> -->
                        <li>
                            <a href="javascript: void(0);" aria-expanded="true"><i class="icon-settings"></i> 進階設定 <span class="fa arrow"></span></a>
                            <ul class="nav-second-level nav" aria-expanded="true" style="background-color: #f7efd8">
                                <li><a href="layout-sidebar-colored.html">語言設定</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/setting/plant_setting.php">成長週期設定</a></li>
                                <li><a href="<?php echo WT_SERVER;?>/admin/setting/space_setting.php">園區空間設定</a></li>
                                <?php if($permmsion == 0){ ?>
                                    <li><a href="<?php echo WT_SERVER;?>/admin/setting/person_setting.php">使用者管理</a></li>
                                <?php } ?>
                                <!-- plant_setting -->
                            </ul>
                        </li>

                        <!--<li><a href="landing/index.html" target="_blank" class="bg-primary"><i class="icon-star"></i>Landing page</a></li>-->
                    </ul>
                </div><!--nano content-->
            </div><!--nano scroll end-->
        </aside>


        <!-- ajax loading  -->
        <div id="ajax_loading" style="display:none">
            <div class="ajax_overlay blue-loader" style="opacity: 0.5; width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: 99999; background-color: rgb(0, 0, 0);">
                <div class="ajax_loader"></div>
            </div>
        </div>

        <!-- alert modal -->
        <div id="alert-modal" class="modal alert-modal" tabindex="-1" role="dialog" style="z-index: 99999;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">通知</h4>
                    </div>
                    <div class="modal-body">
                        <p class="msg"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">確定</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                $('button.go_page').click(function(e){
                    e.preventDefault();
                    var href = $(this).data('href');
                    location.href = href;
                });
            });
        </script>