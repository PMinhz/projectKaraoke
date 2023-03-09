<?php 
    session_start(); 
    require_once "connectDB.php"; 
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
<?php 

    $username = $usernameError= '';
    $password = $passwordError = '';
    $chucvu = 1;
    $email = $emailError = '';
    $sdt = $sdtError = '';
    $fullname = $fullnameError = '';
    $address = $addressError = '';
    $cmnd = $cmndError = '';
    $error = array();
    $active = 1;
    if(isset($_POST['adduser']))
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $sdt = $_POST['sdt'];
        $fullname = $_POST['name'];
        $address = $_POST['address'];
        $cmnd = $_POST['cmnd'];
        $password = $_POST['password'];

        if(empty($username)){
            $usernameError = "Nhập tài khoản";
            array_push($error,$usernameError);
        }
        else if(is_username_exits($username))
        {
            $usernameError = "Tài khoản đã tồn tại";
            array_push($error,$usernameError);
        }

        if(empty($password))
        {
            $passwordError = "Nhập mật khẩu";
            array_push($error,$passwordError);
        }

        if(empty($email))
        {
            $emailError = "Nhập email";
            array_push($error,$emailError);
        }
        else if(is_email_exits($email))
        {
            $emailError = "Email đã tồn tại";
            array_push($error,$emailError);
        }

        if(empty($sdt))
        {
            $sdtError = "Nhập số điện thoại";
            array_push($error,$sdtError);
        }

        if(empty($fullname))
        {
            $fullnameError = "Nhập họ tên";
            array_push($error,$fullnameError);
        }

        if(empty($address))
        {
            $addressError = "Nhập địa chỉ";
            array_push($error,$addressError);
        }

        if(empty($cmnd))
        {
            $cmndError = "Nhập CMND/CCCD";
            array_push($error,$cmndError);
        }
        
        if(empty($error)) 
        {
            $encryptpassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user(fullname,username,password,id_role,cmnd,sdt,email,address,active)
            VALUES ('$fullname','$username','$encryptpassword','$chucvu','$cmnd','$sdt','$email','$address','$active')";
            $mysqli = open_database();
            $query = mysqli_query($mysqli,$sql);
            if(mysqli_close($mysqli))
            {
                $_SESSION['alert_creat'] = true;
                Header("Location: ./login.php");
            }
        }
    }
?>
    <div class="section-login">
        <div class="login-logo">
            <div class="logo-company">
                <img src="../images/bg3.png" alt="">
                <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit</h3>
            </div>
            <div class="bg-login">
                <img src="../images/bg1.svg" alt="">
            </div>
        </div>

        <div class="login-input">
            <div class="form-user">
                <span>
                    <!-- <img src="./bg3.png" alt=""> -->
                    <h2>SIGN UP</h2>
                </span>
                <form method="POST" action="">
                                <div class="form-input">
                                    <label for="username">Họ tên</label>
                                    <input class="bd-rd-rem" id="name" type="text"  placeholder="Nhập họ tên" name="name">
                                </div>
                                <div class="alert alert-danger error-fullname" style="display:none">
                                    <?php
                                        if(!empty($fullnameError))
                                        {
                                            echo "<script>document.querySelector('.error-fullname').style.display = 'block'</script>";
                                            echo $fullnameError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input">
                                    <label for="cmnd">Căn cước công dân</label>
                                    <input class="bd-rd-rem" id="cmnd" name = "cmnd" type="number" placeholder="CCCD">
                                </div>
                                <div class="alert alert-danger error-cmnd" style="display:none">
                                    <?php
                                        if(!empty($cmndError))
                                        {
                                            echo "<script>document.querySelector('.error-cmnd').style.display = 'block'</script>";
                                            echo $cmndError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input">
                                    <label for="sdt">SĐT</label>
                                    <input class="bd-rd-rem" id="sdt" name = "sdt" type="number" placeholder="Số điện thoại">
                                </div>
                                <div class="alert alert-danger error-sdt" style="display:none">
                                    <?php
                                        if(!empty($sdtError))
                                        {
                                            echo "<script>document.querySelector('.error-sdt').style.display = 'block'</script>";
                                            echo $sdtError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input">
                                    <label for="email">Email</label>
                                    <input class="bd-rd-rem" id="email" name = "email" type="email" placeholder="Email">
                                </div>
                                <div class="alert alert-danger error-email" style="display:none">
                                    <?php
                                        if(!empty($emailError))
                                        {
                                            echo "<script>document.querySelector('.error-email').style.display = 'block'</script>";
                                            echo $emailError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input">
                                    <label for="address">Địa chỉ</label>
                                    <input class="bd-rd-rem" id="address" name = "address" type="text" placeholder="Địa chỉ">
                                </div>
                                <div class="alert alert-danger error-address" style="display:none">
                                    <?php
                                        if(!empty($addressError))
                                        {
                                            echo "<script>document.querySelector('.error-address').style.display = 'block'</script>";
                                            echo $addressError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input">
                                    <label for="username">Tài khoản</label>
                                    <input class="bd-rd-rem" id="username" type="text" placeholder="Tài khoản" name="username">
                                </div>
                                <div class="alert alert-danger error-username" style="display:none">
                                    <?php
                                        if(!empty($usernameError))
                                        {
                                            echo "<script>document.querySelector('.error-username').style.display = 'block'</script>";
                                            echo $usernameError;
                                        }
                                    ?>
                                </div>
                                <div class="form-input">
                                    <label for="password">Mật Khẩu</label>
                                    <input class="bd-rd-rem" id="password" type="password" placeholder="Mật Khẩu" name="password">
                                </div>
                                <div class="alert alert-danger error-password" style="display:none">
                                <?php
                                        if(!empty($passwordError))
                                        {
                                            echo "<script>document.querySelector('.error-password').style.display = 'block'</script>";
                                            echo $passwordError;
                                        }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <button name="adduser"type="submit" class="btn-login bd-rd-rem">SIGN UP</button>
                                    <button onclick="window.location.href='./login.php'" type="button" class="btn-login-danger-return bd-rd-rem ml-3">BACK TO LOGIN </button>
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
