<?php ob_start();?>
<?php require_once "extend.php";?>

<?php
    session_start();
    $err = "";
    if (isset($_SESSION["email"]) && ($_SESSION["familyName"] || $_SESSION["firstName"])){
        $email = $_SESSION["email"];
        $familyName = $_SESSION["familyName"];
        $firstName = $_SESSION["firstName"];
        $id = substr($email,1,7);
    }
    else{
        http_response_code(403);
        die('<h1>403 Forbidden</h1>
            <hr>
            <p>您沒辦法進入此頁面喔</p>
        ');
    }

    if ( isset($_POST['familyName']) && isset($_POST['firstName']) 
    &&  isset($_POST['ID'] )&& isset($_POST['grade']) 
    && isset($_POST['email']) && isset($_POST['password']) 
    && isset($_POST['reapectPassword'])){

        $mysql = new mysqli("localhost","root","123456");
        $mysql -> select_db("ncyucsie");
        $familyName = $_POST['familyName'];
        $firstName = $_POST['firstName'];
        $name = $familyName.$firstName;
        $ID = $_POST['ID'];
        $grade = $_POST['grade'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $respectPassword = $_POST['reapectPassword'];

        if ($grade ==="一"){
            $grade = "one";
        }
        else if ($grade ==="二"){
            $grade = "two";
        }
        else if ($grade ==="三"){
            $grade = "three";
        }
        else if ($grade ==="四"){
            $grade = "four";
        }
        else {
            $err .= "Input grade as chinese ： 一 or 二 or 三 or 四<br>";
        }
        if ($password !== $respectPassword){
            $err .= "Password is not same<br>";
        }
        
        
        if ($err ===""){
            $gradeQuery = "SELECT warn from user WHERE usertype = grade";
            $result = $mysql->query($gradeQuery);
            $warn = 0;
            if ($result){
                while ( $row = $result->fetch_assoc() ){
                    $warn=$row['warn'];
                }
            }

            $sql = "INSERT INTO user(userID, Account, usertype, name, warn, password) VALUES ('$ID', '$email',  '$grade', '$name',$warn, '$password')";
            $result=$mysql->query($sql);
            if (!$result ){
                die(
                    '臨時性的失敗，請再試一次'
                );
                exit;
            }
            $_SESSION['user'] = new user(
                $ID, $email, $grade,
                $warn, $name, $password
            );

            header("Location: user/personalInfo.php");
        }
    }
    

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

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <script>
        function validateForm() {
            var familyName = document.getElementById('familyName').value.trim();
            var firstName = document.getElementById('firstName').value.trim();
            var id = document.getElementById('ID').value.trim();
            var grade = document.getElementById('grade').value.trim();
            var email = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value.trim();
            var reapectPassword = document.getElementById('reapectPassword').value.trim();

            if (familyName === '' || firstName === '' || id === '' || grade === '' || email === '' || password === '' || reapectPassword === '') {
                alert('請填寫所有欄位！');
                return false;
            }

            
            return true;
        }
    </script>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" action="register.php" method="post" onsubmit="return validateForm()">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="familyName"
                                            value="<?php print $familyName    ?>" name="familyName" readonly>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="firstName"
                                        value="<?php print $firstName    ?>" name="firstName" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="ID"
                                        value="<?php print $id    ?>" name="ID" readonly>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="grade"
                                            name= "grade" placeholder="年級">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="email"
                                    value="<?php print $email    ?>" name="email" readonly>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password"  name="password" placeholder="密碼">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="reapectPassword"  name="reapectPassword" placeholder="重複密碼">
                                    </div>
                                </div>
                                <p class="text-sm-start"><?php print $err?></p>
                                <hr>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                                
                            </form>
               
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
<?php ob_flush(); ?>