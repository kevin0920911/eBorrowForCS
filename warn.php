<?php require_once 'extend.php'; ?>
<?php // SQL connection
$mysql = new mysqli("localhost", "root", "123456");
$mysql->select_db("ncyucsie");
if ($mysql->connect_error) {
    die("<p>連接失敗：" . $mysql->connect_error . "</p><a href='login.php'>點我重新導向</a>");
}


?>
<?php //PHP mailer setup
    use PHPMailer\PHPMailer\PHPMailer;
    $clientId = getenv('MAIL_USERNAME_ID');
    $clientSecret = getenv('MAIL_PASSWORD');
    include "PHPMailer\PHPMailer\src\Exception.php";
    include "PHPMailer\PHPMailer\src\PHPMailer.php";
    include "PHPMailer\PHPMailer\src\SMTP.php";
    date_default_timezone_set('Asia/Taipei');
    $mail = new PHPMailer(true);
    $mail->CharSet = "UTF-8";
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.gmail.com"; //SMTP服務器
    $mail->Port = 587; //SSL預設Port 是465, TLS預設Port 是587
    //$mail->SMTPSecure = 'tls';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //使用SSL, 如果是TLS 請改為 PHPMailer::ENCRYPTION_STARTTLS
    $mail->Username = $clientId; // 這裡填寫你的SMTP登入帳號, 例如 your.gmail.name@gmail.com 則填寫your.gmail.name
    $mail->Password = $clientSecret; //這裡填寫你的SMTP登入密碼. 即是Gmail的密碼
?>


<?php // mail to user for warning 
    function warn($username, $account, $class)
    {
        global $mail;
        $to = $account;
        $from = 'csie@mail.ncyu.edu.tw';
        $subject = "請盡速歸還器材";
        $body = "$username 同學您好，您借用的 $class 教室即將到期，請盡速歸還，以免被記警告";

        $mail->setFrom($from , 'Mailer'); //設定寄件人電郵
        $mail->addAddress($to,"Receiver");
        $mail->addReplyTo($from , 'info'); 
        $mail->Subject =  $subject; //設定郵件主題
        $mail->Body = $body;  //設定郵件內容
        $mail->IsHTML(true);  //設定是否使用HTML格式

        $mail->send();
    }
?>
<?php // mail to user for punish 
    function punish($username, $account, $class)
    {
        global $mail;
        $to = $account;
        $from = 'csie@mail.ncyu.edu.tw';
        $subject = "警告通知";
        $body = "$username 同學您好，您已經被記一支警告，因為$class 教室未歸還";

        $mail->setFrom($from , 'Mailer'); //設定寄件人電郵
        $mail->addAddress($to,"Receiver");
        $mail->addReplyTo($from , 'info'); 
        $mail->Subject =  $subject; //設定郵件主題
        $mail->Body = $body;  //設定郵件內容
        $mail->IsHTML(true);  //設定是否使用HTML格式
        
        $mail->send();
    }
?>
<?php // mail to user for blocking 
    function block($username, $account, $class)
    {
        global $mail;
        $to = $account;
        $from = 'csie@mail.ncyu.edu.tw';
        $subject = "禁止借用教室";
        $body = "$username 同學您好，貴班已經禁止借用教室，請盡速來系辦預約時間!";

        $mail->setFrom($from , 'Mailer'); //設定寄件人電郵
        $mail->addAddress($to,"Receiver");
        $mail->addReplyTo($from , 'info'); 
        $mail->Subject =  $subject; //設定郵件主題
        $mail->Body = $body;  //設定郵件內容
        $mail->IsHTML(true);  //設定是否使用HTML格式
    }
?>

