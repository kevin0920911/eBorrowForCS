<?php ob_start(); ?>
<?php    require_once "../extend.php";?>
<?php // 保持Session連線
    session_start();
    require_once "../extend.php";
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        if ($user->usertype != 'admin') {
            http_response_code(403);
            die('<h1>403 Forbidden</h1>
                <hr>
                <p>您不是管理者喔</p>
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
<?php
    /*for bulletin*/
    // 新增公告
    if ( isset($_POST['add_announcement']) ) {
        $file = "../bulletin.txt";
        if ( !file_exists($file) ) { 
        $fp = fopen($file, "w");  
        fclose($fp);
        }
        $msg = "";
        $messages = nl2br($_POST["add_announcement"]);
        $fp = fopen($file, "a");  
    
        
       
        $msg .= "$messages\n";
        fputs($fp, $msg);  
        fclose($fp);       
 
        header('Location: bulletin_manage.php');
        exit();
    }
    // 刪除公告
    if (isset($_GET['delete'])) {
     
        $file = '../bulletin.txt';
        $indexToDelete = $_GET['delete'];
 
        $file_arr = file($file);
        if (isset($file_arr[$indexToDelete ])) {
            unset($file_arr[$indexToDelete ]);
        }
        $file_content = implode('', $file_arr);
        file_put_contents($file, $file_content);
 
        header('Location: bulletin_manage.php');
        exit();
    }
 
    // 編輯公告
    if (isset($_POST['edit_announcement'])) {
        $indexToEdit = $_POST['edit_index'];
        $editedAnnouncement = $_POST['edited_announcement'];
        $announcements = file("../bulletin.txt", FILE_IGNORE_NEW_LINES);
 
        if (isset($announcements[$indexToEdit])) {
            $announcements[$indexToEdit] = $editedAnnouncement;
            if (substr($announcements[count($announcements) - 1], -1) !== "\n") {
                $announcements[count($announcements) - 1] .= "\n";
            }
            file_put_contents("../bulletin.txt", implode("\n", $announcements));
        }
        header('Location: bulletin_manage.php');
        exit();
    }
    /*for bulletin*/
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>嘉義資工教室管理與器材借用管理系統</title>

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
                    <span>教室借用管理</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">教室借用</h6>
                        <a class="collapse-item" href="lend.php?class=All">所有教室</a>
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
                    <span>使用者管理</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">使用者管理</h6>
                        <a class="collapse-item" href="user_manage.php">使用者管理</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#service"
                    aria-expanded="true" aria-controls="collapseUtilities1">
                    <i class="fas fa-heart"></i>
                    <span>愛系服務管理</span>
                </a>
                <div id="service" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">愛系服務管理</h6>
                        <a class="collapse-item" href="makeup_manage.php">愛系服務管理</a>
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user->name ?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
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
                    <h1 class="h3 mb-2 text-gray-800">公告管理</h1>
                    <p class="mb-4"></p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">公告管理</h6>
                        </div>
                        <div class="card-body">
                            <h5 class="h5 mb-2 text-gray-800">公告</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>公告文字</th>
                                            <th>功能</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>公告文字</th>
                                            <th>功能</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>


                                        <?php
                                            $file = '../bulletin.txt';
                                            $fp = fopen($file, 'r');

                                            if ($file) {
                                                if (filesize($file) > 0) {
                                                    
                                                    $index=0;
                                                    while (($line = fgets($fp)) !== false) {                                         
                                                        echo "<tr>";
                                                        echo "<td>".
                                                                $line.
                                                            "</td>";
                                                        echo "<td>".
                                                        "<a href=\"modify_bulletin.php?edit=$index\" class=\"btn btn-warning btn-icon-split btn-sm\">".
                                                            "<span class=\"icon text-white-50\">".
                                                                "<i class=\"fas fa-exclamation-triangle\"></i>".
                                                            "</span>".
                                                            "<span class=\"text\">修改</span>".
                                                        "</a>"." ".
                                                        "<a href=\"bulletin_manage.php?delete=$index\" class=\"btn btn-danger btn-icon-split btn-sm\">".
                                                            "<span class=\"icon text-white-50\">".
                                                                "<i class=\"fas fa-trash\"></i>".
                                                            "</span>".
                                                            "<span class=\"text\">刪除</span>".
                                                        "</a>".
                                                        "</td>";
                                                        echo "</tr>";
                                                        $index++;
                                                    }
                                                } else {
                                                    echo "<tr>";
                                                    echo "<td>".
                                                            "沒有公告".
                                                        "</td>";
                                                    echo "</tr>"; 
                                                }
    
                                                fclose($fp);
                                            }
                                            else{
                                                echo "<tr>";
                                                    echo "<td>".
                                                        "沒有公告".
                                                    "</td>";
                                                    echo "<td></td>";
                                                echo "</tr>";    
                                            }
                                        ?>


                                    </tbody>
                                </table>
                            </div>
                            <hr>

                            <div class="row mt-4">
                                <div class="col-lg-12 mb-4">
                                    <h5 class="h5 mb-2 text-gray-800">新增公告</h5>
                                    <form method="POST" action="bulletin_manage.php">
                                        <div class="form-group">
                                            <input type="text" name="add_announcement" required class="form-control" placeholder="輸入公告內容">
                                        </div>
                                        <button type="submit" class="btn btn-primary">提交</button>
                                    </form>
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

    <!-- Logout Modal-->
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
                    <a class="btn btn-primary" href="../login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

</body>

</html>
<?php ob_flush(); ?>