<?php require_once "../extend.php";?>
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
<?php // MySQL setup
    $mysql = new mysqli("localhost", "root", "123456");
    if ($mysql->connect_error) {
        die("連接失敗：" . $mysql->connect_error);
    }
    $mysql->select_db("ncyucsie");
?>
<?php
    if (isset($_GET['class'])) {
        $page = $_GET['page'];
        $class = $_GET['class'];
        $userID = $_GET['userID'];
        $start = $_GET['start'];
        $sql = "SELECT agree FROM borrow Where classID = '$class'";
        $result = $mysql->query($sql);
        $flag = false;
        while ($row = $result->fetch_assoc()){
            $agree = $row['agree'];
            if ($agree == 1){
                $flag = true;
            }
        }

        if ($flag){
            print "<script>alert('已經被借過了')</script>";
            header("Refresh: 0.01; URL=lend.php?class=$page");
            exit;
        }
        else{
            $sql ="UPDATE borrow SET agree = 1 WHERE userID = '$userID' AND beginTime = '$start' AND classID = '$class'";
            $result = $mysql -> query($sql);
            header("Location: lend.php?class=$page");
            exit;
        }
    }
?>
<?php $mysql->close(); ?>