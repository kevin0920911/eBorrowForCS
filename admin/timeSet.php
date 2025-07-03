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

    $usertype = $_GET['usertype'];
    $serviceTime = $_GET['serviceTime'];
    $sql ="UPDATE block SET serviceTime = '$serviceTime' WHERE usertype = '$usertype'";
    print "$serviceTime" . "$usertype";
    $mysql -> query($sql);
    header("Location: makeup_manage.php");
    exit;
?>
