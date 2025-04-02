<?php
    include("../config.php");
    session_start();
    $user_check = $_SESSION['login_user'];
    $role= $_SESSION['role'];
    $sql = "select * from users where userid='$user_check' and roleid ='$role'";
    $result = mysqli_query($connection,$sql);
    $count = mysqli_num_rows($result);
    if($count == 1 && $role == 2){
        $now = time();
        if ($now > $_SESSION['expire']){
            session_destroy();
            header("location:sessionexpire.php");
            exit();
        }
    }
    else {
        header("location:sessionexpire.php");
    }
?>