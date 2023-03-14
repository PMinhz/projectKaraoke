<?php

session_start();
require_once "connectDB.php";

if(!isset($_SESSION['username']) && !isset($_SESSION['password']))
{
   
        header("Location: ./login.php");
        exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" href="../style.css" />
    <title>Trang chủ</title>
</head>

<body>
    <?php

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);

    $username=$_SESSION['username'];
    $error = '';
    $oldpassword = '';
    $password = '';

    if(isset($_POST['oldpassword'])  && isset($_POST['password']) && isset($_POST['login'])){
        $oldpassword = $_POST['oldpassword'];
        $password = $_POST['password'];

        if(empty($oldpassword))
        {
            $error = 'Hãy nhập mật khẩu';
        }
        else if(empty($password))
        {
            $error = 'Xác nhận mật khẩu';
        }
        else  if(($oldpassword != $password))
        {
               $error = 'Mật khẩu không khớp';
        }
            else
            {
                $conn = open_database();
                $encryptpassword = password_hash($password, PASSWORD_DEFAULT);
             
                    mysqli_query($conn,"update user set password = '$encryptpassword' where   username = '$username'");
                    session_destroy();
                    header("Location: ./login.php");
                    mysqli_close($conn);
                }

            }



    // if(isset($_POST['login'])){
    //     if($_POST['oldpassword'] == 'admin' && $_POST['password'] == '123456'){
    //         $_SESSION['oldpassword'] = $_POST['oldpassword'];
    //         header("Location: home.php");
    //         exit;
    //     }
    // }
    ?>
    <div class="section-login">
        <div class="login-logo">
            <div class="logo-company">
                <img src="../images/bg3.png" alt="" />
                <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit</h3>
            </div>
            <div class="bg-login">
                <img src="../images/bg1.svg" alt="" />
            </div>
        </div>
        <div class="login-input">
            <div class="form-user">
                <span>
                    <!-- <img src="./bg3.png" alt=""> -->
                    <h2>Members</h2>
                </span>
                <?php
                if(isset($_SESSION['alert_creat']))
                {
                    if($_SESSION['alert_creat'])
                    {
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Thành công!</strong> Tạo tài khoản thành công!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                            </div>";
                        $_SESSION['alert_creat'] = false;
                    }
                }
                ?>
                <form method="POST" action="Doimk.php">
                    <div class="form-input">
                        <label for="oldpassword">Mật khẩu mới</label>
                        <input class="bd-rd-rem" id="oldpassword" type="password" placeholder="Mật khẩu mới" value="<?= $oldpassword ?>" name="oldpassword" />
                        <div class="error-oldpassword"></div>
                    </div>
                    <div class="form-input">
                        <label for="password">Xác nhận mật khẩu</label>
                        <input class="bd-rd-rem" id="password" type="password" placeholder="Xác nhận mật khẩu" value="<?= $password ?>" name="password" />
                        <div class="error-password"></div>
                    </div>
                    <div class="form-group">
                        <?php
                        if(!empty($error)){
                            echo '<div class="alert alert-danger">'. $error .'</div>';
                        }
                        ?>
                        <button name="login" type="submit" class="btn-login bd-rd-rem">Submit</button>
                        <a href="./login.php">
                            <input  class="btn-login-danger bd-rd-rem ml-2" type="button" value="Back" />
                        </a>
                    
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../main.js"></script>
</body>
</html>