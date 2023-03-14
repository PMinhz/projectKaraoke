<?php

    session_start();
    ob_get_contents();
    ob_start();
    require_once "./connectDB.php";

    $url = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $logout = '';
    $name = basename($url);
    $checksidebar = 0;
    if(!isset($_SESSION['username']))
    {
        header("Location:./logout.php");
        exit;
    }
    else
    {

        $user = user($_SESSION['username']);
        $nameuser = $user['fullname'];
        $check_active = is_password_change($_SESSION['username']);
        if($user['role'] == '0')
        {

            echo "
                <script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.modal-password').style.display = 'none'
                });
                </script>
            ";
            header("Location:./doanhthu.php");
         $checksidebar = 1;
        }
        else
        {
                if($check_active['active'] == 1)
                {
                        header("Location:./danhsachphong.php");   
                }
                else
                {
                    echo "
                    <script type=\"text/javascript\">
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.modal-password').style.display = 'flex'
                    });
                    </script>
                    ";

                    $password = $passwordError = '';
                    $repassword = $repasswordError = '';
                    $change = '';
                    $error = array();
                    if(isset($_POST['submit-verify']))
                    {
                        $password = $_POST['newpassword'];
                        $repassword = $_POST['retypenewpassword'];
                        $verify = verify_password($user['username'],$password);

                        if(empty($password)){
                            $passwordError = "Nhập mật khẩu";
                            array_push($error,$passwordError);
                        }
                        else if ($verify['code'] == 0 )
                        {
                            $passwordError = "Không được nhập mật khẩu cũ";
                            array_push($error,$passwordError);
                        }
                        
                        if(empty($repassword))
                        {
                            $repasswordError = "Chưa xác nhận lại mật khẩu mới";
                            array_push($error,$repasswordError);
                        }
                        else if($password !== $repassword)
                        {
                            $repasswordError = "Mật khẩu không khớp";
                            array_push($error,$repasswordError);
                        }
                        if(empty($error))
                        {
                            include_once ("./change_password.php");
                        }
                        
                    }
                }
            }
    }
    ob_end_flush();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../style.css">
    <title>Trang chủ</title>
</head>

<body>
    <div id="main" class="js-main">
        
        <!-- sidebar -->
        <?php
            if($checksidebar == 1)
                require_once "./sidebar.php";
            else
                require_once "./sidebarnhanvien.php";
        ?>        
     <!-- header -->
     <div class="header-sidebar">
        <span>Members</span>
        <svg class="show-sidebar-ipad" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px" viewBox="0 0 24 24" version="1.1">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path class="icon-showsidebar-vector-first" d="M2 11.5C2 12.3284 2.67157 13 3.5 13H20.5C21.3284 13 22 12.3284 22 11.5V11.5C22 10.6716 21.3284 10 20.5 10H3.5C2.67157 10 2 10.6716 2 11.5V11.5Z" fill="black"/>
                <path class="icon-showsidebar-vector-last" opacity="1" fill-rule="evenodd" clip-rule="evenodd" d="M9.5 20C8.67157 20 8 19.3284 8 18.5C8 17.6716 8.67157 17 9.5 17H20.5C21.3284 17 22 17.6716 22 18.5C22 19.3284 21.3284 20 20.5 20H9.5ZM15.5 6C14.6716 6 14 5.32843 14 4.5C14 3.67157 14.6716 3 15.5 3H20.5C21.3284 3 22 3.67157 22 4.5C22 5.32843 21.3284 6 20.5 6H15.5Z" fill="black"/>
            </g>
        </svg>
        </div>
        <div id="header-main" class="js-header header">
                    <div id="header-menu-main" class="js-header-menu header-menu">
                        <div class="menu-search js-hide-header" >
                            <span>ĐẶT PHÒNG</span>
                        </div>
                        <ul class="menu-itmes js-menu-itmes">
                            <li class="items-icon items-icon-hover js-items-icon" >
                                <span class="icon-name">
                                    <img  src="https://media.istockphoto.com/photos/young-man-arms-outstretched-by-the-sea-at-sunrise-enjoying-freedom-picture-id1285301614" alt="">
                                    <span>
                                        <?=$nameuser?>
                                        <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="20" height="20">
                                            <path class="icon-arrow-vector-only" style="font-size: 12px;" d="M18.71,8.21a1,1,0,0,0-1.42,0l-4.58,4.58a1,1,0,0,1-1.42,0L6.71,8.21a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l4.59,4.59a3,3,0,0,0,4.24,0l4.59-4.59A1,1,0,0,0,18.71,8.21Z"/>
                                        </svg>
                                    </span>
                                </span>
                                <ul class="icon-setting-pic js-icon-setting-show bd-rd-rem">
                                    <li class="setting-items"><a href="./Doimk.php" class="items-link">Change Password</a></li>
                                    <li class="setting-items"><a href="./logout.php" class="items-link">Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
    <!-- header -->

    <div class="modal-password">
        <div class="modal-change-password">
            <div class="modal-password-title">
                Bạn phải đổi mật khẩu khi đăng nhập lần đầu
            </div>
            <form method="POST" action="">
                <div class="form-input-password">
                    <label>Mật khẩu mới</label>
                    <input class="bd-rd-rem" id="newpassword" name="newpassword" type="password" placeholder="Nhập mật khẩu mới" value="<?= $password ?>">
                </div>
                <div class="alert alert-danger error-newpassword" style="display: none">
                    <?php
                        if(!empty($passwordError))
                        {
                            echo "<script>document.querySelector('.error-newpassword').style.display = 'block'</script>";
                            echo $passwordError;
                        }
                    ?>
                </div>
                <div class="form-input-password">
                    <label>Nhập lại mật khẩu mới</label>
                    <input class="bd-rd-rem" id="retypenewpassword" name="retypenewpassword" type="password" placeholder="Nhập lại mật khẩu mới" value = "<?= $repassword ?>">
                </div>
                <div class="alert alert-danger error-retypenewpassword" style="display: none">
                    <?php
                        if(!empty($repasswordError))
                        {
                            echo "<script>document.querySelector('.error-retypenewpassword').style.display = 'block'</script>";
                            echo $repasswordError;
                        }
                    ?>
                </div>
                <div class="btn-change-password">
                    <button name="submit-verify">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script type="text/javascript" src="../main.js"></script>

</body>
</html>