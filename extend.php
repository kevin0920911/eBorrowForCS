<?php
    mb_internal_encoding('UTF-8');
    class user{
        public $ID;
        public $account;
        public $usertype;
        public $warning;
        public $name;
        private $password;

        function __construct(
            $ID,$account,$usertype,
            $warning,$name,$password){

            $this->ID = $ID;
            $this->account=$account;
            $this->usertype=$usertype;
            if ($usertype=="one"){
                $this->usertype="大一";
            }
            else if ($usertype=="two"){
                $this->usertype="大二";
            }
            else if ($usertype=="three"){
                $this->usertype="大三";
            }
            else if ($usertype=="four"){
                $this->usertype="大四";
            }
            
        
            $this->warning=$warning;
            $this->name=$name;
            $this->password=$password;
        }

        public function passwardValide($password):bool{
            return $password === $this->password;
        }
    }
?>
<?php
    function timeToPair($time): array{
        $day = match(substr($time,0,3)){
            'MON' => 0,
            'TUE' => 1,
            'WED' => 2,
            'THU' => 3,
            'FRI' => 4
        };
        $lesson = match(substr($time,4,5)){
            '1' => 0,
            '2' => 1,
            '3' => 2,
            '4' => 3,
            'A' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9
        };
        return array($day,$lesson);
    }
?>
<?php
    function printModifyButton($str){
        print  "<a href='modify_user.php?userID=$str' class='btn btn-warning btn-icon-split btn-sm' >" .
                    "<span class='icon text-white-50'>" .
                        "<i class='fas fa-exclamation-triangle'></i>" .
                    "</span>" .
                    "<span class='text'>修改</span>" .
                "</a>";
    }

    function printDeleteButton($str){
        print   "<a href='user_manage.php?delete=$str' class='btn btn-danger btn-icon-split btn-sm' onclick=\"window.confirm('真的要刪除$str\同學嗎')\">" .
                "<span class='icon text-white-50'>" .
                "<i class='fas fa-trash'></i>" .
                "</span>" .
                "<span class='text'>刪除</span>" .
                "</a>";
    }
?>