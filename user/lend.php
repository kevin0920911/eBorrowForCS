<?php ob_start();?>
<?php
$mysql = new mysqli("localhost", "root", "123456");
if ($mysql->connect_error) {
    die("連接失敗：" . $mysql->connect_error);
}
$mysql->select_db("ncyucsie");
?>
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
        if (isset($_GET['class'])) {
            // sql管理
            $class = $_GET['class'];
            if ($user->usertype == '大一' || $user->usertype == '大二' 
                || $user->usertype == '大三' || $user->usertype == '大四') {
                if ($class[0]=="5" || $class[0]=="6"){
                    http_response_code(403);
                    die('<h1>403 Forbidden</h1>
                        <hr>
                        <p>碩班、博士班才能借</p>
                        <a href="personalInfo.php">點我重新導向</a>
                    ');
                }
            }
            $result = $mysql->query("SELECT beginTime,expireTime,agree  FROM borrow  WHERE classID = '$class'");
            if (!$result) {
                die("查詢失敗：" . $mysql->error);
            }
            // 0 for not lended
            // 1 for has lended
            // 2 for has reserved
            $tableForLend = array(
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
                array(0, 0, 0, 0, 0),
            );
            while ($row = $result->fetch_assoc()) {
                if (isset($row['beginTime']) && isset($row['expireTime'])) {
                    $begin = timeToPair($row['beginTime']);
                    $end = timeToPair($row['expireTime']);
                    if ($row['agree'] == 1) {
                        for ($i = $begin[1]; $i <= $end[1]; $i++) {
                            $tableForLend[$i][$begin[0]] = 1;
                        }
                    } else {
                        for ($i = $begin[1]; $i <= $end[1]; $i++) {
                            $tableForLend[$i][$begin[0]] = 2;
                        }
                    }
                }
            }

        }
    ?>
    <?php
        $result = $mysql->query("SELECT beginTime,expireTime,agree  FROM borrow  WHERE userID = '$user->ID'");
        if (!$result) {
            die("查詢失敗：" . $mysql->error);
        }
        // 0 for not lended
        // 1 for has lended
        // 2 for has reserved
        $userTable = array(
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0),
        );
        while ($row = $result->fetch_assoc()) {
            if (isset($row['beginTime']) && isset($row['expireTime'])) {
                $begin = timeToPair($row['beginTime']);
                $end = timeToPair($row['expireTime']);
                if ($row['agree'] == 1) {
                    for ($i = $begin[1]; $i <= $end[1]; $i++) {
                        $userTable[$i][$begin[0]] = 1;
                    }
                } 
                else {
                    for ($i = $begin[1]; $i <= $end[1]; $i++) {
                        $userTable[$i][$begin[0]] = 2;

                        $sql = "SELECT name FROM class  WHERE classID = '$class'";
                        $className = $mysql->query($sql);
                        while ($r = $className->fetch_assoc()) {
                            $classTable[$i][$begin[0]] = $class . $r['name'];
                        }
                    }
                }
            }
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
      cursor: pointer;
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
                        <a class="collapse-item" href="makeup.php">愛系服務查詢</a>
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$user->name?></span>
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
                                <a class="dropdown-item" href="logout.php" >
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
                        <h1 class="h3 mb-0 text-gray-800"><?php print $class?>教室情況</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-12 mb-4">

                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php print $class?>教室使用狀況</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered align-middle">
                                        <tr  >
                                            <th class="col-lg-1 text-center table-dark"></th>
                                            <th class="col-lg-2 text-center table-dark">一</th>
                                            <th class="col-lg-2 text-center table-dark">二</th>
                                            <th class="col-lg-2 text-center table-dark">三</th>
                                            <th class="col-lg-2 text-center table-dark">四</th>
                                            <th class="col-lg-2 text-center table-dark">五</th>
                                        </tr>
                                        <?php
                                            for ($i = 0; $i < 10; $i++) {
                                                print "<tr>";
                                                print "<th class=\"col-lg-1 text-center table-dark\">";
                                                if ($i >= 0 && $i < 4) {
                                                    print $i + 1;
                                                } else if ($i == 4) {
                                                    print "A";
                                                } else if ($i >= 5 && $i < 10) {
                                                    print $i;
                                                }
                                                print "</th>";
                                                for ($j = 0; $j < 5; $j++) {
                                                    if ($tableForLend[$i][$j] == 0) {
                                                        if ($userTable[$i][$j] == 0) {
                                                            print "<td class='col-lg-2 text-center highlight' onclick=\"location.href='borrow.php?class=$class&start=$i+$j'\">可預約</td>";
                                                        }
                                                        else{
                                                            print "<td class='col-lg-2 text-center'>不可預約</td>";
                                                        }
                                                    } 
                                                    else if ($tableForLend[$i][$j] == 1) {
                                                        print "<td class='col-lg-2 text-center table-danger'>正在使用</td>";
                                                    } 
                                                    else if ($tableForLend[$i][$j] == 2) {
                                                        print "<td class='col-lg-2 text-center table-warning'>已預約</td>";
                                                    }
                                                }
                                                print "</tr>";
                                            }
                                            ?>
                                    </table>
                                </div>
                            </div>


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
<?php $mysql->close(); ?>