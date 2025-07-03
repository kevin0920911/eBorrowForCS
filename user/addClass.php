<?php require_once '../extend.php';?>
<?php
    $mysql = new mysqli("localhost", "root", "123456");
    $mysql->select_db("ncyucsie");
    if ($mysql->connect_error) {
        die("<p>連接失敗：" . $mysql->connect_error . "</p><a href='../login.php'>點我重新導向</a>");
    }
    
?>
<?php // keep session connection
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
<?php
    if (isset($_POST['startTime']) && $_POST['endTime'] && $_POST['class']) {
        $beginTime = $_POST['startTime'];
        $expireTime = $_POST['endTime'];
        $class = $_POST['class'];
        $item = $class."00";
        $userID = $user->ID;

        $stmt = $mysql->prepare("INSERT INTO borrow (classID, userID, itemID, beginTime, expireTime) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $class,  $userID, $item, $beginTime, $expireTime);
        $stmt->execute();

        $result = $stmt->get_result();
        header("Location: personalInfo.php");
    }
?>
<?php $mysql->close(); ?>