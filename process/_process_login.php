<?php

    require '../connection/_dbconnect.php';
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM `users` WHERE `user_uname`='$username'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if($num == 1){
            while($row = mysqli_fetch_assoc($result)){
                if(password_verify($password, $row['user_pass'])){
                    session_start();
                    $_SESSION['loggedin']=true;
                    $_SESSION['username']=$username;
                    $_SESSION['userid']=$row['user_id'];
                    $_SESSION['users_name']=$row['user_name'];
                    header("location:../index.php?loginsuccess=true");
                    exit();
                }
                else{
                    $showAlert = "Invalid credentials";
                }
            }
        }
        else{
            $showAlert = "Invalid credentials";
        }
    }
    header("location:../index.php?error=$showAlert");

?>