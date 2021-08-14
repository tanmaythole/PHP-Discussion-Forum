<?php
    require '../connection/_dbconnect.php';
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $showAlert = false;
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        $existsql = "SELECT * FROM `users` WHERE `user_uname`='$username'";
        $result = mysqli_query($conn, $existsql);
        $num = mysqli_num_rows($result);
        if($num>0){
            $showAlert = "Username Already Exists.";
        }
        else{
            $existsql = "SELECT * FROM `users` WHERE `user_email`='$email'";
            $result = mysqli_query($conn, $existsql);
            $num = mysqli_num_rows($result);
            if($num>0){
                $showAlert = "Email Already in use.";
            }
            else{
                if($password == $cpassword){
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO `users` (`user_name`, `user_uname`, `user_email`, `user_pass`, `datetime`) VALUES ('$name', '$username', '$email', '$hash', current_timestamp());";
                    $result = mysqli_query($conn, $sql);
                    if($result){
                        header("location: ../index.php?signupsuccess=true");
                        exit();
                    }
                }
                else{
                    $showAlert = "Password Not Matched";
                }
                
            }
        }

    }
    header("location: index.php?signupsuccess=false&&error=$showAlert");


?>