<?php

use function PHPSTORM_META\type;

if (!isset($_SESSION)) {
    session_start();
}

require_once "connectDB.php";
$alertadd = false;
$hoten = '';
$sdt = '';
$cmnd ='';
$tenphong='';
$mangmon = [];
$type = isset($_GET['idhd'])? 1:0;
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
} else {
    $user = user($_SESSION['username']);
    $nameuser = $user['fullname'];
    if ($_SESSION['id_role'] == 1) {
        echo "Bạn không đủ quyền truy cập vào trang này<br>";
        echo "<a href='./khachhang.php'> Click để về lại trang chủ</a>";
        exit();
    }
}
if($type == 0)
{
    if(!isset($_GET['id_dondat']))
    {
        header("Location: nhanvienduyet.php");
        exit;
    }
    else
    {
        if(phong_duoc_dat($_GET['id_dondat']))
        {
            header("Location: nhanvienduyet.php");
            exit;
        }
        else
        {
        $conn  = open_database();
        $hoten1 = ds_don_dat($_GET['id_dondat'])['hoten'];
        $sdt1 = ds_don_dat($_GET['id_dondat'])['sdt'];
        $cmnd1 = ds_don_dat($_GET['id_dondat'])['cmnd'];
        $idphong = ds_don_dat($_GET['id_dondat'])['id_phong'];
        $tenphong = phong($idphong)['tenphong'];
        $monan = ds_don_dat($_GET['id_dondat'])['monan'];
        $id_mon = '';
        if(isset($_POST['addmonan']))
        {
                $array_soluong = []; 
                $longtext ='';
                if(mysqli_query($conn,"SELECT * from monan where available = 1"))
                {
                    $count= mysqli_query($conn,"SELECT * from monan where available = 1");
                    $datanuoc = $count->fetch_all(MYSQLI_ASSOC);
                }
                foreach($datanuoc as $data) 
                {
                    $id = $data['id_monan'];
                    $tenmon = monan($id)['Tenmon'];
                    if(isset($_POST["soluong?$id"]))
                    {
                        $soluongid = $_POST["soluong?$id"]."?"."$id"."?"."$tenmon";
                        array_push($array_soluong, $soluongid);
                    }
                }
                foreach($array_soluong as $data) 
                {
                    $longtext = $longtext.$data.'/';
                }
                if(!empty($longtext))
                {
                    $longtext = rtrim($longtext, "/ ");
                    $monan = $monan . '/'. $longtext;
                }
        }
        if(empty($monan))
            {
                $mangmon = [];
            }
            else
            {
                $mangmon = explode("/",$monan);
            }
            if(count($mangmon) == 0)
            {
                $id_mon = '';
            }
            else
            {
                foreach ($mangmon as $mon)
                {
                    $temp1= explode("?",$mon);
                    $id_mon = $id_mon.$temp1[1].'?';
                }
                $id_mon = rtrim($id_mon, "? ");
            }
            $sdt = $sdtError = '';
            $fullname = $fullnameError = '';
            $cmnd = $cmndError = '';
            $error = array();
            if(isset($_POST['adduser']))
            {
                $sdt = $_POST['sdt'];
                $fullname = $_POST['name'];
                $cmnd = $_POST['cmnd'];
                
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
        
                if(empty($cmnd))
                {
                    $cmndError = "Nhập CMND/CCCD";
                    array_push($error,$cmndError);
                }
        
                if(empty($error)) 
                {
                    
                }
            }
        }
    }
}
else
{
    if(phonghd($_GET['idhd'])!=null)
    {
       $conn  = open_database();
       $hoten1 = phonghd($_GET['idhd'])['hoten'];
       $sdt1 = phonghd($_GET['idhd'])['sdt'];
       $cmnd1 = phonghd($_GET['idhd'])['cmnd'];
       $idphong = phonghd($_GET['idhd'])['id_phong'];
       $tenphong = phong($idphong)['tenphong'];
       $monan = phonghd($_GET['idhd'])['monan'];

       $id_mon = '';
       if(isset($_POST['addmonan']))
       {
               $array_soluong = []; 
               $longtext ='';
               if(mysqli_query($conn,"SELECT * from monan where available = 1"))
               {
                   $count= mysqli_query($conn,"SELECT * from monan where available = 1");
                   $datanuoc = $count->fetch_all(MYSQLI_ASSOC);
               }
               foreach($datanuoc as $data) 
               {
                   $id = $data['id_monan'];
                   $tenmon = monan($id)['Tenmon'];
                   if(isset($_POST["soluong?$id"]))
                   {
                       $soluongid = $_POST["soluong?$id"]."?"."$id"."?"."$tenmon";
                       array_push($array_soluong, $soluongid);
                   }
               }
               foreach($array_soluong as $data) 
               {
                   $longtext = $longtext.$data.'/';
               }
               if(!empty($longtext))
               {
                   $longtext = rtrim($longtext, "/ ");
                   $monan = $monan . '/'. $longtext;
               }
       }

       if(empty($monan))
        {
            $mangmon = [];
        }
        else
        {
            $mangmon = explode("/",$monan);
        }
        if(count($mangmon) == 0)
        {
            $id_mon = '';
        }
        else
        {
            foreach ($mangmon as $mon)
            {
                $temp1= explode("?",$mon);
                $id_mon = $id_mon.$temp1[1].'?';
            }
            $id_mon = rtrim($id_mon, "? ");
        }
        $sdt = $sdtError = '';
        $fullname = $fullnameError = '';
        $cmnd = $cmndError = '';
        $error = array();
    }
    else
    {
        header("Location: danhsachphong.php");
    }
}
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
    <title>List User</title>
