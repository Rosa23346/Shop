<?php
    include_once("../admin/config.php");
    session_start();
    function isLogin(){
        if(!isset($_SESSION['valid'])){
            header('Location: login.php'); //direct location to login.php
            exit(0);
        }
    }

    function checkLogin($userName , $password){
        global $conn;
        $sql = "select * from tbl_user where username = '$userName' and password = md5('" . $password . "') and active='1'";
        $result = mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_array($result);
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['valid'] = true;
            return true;
        }
        return false;
    }
?>