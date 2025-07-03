<?php
    ob_start();
    session_start();
    $clientId = getenv('GOOGLE_CLIENT_ID');
    $clientSecret = getenv('GOOGLE_CLIENT_SECRET');
    require_once __DIR__."\\vendor\\autoload.php";
    //use Google;

    $client = new Google\Client();
    $client->setClientId($clientId);
    $client->setClientSecret($clientSecret);

    $client->setRedirectUri("http://localhost/webServerFinalProject/login.php");
    $client->addScope("email");
    $client->addScope("profile");

    // SQL 連接
    $mysql = new mysqli("localhost", "root", "123456");
    $mysql->select_db("ncyucsie");
    if ($mysql->connect_error) {
        die("<p>連接失敗：" . $mysql->connect_error . "</p><a href='login.php'>點我重新導向</a>");
    }

    // function setup
    require_once "extend.php";
?>




<?php // Google Login modle
    // Google 登入
    if (isset($_SESSION["googleToken"])) {
        $client->setAccessToken($_SESSION["googleToken"]);
        if ($client->isAccessTokenExpired()) {
            unset($_SESSION["googleToken"]);
        }

        if (isset($_SESSION["googleToken"])) {
            $oAuth = new Google\Service\Oauth2($client);
            $info = $oAuth->userinfo->get(); // Google 使用者資訊
            $email = $info->email;
            $domain = substr($email, strpos($email, "@") + 1);

            if ($domain !== "g.ncyu.edu.tw") {
                // 不使用 "g.ncyu.edu.tw" 域名
                die(
                    "<p>登入失敗，請使用 g.ncyu.edu.tw 的電子郵件以便驗證身份</p>
                    <a href='login.php'>點我重新導向</a>"
                );
            } 
            else {
                handleGoogleLogin($email, $info->familyName, $info->givenName, $info->name);
            }
        }
    }
?>
<?php // function for google login
    // 處理 Google 登入的函數
    function handleGoogleLogin($email, $familyName, $firstName, $name)
    {
        global $mysql;

        $sql = "SELECT * FROM user WHERE Account='$email'";
        $result = $mysql->query($sql);

        if (!$result || $result->num_rows === 0) { // 新用戶前往註冊
            $_SESSION['email'] = $email;
            $_SESSION['familyName'] = $familyName;
            $_SESSION['firstName'] = $firstName;
            header("Location: register.php");
        } 
        else { // 老用戶
            while ($row = $result->fetch_assoc()) {
                $ID = $row['userID'];
                $account = $row['Account'];
                $usertype = $row['usertype'];
                $name = $row['name'];
                $warn = $row['warn'];
                $paw = $row['password'];
            }
            $user = new user(
                $ID, $account, $usertype,
                $warn, $name, $paw
            );
            $_SESSION['user'] = $user;
            if ($user->usertype == 'admin') {
                header("Location: user/personalInfo.php");
            }
            else{
                header("Location: user/personalInfo.php");
            }
        }
    }
?>


<?php // 關閉 SQL 連接
    $mysql->close();
?>
<?php ob_flush() ; ?>