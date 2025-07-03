<?php ob_start();?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php // keep session connection
    require_once '../extend.php';
    session_start();
    if (isset($_SESSION['user']) ){
        $user = $_SESSION['user'];
        if ($user->usertype == 'admin'){
            http_response_code(403);
            die('<h1>403 Forbidden</h1>
                <hr>
                <p>管理者不要來這裡拉齁</p>
            ');
        }
    }
    else{
        http_response_code(403);
        die('<h1>403 Forbidden</h1>
            <hr>
            <p>您沒辦法進入此頁面喔</p>
        ');
    }
?>
<head>
    <?php
        if (isset($_SESSION['user']) && $_SESSION['user']-> usertype != 'admin'){
            $user = $_SESSION['user'];
        }
        else{
            http_response_code(403);
            die('<h1>403 Forbidden</h1>
                <hr>
                <p>您沒辦法進入此頁面喔</p>
            ');
        }
    ?>
    <?php
        $mysql = new mysqli("localhost", "root", "123456");
        if ($mysql->connect_error) {
            die("連接失敗：" . $mysql->connect_error);
        }
        $mysql->select_db("ncyucsie");
        $result = $mysql->query("SELECT serviceTime FROM `block` JOIN `user` ON `block`.`usertype` = `user`.`usertype` WHERE `user`.`userID` = $user->ID");
        if(!$result){
            die("查詢失敗：" . $mysql->error);
        }
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>嘉義資工教室管理與器材借用管理系統</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
    .highlight:hover {
      background-color: #f5f5f5;
      border: 1px solid #ddd;
    }
  </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="personalInfo.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">NCYU CSIE</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="personalInfo.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>個人資料</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                基礎功能
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>教室借用</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">教室借用</h6>
                        <a class="collapse-item" href="lend.php?class=401">401 大一教室</a>
                        <a class="collapse-item" href="lend.php?class=402">402 大三教室</a>
                        <a class="collapse-item" href="lend.php?class=403">403 大四教室</a>
                        <a class="collapse-item" href="lend.php?class=413">413 大二教室</a>
                        <a class="collapse-item" href="lend.php?class=415">415 電腦教室</a>
                        <a class="collapse-item" href="lend.php?class=416">416 電子電路實驗室</a>

                        <a class="collapse-item" href="lend.php?class=520">520 研討室</a>
                        <a class="collapse-item" href="lend.php?class=523">523 研討室</a>
                        <a class="collapse-item" href="lend.php?class=524">524 研討室</a>

                        <a class="collapse-item" href="lend.php?class=620">620 研討室</a>
                        <a class="collapse-item" href="lend.php?class=622">622 研討室</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>愛系服務</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">愛系服務查詢</h6>
                        <a class="collapse-item" href="#">愛系服務查詢</a>
                    </div>
                </div>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user->name?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="personalInfo.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    個人資料
                                </a>
                                <hr>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">愛系服務查詢</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-12 mb-4">

                            <!-- Illustrations -->
                            <?php 
                                
                                if ($serviceTime = $result->fetch_column()) {
                                    print "恭喜各位累積了三次以上的警告!<br>你們將要愛系服務的時間為 : ";
                                    print $serviceTime;
                                }
                                else{
                                    print "你們沒有愛系服務。";
                                }
                            
                            ?>
                                <!-- <form>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">預約者帳號</label>
                                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="ex.s1092987@g.ncyu.edu.tw">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">預約者學號</label>
                                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="ex.1092987">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">預約者姓名</label>
                                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="ex.吳家寶">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">預約者年級</label>
                                        <select class="form-control" id="exampleFormControlSelect1">
                                        <option>大一</option>
                                        <option>大二</option>
                                        <option>大三</option>
                                        <option>大四</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleDateTimePicker">選擇日期與時間</label>
                                        <input type="datetime-local" class="form-control" id="exampleDateTimePicker">
                                    </div>

                                </form> 
                                -->
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!--
    -- Logout Modal--
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    -->

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>
<?php ob_flush(); ?>