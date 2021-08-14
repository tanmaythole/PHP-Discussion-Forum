<?php
    
    require 'connection/_dbconnect.php';

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!--  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link rel="stylesheet" href="css/style.css">

    <title>Home</title>
</head>

<body>

    <?php require 'partials/_header.php'; ?>
    <?php
        $catid = $_GET['catid'];
        $sql = "SELECT *FROM `category` WHERE `cat_id`='$catid'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $cat_title = $row['cat_title'];
            $cat_desc = $row['cat_description'];
        }

        // submit the question from user
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $title = $_POST['title'];
            $desc = $_POST['desc'];
            $userid = $_SESSION['userid']; //Insert user id

            // protection from attackers
            $title = str_replace('<','&lt;',$title);
            $title = str_replace('>','&gt;',$title);
            $desc = str_replace('<','&lt;',$desc);
            $desc = str_replace('>','&gt;',$desc);

            $sql = "INSERT INTO `threads` ( `thread_title`, `thread_description`, `thread_cat_id`, `thread_user_id`, `time`) VALUES ( '$title', '$desc', '$catid', '$userid', current_timestamp())";
            $result = mysqli_query($conn, $sql);

            if($result){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your question has been posted successfully. Please wait for community to respond.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
            else{
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Your question has not been posted.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
        }

    ?>
    <div class="container col-md-8 my-4">
        <div class="card ">
            <h1 class="card-header bg-dark text-light"><?php echo $cat_title; ?></h1>
            <div class="card-body bg-light text-dark">
                <h6 class="card-title"><?php echo $cat_desc; ?></h6>
                <hr>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-success">Learn More</a>
            </div>
        </div>
    </div>
    <div class="container col-md-8">
        <h2>Ask the Question</h2>
        <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
            echo '<form action="threadlist.php?catid='.$catid.'" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="title" id="floatingInput" placeholder="Title">
                    <label for="floatingInput">Title</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control" name="desc" placeholder="Describe your Problem Here"
                        id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">Description</label>
                </div>
                <button type="submit" class="btn btn-success my-2">Submit</button>
            </form>';
        }
        else{
            echo '<p class="lead">You are not logged in. Please <a href="" data-bs-toggle="modal" data-bs-target="#loginModal">log in</a> to ask a question.</p>';
        }
        ?>
    </div>
    <div class="container col-md-8 my-5" id="space">
        <h3>Browse Question</h3>

        <?php
            $sql = "SELECT * FROM `threads` WHERE `thread_cat_id` = $catid";
            $result = mysqli_query($conn, $sql);
            $noResult = true;
            while($row = mysqli_fetch_assoc($result)){
                $noResult = false;
                $thread_user_id = $row['thread_user_id'];

                $sqluser = "SELECT `user_name` FROM `users` WHERE `user_id`=$thread_user_id";
                $resultuser = mysqli_query($conn, $sqluser);
                $user = mysqli_fetch_assoc($resultuser);
                $user_name = $user['user_name'];

                echo '<div class="media py-2">
                    <img src="images/user.png" width="44px" class="mr-3" alt="...">
                    <div class="media-body">
                        <p class="font-weight-bold my-0">'.$user_name.' <span class="badge bg-secondary">'.$row['time'].'</span></p>
                        <h5 class="mt-0"><a class="text-dark" href="threads.php?threadid='.$row['thread_id'].'">'.$row['thread_title'].'</a></h5>
                        <p>'.$row['thread_description'].'</p>
                    </div>
                </div>';
            }

            if($noResult){
                echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                  <p class="display-4">No Question Found!</p>
                  <p class="lead">Be the First user to ask the Question.</p>
                </div>
              </div>';
            }
        ?>

    </div>

    <?php require 'partials/_footer.php';?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>

    <!--  -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->
</body>

</html>