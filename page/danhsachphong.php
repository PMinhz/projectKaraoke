<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once "connectDB.php";

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
$conn = open_database();
$thanhtoan = isset($_GET['thanhtoan'])? $_GET['thanhtoan']:0;
if(mysqli_query($conn,"SELECT * from dondatphong"))
{
    $count= mysqli_query($conn,"SELECT * from dondatphong");
    $dataphongdat = $count->fetch_all(MYSQLI_ASSOC);
}

if(mysqli_query($conn,"SELECT * from phong where active = 0 and check_room = 1"))
{
    $count= mysqli_query($conn,"SELECT * from phong where active = 0 and check_room = 1");
    $dataphongvip = $count->fetch_all(MYSQLI_ASSOC);
}

if(mysqli_query($conn,"SELECT * from phong where active = 0 and check_room = 0"))
{
    $count= mysqli_query($conn,"SELECT * from phong where active = 0 and check_room = 0");
    $dataphongbt = $count->fetch_all(MYSQLI_ASSOC);
}

if(mysqli_query($conn,"SELECT * from dondatphong"))
{
    $count= mysqli_query($conn,"SELECT * from dondatphong");
    $dataphongduyet = $count->fetch_all(MYSQLI_ASSOC);
}


if(mysqli_query($conn,"SELECT * from phonghoatdong"))
{
    $count= mysqli_query($conn,"SELECT * from phonghoatdong");
    $dataphonghd= $count->fetch_all(MYSQLI_ASSOC);
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
                    <span>DANH SÁCH PHÒNG</span>
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
        <!-- START -->
        <?php
            $classname = 'section-content-dashboard';
            if(count($dataphongdat) >0)
            {
                echo '<h3 class="head-nuoc">PHÒNG CẦN DUYỆT</h3>';
                 $classname = 'section-content-monan';
            }
        ?>
        <div id="section-content" class=<?=$classname?>>
        <?php 
           if($thanhtoan == 1)
            echo "<div style ='width: 100%'class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Thành công!</strong>  Thanh toán thành công!   
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
            </button>
            </div>";
        ?>
        <?php 
            foreach($dataphongdat as $phong) : ?>
                <div  class="content-overview">
                    <a href="./nhanvienduyet.php?type=1&tim=<?=$phong['id_dondat']?>" class="link-sales">
                            <div style = "background-color: #969696" class="overview overview-sales bd-rd-rem">
                                <div  class="overview-icon mr-16">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="60px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path style ="fill: #ffffff" class="icon-task-vector-first" d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
                                        <path style ="fill: #ffffff" class="icon-task-vector-last" d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                    </svg>
                                </div>
                                <div class="overview-about mr-16">
                                    <span style = "color: #ffffff" class="about-content">Phòng đặt: <?= phong($phong['id_phong'])['tenphong']?></span>
                                    <h3 style = "color: #ffffff" class="about-heading">CHỜ DUYỆT</h3>
                                    <span style="width: 100%;color: #ffffff" class="about-time">GIỜ ĐẶT: <?=$phong['gio']?></span>
                                    <span style="width: 100%;color: #ffffff" class="about-time">NGÀY ĐẶT <?=$phong['ngay']?> </span>
                                </div>
                            </div>
                        </a>
                </div>
            <?php endforeach; ?>
            <?php 
                if(count($dataphonghd) >0)
                    echo '<h3 class="head-monan">PHÒNG ĐANG HOẠT ĐỘNG</h3>';
            ?>
            <?php 
            foreach($dataphonghd as $phong) : ?>
                <div  class="overview-sales content-overview">
                    <a href="./chitiethoatdong.php?idhd=<?=$phong['id_hoatdong']?>" class="link-sales">
                            <div  class="overview overview-sales bd-rd-rem">
                                <div  class="overview-icon mr-16">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="60px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                        <path style ="fill: #f20303" class="icon-task-vector-first" d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
                                        <path style ="fill: #f20303" class="icon-task-vector-last" d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                    </svg>
                                </div>
                                <div class="overview-about mr-16">
                                    <span  class="about-content">TÊN PHÒNG: <?= phong($phong['id_phong'])['tenphong']?></span>
                                    <h3  class="about-heading">ĐANG HOẠT ĐỘNG</h3>
                                    <span style="width: 100%" class="about-time">GIỜ VÀO: <?=$phong['gio']?></span>
                                </div>
                            </div>
                        </a>
                </div>
            <?php endforeach; ?>
            <?php
                if(count($dataphongvip) >0 || count($$dataphongbt)>0)
                echo '<h3 class="head-monan">PHÒNG ĐANG TRỐNG</h3>';
            ?>
            <?php 
                foreach($dataphongvip as $phongvip) : ?>
                    <div class="content-overview">
                        <a href="./datphongNV.php?idphong=<?=$phongvip['id_phong']?>" class="link-sales">
                                <div class="overview overview-online bd-rd-rem">
                                    <div class="overview-icon mr-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="60px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path style ="fill: #00e40b" class="icon-task-vector-first" d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
                                            <path style ="fill: #00e40b" class="icon-task-vector-last" d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
                                        </g>
                                        </svg>
                                    </div>
                                    <div class="overview-about mr-16">
                                        <span style ="color:#1bc5bd"class="about-content"><?= $phongvip['tenphong']?></span>
                                        <h3 class="about-heading"><?=$phongvip['giaphong'].'K'?></h3>
                                        <span class="about-time">Phòng 15 người</span>
                                    </div>
                                </div>
                            </a>
                    </div>
                <?php endforeach; ?>
                <?php
                foreach($dataphongbt as $phongbt) : ?>
                    <div class="content-overview">
                        <a href="./datphongNV.php?idphong=<?=$phongbt['id_phong']?>" class="link-online">
                                <div class="overview overview-online bd-rd-rem">
                                    <div class="overview-icon mr-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="60px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path style ="fill: #00e40b" class="icon-task-vector-first" d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
                                                <path style ="fill: #00e40b" class="icon-task-vector-last" d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="overview-about mr-16">
                                        <span class="about-content"><?= $phongbt['tenphong']?></span>
                                        <h3 class="about-heading"><?= $phongbt['giaphong'].'K'?></h3>
                                        <span class="about-time">Phòng 8 người</span>
                                    </div>
                                </div>
                        </a>
                    </div> 
                <?php endforeach; ?>
        </div>

        <!-- START -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../main.js"></script>

</body>

</html>