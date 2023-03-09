<?php 
    session_start(); 
    require_once "connectDB.php"; 
    if(isset($_SESSION['username']) && isset($_SESSION['id_role']))
    {
        $user = user($_SESSION['username']);
        $nameuser = '';
        $nameuser = $user['fullname'];
    }
    else
    {
        header("Location: ./login.php");
        exit;
    }

     $servername = "localhost";
     $username = "root";
     $password = "";
     $db_sql = "cnpm";
     $type = isset($_GET['idhd'])? 1:0 ; 
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
    if($type ==0)
    {
        if(!isset($_GET['monan']))
            header("Location: ./suadathang.php");
        if(!isset($_GET['iddondat']))
            header("Location: ./suadathang.php");
        $monan = $_GET['monan'];
        $mangmon = [];
        if(empty($monan))
        {
            $mangmon = [];
        }
        else
        {
            $mangmon = explode("?",$_GET['monan']);
        }
    }
    else
    {
        if(phonghd($_GET['idhd'])!=null)
        {
            $monan = $_GET['monan'];
            $mangmon = [];
            if(empty($monan))
            {
                $mangmon = [];
            }
            else
            {
                $mangmon = explode("?",$_GET['monan']);
            }
        }
        else
        {
            header("Location: danhsachphong.php");
        }
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
    <?php require_once "./sidebarnhanvien.php"; ?>
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
                            <span>THÊM MÓN ĂN</span>
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
    <h3 class="head-nuoc">THÊM NƯỚC UỐNG</h3>
    <?php 
        if($type ==0)
            $link = "suadathang.php?id_dondat=".$_GET['iddondat'];
        else
            $link = "suadathang.php?idhd=".$_GET['idhd'];
    ?>
    <form method="POST" action="<?=$link?>">
    <button type="button" onclick="window.location.href='./suadathang.php?id_dondat=<?=$_GET['iddondat']?>'" name="addmonan" class="btn-login-danger bd-rd-rem button-datphong">HỦY</button> 
    <button type="submit" name="addmonan" class="btn-login bd-rd-rem button-datphong mr-3">LƯU</button> 
    <div class="section-content-monan">
            <?php 
                foreach($datanuoc as $nuoc) : 
                   if(in_array($nuoc['id_monan'], $mangmon))
                        continue;
                ?>
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
                        <input name="soluong<?='?'.$nuoc['id_monan']?>" class ="input-soluong" min="1" max="999" id="sdt" name = "sdt" type="number" value = "0" disabled> 
                    </h4>
                </div>
                </a>
            </div>
        <?php endforeach; ?>
        <h3 class="head-monan">THÊM MÓN ĂN</h3>
        <?php 
            foreach($datadoan as $doan) : 
            if(in_array($doan['id_monan'], $mangmon))
                continue;
        ?>
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