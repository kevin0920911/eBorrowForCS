<?php ob_start()?>
<?php //set up
       session_start();

       // SQL 連接
       $mysql = new mysqli("localhost", "root", "123456");
       $mysql->select_db("ncyucsie");
       if ($mysql->connect_error) {
           die("<p>連接失敗：" . $mysql->connect_error . "</p><a href='login.php'>點我重新導向</a>");
       }
   
       // function setup
       require_once "extend.php";
?>
<?php // Locoal login modle
    // 正常登入
    if (isset($_POST['Password']) && isset($_POST['Email'])) {
        handleNormalLogin($_POST['Email'], $_POST['Password']);
    }
?>
<?php // function for local login
    // 處理正常登入的函數
    function handleNormalLogin($email, $password)
    {
        global $mysql;
        // note that ：to some extent,this method can prevent SQL injection 
        $stmt = $mysql->prepare("SELECT * FROM user WHERE Account = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
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
    
            if ($user->passwardValide($password)) {
                $_SESSION['user'] = $user;
                if ($user->usertype=='admin') {
                    header("Location: admin/personalInfo.php");
                }
                else{
                    header("Location: user/personalInfo.php");
                }
            } 
            else {
                header("Location: login.php");
            }
        } 
        else {
            $_SESSION['err']="Password is incorrect";
            header("Location: login.php");
        }
    
        /** 
        $sql = "SELECT * FORM user Where Account = '$email'";
        $result = $mysql->query($sql);
        while ($row = $mysql->fetch_assoc($sql)){
            $ID = $row['UserID'];
            $account = $row['Account'];
            $usertype = $row['usertype'];
            $name = $row['name'];
            $warn = $row['warn'];
            $paw = $row['password'];
        }
        $user = new user(
            $ID,$account,$usertype,
            $warn,$name,$paw
        );

        if ($user->passwardValide($password)){
            $_SESSION['user'] = $user;
            header("Location: personalInfo.php");
        }
        else{
            header("Location: login.php");
        }
        */
    }
?>
<?php ob_flush();?>