<?php
    $timeToReturn = array(
        array("1-9-10","2-9-10","3-9-10","4-9-10","5-9-10"),     //1
        array("1-10-10","2-10-10","3-10-10","4-10-10","5-10-10"),//2
        array("1-11-10","2-11-10","3-11-10","4-11-10","5-11-10"),//3
        array("1-12-10","2-12-10","3-12-10","4-12-10","5-12-10"),//4
        array("1-13-20","2-13-20","3-13-20","4-13-20","5-13-20"),//A
        array("1-14-20","2-14-20","3-14-20","4-14-20","5-14-20"),//5
        array("1-15-20","2-15-20","3-15-20","4-15-20","5-15-20"),//6
        array("1-16-20","2-16-20","3-16-20","4-16-20","5-16-20"),//7
        array("1-17-20","2-17-20","3-17-20","4-17-20","5-17-20"),//8
        array("2-8-30","3-8-30","4-8-30","5-8-30","1-8-30")      //9
    );
?>
<?php // function for determin whether date is expire
    function isExpire($date,$expire){
        if ($date[0]>$expire[0]){
            return true;
        }
        else if ($date[1]>$expire[1]){
            return true;
        }
        else if ($date[2]>$expire[2]){
            return true;
        }
        return false;
    }
    function canWarn($date,$expire){
        if ($date[0]==$expire[0]){
            if ($date[1]==$expire[1]){
                if ($date[2]-$expire[2] <= 10){
                    return true;
                }
            }
        }
        return false;
    }
?>
<?php
    $class = ["401","402","403","413","415","416","520","523","524","620","622"];
    $time = date('w-H-i');
    print ($time);
    $time = explode('-', $time);
    foreach ($class as $key) {
        $stm = $mysql->prepare("SELECT * FROM borrow WHERE classID = ?");
        $stm->bind_param('s', $key);
        $stm->execute();
        $result =$stm->get_result();
        while ($row = $result->fetch_assoc()) {
            $expire = $row['expireTime'];
            if ($row['agree']==0){continue;}
            $expire = $timeToReturn[timeToPair($expire)[0]][timeToPair($expire)[1]];
            $expire = explode('-', $expire);
            if (isExpire($time, $expire) ) {
                $userID = $row['userID'];
                $sql = "SELECT usertype FROM user WHERE userID = $userID";
                $results = $mysql->query($sql);
                
                while ($rows = $results->fetch_assoc()){
                    $usertype=$rows['usertype'] ;
                    if ($usertype == 'one' || $usertype == 'two' || $usertype == 'three' || $usertype == 'four' ) {
                        $sql1 = "SELECT Account,name,warn FROM user WHERE usertype = '$usertype'";
                        $result1 = $mysql->query($sql1);
                        $flag=true;
                        while ($row1 = $result1->fetch_assoc()){
                            $account  = $row1['Account'];
                            $username = $row1['name'];
                            $warn     = $row1['warn']+1;
 
                            $upadate = "UPDATE user SET warn='$warn' WHERE Account='$account' AND name='$username'";

                            $mysql->query($upadate);

                            punish($username,$account,$key);
                            if ($flag){
                                $flag=false;
                                if ($warn>=3){
                                    $upadate = "INSERT INTO block VALUES ('$usertype');";
                                    $mysql->query($upadate);
                                }
                            }
                        }
                        
                    }
                    else{
                        $sql1 = "SELECT Account,username,warn FROM user WHERE userID = $userID";
                        $result1 = $mysql->query($sql1);
                        while ($row1 = $result1->fetch_assoc()){
                            $account  = $row1['Account'];
                            $username = $row1['username'];
                            $warn     = $row1['warn']+1;
                            
                            $upadate = "UPDATE user SET warn='$warn' WHERE Account='$account' AND name='$username'";

                            $mysql->query($upadate);
                            if ($warn>=3){
                                $upadate = "INSERT INTO block VALUES ('$usertype');";
                                $mysql->query($upadate);
                            }

                            punish($username,$account,$key);
                        }
                    }
                }
            }
            if (canWarn($time, $expire)){
                $userID = $row['userID'];
                $sql = "SELECT account,username FROM user WHERE userID = $userID";
                $results = $mysql->query($sql);
                while ($rows = $results->fetch_assoc()){
                    $account  = $rows['account'];
                    $username = $rows['username'];
                    warn($username,$account,$key);
                }

            }
        }
    }
    $stm->close();
?>


<?php //connecting close
$mysql->close();
?>