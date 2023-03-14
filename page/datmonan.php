<?php 
    session_start(); 
    require_once "connectDB.php"; 
    if(isset($_SESSION['username']) && isset($_SESSION['id_role']))
    {
            if($_SESSION['id_role'] == 2 || $_SESSION['id_role'] ==0)
            {
                header("Location: ./quanly.php");
                exit;
            }
        $user = user($_SESSION['username']);
        $nameuser = '';
        $nameuser = $user['fullname'];
    }
    else
    {
        header("Location: ./login.php");
        exit;
    }
    if(isset($_GET['idphong']) && isset($_GET['hoten']) && isset($_GET['cmnd']) && isset($_GET['sdt']))
    {
        $idphong = $_GET['idphong'];
        $hoten = $_GET['hoten'];
        $cmnd = $_GET['cmnd'];
        $sdt = $_GET['sdt'];
    }
    else
    {
        header("Location: ./khachhang.php");
    }
     $servername = "localhost";
     $username = "root";
     $password = "";
     $db_sql = "cnpm";

    
     $conn  = new mysqli($servername,$username,$password,$db_sql);
   
     if ($conn  -> connect_errno) 
       die('Connect error:' . $conn  -> connect_error);
        
    if(mysqli_query($conn,"SELECT * from monan where phanloai = 1 and available = 1"))
    {
        $count= mysqli_query($conn,"SELECT * from monan where phanloai = 1 and available = 1");
        $datanuoc = $count->fetch_all(MYSQLI_ASSOC);
    }

    if(mysqli_query($conn,"SELECT * from monan where phanloai = 0 and available = 1"))
    {
        $count= mysqli_query($conn,"SELECT * from monan where phanloai = 0 and available = 1");
        $datadoan = $count->fetch_all(MYSQLI_ASSOC);
    }
    mysqli_close($conn);
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
    <!-- sidebar -->
    <div class="coating-sidebar">
        <div class="sidebar-menu">
        <div class="menu-logo">
            <a class = "index" style=" text-decoration: none;">
                <span class="logo-name">KARAOKE
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="hideSidebar two-rotate-right">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" >
                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                        <path class="icon-twoarrow-vector-last" d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)"></path>
                        <path class="icon-twoarrow-vector-first"  d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="1" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)"></path>
                    </g>
            </svg></span>
            </a>
        </div>
        <div class="nav-menu">
            <ul class="menu-lists">
                <li class="list-items animation-submenu list-items-task">
                    <a href="./khachhang.php" class="list-user">
                        <div class="icon-listuser-vector-only">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path class="icon-listtask-vector-first" d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                    <path class="icon-listtask-vector-last" d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="1"/>
                                </g>
                            </svg>
                            <span>ĐẶT PHÒNG</span>
                        </div>
                    </a>
                </li>
                <li class="list-items animation-submenu list-items-userlist">
                    <a href="#" class="list-user">
                        <div class="icon-listuser-vector-only">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path class="icon-listtask-vector-first" d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                    <path class="icon-listtask-vector-last" d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="1"/>
                                </g>
                            </svg>
                            <span>DANH SÁCH MÓN ĂN</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
    <!-- sidbar -->
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
                            <span>ĐẶT TRƯỚC MÓN ĂN</span>
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
    <h3 class="head-nuoc">ĐẶT NƯỚC UỐNG</h3>
    <form method="POST" action="dulieudatphong.php">
    <button name="adduser" class="btn-login-danger-return bd-rd-rem button-datphong" style="background-color: #48b649;">ĐẶT PHÒNG</button> 
    <div class="section-content-monan">
        <?php 
            foreach($datanuoc as $nuoc) : ?>
        <div class = "monan monan-sales">
            <a style=" cursor: pointer;text-decoration: none; color:white">
            <div class="container-img" onclick="checkbox(this.parentNode.parentNode,this.children[1],this.parentNode.children[1].children[2].children[0])">
                <img src="<?= "../imgmonan/".$nuoc['hinhanh'].".png"?>" alt="">
                <input onclick="checkbox(this.parentNode.parentNode.parentNode,this,this.parentNode.parentNode.children[1].children[2].children[0])" class = "checkbox" type="checkbox">
            </div>
            <div class="infor-monan">
                <h4>TÊN NƯỚC: <?= $nuoc['Tenmon']?> </h4>
                <h4>GIÁ TIỀN: <?= $nuoc['giamon'].'K/1'?></h4>
                <h4 style="width: 100%;">SỐ LƯỢNG:
                    <input name="soluong<?='?'.$nuoc['id_monan']?>" class ="input-soluong"min="1" max="999" id="sdt" name = "sdt" type="number" value = "0" disabled> 
                 </h4>
            </div>
            </a>
        </div>
        <?php endforeach; ?>
        <h3 class="head-monan">ĐẶT MÓN ĂN</h3>
        <?php 
            foreach($datadoan as $doan) : ?>
        <div class = "monan monan-sales">
            <a  style=" cursor: pointer;text-decoration: none; color:white">
            <div class="container-img" onclick="checkbox(this.parentNode.parentNode,this.children[1],this.parentNode.children[1].children[2].children[0])">
                <img src="<?= "../imgmonan/".$doan['hinhanh'].".png"?>" alt="">
                <input onclick="checkbox(this.parentNode.parentNode.parentNode,this,this.parentNode.parentNode.children[1].children[2].children[0])" class = "checkbox" type="checkbox">
            </div>
            <div class="infor-monan">
                <h4>TÊN MÓN: <?= $doan['Tenmon']?> </h4>
                <h4>GIÁ TIỀN: <?= $doan['giamon'].'K/1'?></h4>
                <h4 style="width: 100%;">SỐ LƯỢNG:
                   <input name="soluong<?='?'.$doan['id_monan']?>" class ="input-soluong" min="1" max="999" id="sdt" name = "sdt" type="number" value = "0" disabled> 
                </h4>
            </div>
            </a>
        </div>
        <?php endforeach; ?> 
         <input type="hidden" name="hoten" value="<?=$hoten?>"/>
         <input type="hidden" name="username" value="<?=$_SESSION['username']?>"/>
         <input type="hidden" name="sdt" value="<?=$sdt?>"/>
         <input type="hidden" name="cmnd" value="<?=$cmnd?>"/>
         <input type="hidden" name="idphong" value="<?=$idphong?>"/>
    </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../main.js"></script>           
</body>
</html>