</head>
<body>
    <div id="main" class="js-main">

        <!-- Sidebar -->
        <?php require_once "./sidebarnhanvien.php"; ?>
        <!-- header -->
        <div class="header-sidebar">
            <span>Members</span>
            <svg class="show-sidebar-ipad" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <path class="icon-showsidebar-vector-first" d="M2 11.5C2 12.3284 2.67157 13 3.5 13H20.5C21.3284 13 22 12.3284 22 11.5V11.5C22 10.6716 21.3284 10 20.5 10H3.5C2.67157 10 2 10.6716 2 11.5V11.5Z" fill="black" />
                    <path class="icon-showsidebar-vector-last" opacity="1" fill-rule="evenodd" clip-rule="evenodd" d="M9.5 20C8.67157 20 8 19.3284 8 18.5C8 17.6716 8.67157 17 9.5 17H20.5C21.3284 17 22 17.6716 22 18.5C22 19.3284 21.3284 20 20.5 20H9.5ZM15.5 6C14.6716 6 14 5.32843 14 4.5C14 3.67157 14.6716 3 15.5 3H20.5C21.3284 3 22 3.67157 22 4.5C22 5.32843 21.3284 6 20.5 6H15.5Z" fill="black" />
                </g>
            </svg>
        </div>
        <div id="header-main" class="js-header header">
            <div id="header-menu-main" class="js-header-menu header-menu">
                <div class="menu-search js-hide-header">
                    <span>CHỈNH SỬA <?=$tenphong?></span>
                </div>
                <ul class="menu-itmes js-menu-itmes">
                    <li class="items-icon items-icon-hover js-items-icon">
                        <span class="icon-name">
                            <img src="https://media.istockphoto.com/photos/young-man-arms-outstretched-by-the-sea-at-sunrise-enjoying-freedom-picture-id1285301614" alt="">
                            <span>
                                <?= $nameuser ?>
                                <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="20" height="20">
                                    <path class="icon-arrow-vector-only" style="font-size: 12px;" d="M18.71,8.21a1,1,0,0,0-1.42,0l-4.58,4.58a1,1,0,0,1-1.42,0L6.71,8.21a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l4.59,4.59a3,3,0,0,0,4.24,0l4.59-4.59A1,1,0,0,0,18.71,8.21Z" />
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
        <div class="management-user">
                <div class="user-list">
                <?php 
                if($alertadd)
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Thành công!</strong> Chỉnh sửa thành công!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
                 ?>
                <div class="add-user-header">
                        <div class="add-user-content">
                            <h2 class="add-user-title">THÔNG TIN CƠ BẢN</h2>
                            <?php 
                                if($type == 0)
                                    $link2 = "updatedatphong.php";
                                else
                                    $link2 = "updatechitiet.php";
                            ?>
                            <form method="POST" action=<?=$link2?>>
                                <div class="form-input">
                                    <label for="username">Họ tên</label>
                                    <input class="bd-rd-rem" id="name" type="text" value="<?= $hoten1?>"  placeholder="Nhập họ tên" name="name">
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
                                    <input class="bd-rd-rem" id="cmnd" name = "cmnd" type="number"  value="<?= $cmnd1 ?>" placeholder="CCCD">
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
                                    <input class="bd-rd-rem" id="sdt" name = "sdt" type="number"  value="<?= $sdt1 ?>" placeholder="Số điện thoại">
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
                                <h2 class="add-user-title">MÓN ĂN VÀ NƯỚC UỐNG</h2>
                                <?php if(count($mangmon) == 0):?>
                                <div class="form-input">
                                    <h6 style="margin-left: 20px;margin-bottom: 20px">KHÔNG ĐẶT MÓN</h6>
                                </div>
                                <?php else:?>   
                                    <?php foreach ($mangmon as $mon) : 
                                        $temp = explode("?",$mon);
                                    ?>
                                    <div class="form-input">
                                        <label for="sdt"><?=$temp[2]?></label>
                                        <input value="<?= $temp[0] ?>" class="bd-rd-rem" name="soluong<?='?'.$temp[1]?>" class ="input-soluong" min="0" max="999" type="number" value = "0"> 
                                    </div>  
                                    <?php endforeach; ?>
                                <?php endif;?>
                                <div class="form-group">
                                    <?php if($type == 0) 
                                            $link = '?monan='.$id_mon.'&iddondat='.$_GET['id_dondat'] ;
                                        else
                                            $link = '?monan='.$id_mon.'&idhd='.$_GET['idhd'] ;
                                    ?>
                                    <button name="updatephong" class="btn-login bd-rd-rem">LƯU</button>
                                    <button onclick="window.location.href='./bosungmonan.php<?=$link?>'"  style="width: 130px;background-color: #26a403" type="button" class="btn-login bd-rd-rem ml-3">THÊM MÓN</button>
                                    <button onclick="window.location.href='./nhanvienduyet.php'" style="float: right;" type="button" class="btn-login-danger bd-rd-rem ml-3">HỦY BỎ</button>
                                </div>
                                <?php if($type == 0) 
                                        $id_chung = $_GET['id_dondat'];
                                    else
                                        $id_chung = $_GET['idhd'];
                                ?>
                                <input type="hidden" name="id_dondat" value="<?=$id_chung?>"/>
                            </form>
                        </div>
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
