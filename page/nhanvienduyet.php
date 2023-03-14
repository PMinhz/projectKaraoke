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
$limit = 10;
$page = isset($_GET['page'])? $_GET['page'] : 1;
$type = isset($_GET['type'])? $_GET['type'] : 0;
$del_id = isset($_GET['delid'])? $_GET['delid'] : null;
$del_id_phong = isset($_GET['delidphong'])? $_GET['delidphong'] : null;
$confirm = isset($_GET['confirm'])? $_GET['confirm'] : null;
$type = isset($_GET['type'])? $_GET['type'] : 0;

$start = ($page -1 ) * $limit;
$alertdel = false;
$alertcom = false;
$checkdat = isset($_GET['checkdat'])? $_GET['checkdat']:2;

if($del_id !=null && $del_id_phong!=null)
{
    if(isset($_COOKIE['preventdel']))
    {
        if($_COOKIE['preventdel'] == 1)
        {
                $delquerry = "DELETE FROM dondatphong WHERE id_dondat = '$del_id'";
                $sqlr2 = "UPDATE phong SET active = 0 WHERE id_phong = '$del_id_phong'";
                if(mysqli_query($conn,$delquerry))
                {
                    mysqli_query($conn,$sqlr2) or die("Query failed.");
                    $alertdel= true;
                }
                setcookie('preventdel','0');
        }
    }    
}
if($confirm !=null)
{
    if(isset($_COOKIE['preventcon']))
    {
        if($_COOKIE['preventcon'] == 1)
        {
            date_default_timezone_set("asia/ho_chi_minh");
            $username1 = ds_don_dat($confirm)['username'];
            $hoten1 = ds_don_dat($confirm)['hoten'];
            $sdt1 = ds_don_dat($confirm)['sdt'];
            $cmnd1 = ds_don_dat($confirm)['cmnd'];
            $idphong1 = ds_don_dat($confirm)['id_phong'];
            $monan1 = ds_don_dat($confirm)['monan'];
            $gio = date("h:ia");
            $ngay = date("Y-m-d");
            $ngay = date("d/m/Y", strtotime($ngay));
            $delquerry = "INSERT INTO phonghoatdong(username,hoten,sdt,cmnd,id_phong,monan,ngay,gio)
            VALUES ('$username1','$hoten1','$sdt1','$cmnd1','$idphong1','$monan1','$ngay','$gio')";
            $sqlr2 = "DELETE FROM dondatphong WHERE id_dondat = '$confirm'";
            if(mysqli_query($conn,$delquerry))
            {
                mysqli_query($conn,$sqlr2) or die("Query failed.");
               $alertcom = true;
            }
            setcookie('preventcon','0');
        }
    }    
}
if($type ==0)
{
    if(mysqli_query($conn,"SELECT * from dondatphong LIMIT $start, $limit"))
    {
        $count= mysqli_query($conn,"SELECT * from dondatphong LIMIT $start, $limit");
        $data = $count->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        exit;
    }
    $count= mysqli_query($conn,"SELECT count(*) as total from dondatphong");
    $total = $count->fetch_assoc();
    $pages = ceil($total['total']/$limit);
}
else
{
    if(isset($_GET['tim']))
    {
        $tim = $_GET['tim'];
        if(mysqli_query($conn,"SELECT * from dondatphong where id_dondat ='$tim'"))
        {
            $count= mysqli_query($conn,"SELECT * from dondatphong where id_dondat ='$tim'");
            $data = $count->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from dondatphong");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
    }
    else
    {
        header("Location: danhsachphong.php");
    }
}

$count= mysqli_query($conn,"SELECT count(*) as total from dondatphong");
$total = $count->fetch_assoc();
$pages = ceil($total['total']/$limit);

$previous = $page - 1;
$next = $page + 1;
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
                    <span>DUYỆT PHÒNG</span>
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
        <!-- start -->
        <div class="management-user">
            <div class="user-list">
            <?php
            if ($alertdel)
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Thành công!</strong> Xóa đơn đặt thành công!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
            if ($alertcom)
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Thành công!</strong> Duyệt đơn đặt thành công!   
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
                </div>";        
            if($checkdat == 1)
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Thành công!</strong> Chỉnh sửa thành công!   
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
                </div>";
            else if($checkdat == 0)
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Thất Bại!</strong> Chỉnh sửa Thất Bại!   
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
                </div>";
            ?>
                <div class="list-header">
                    <div class="header-title">
                        <h4>Danh sách Phòng Cần Duyệt<h6></h6>
                        </h4>
                    </div>
                </div>
                <div class="header-content-table">
                    <div class="table-data-list">
                        <div class="list-management-user">
                            <table class="user-table">
                                <tr class="title-table">
                                    <th>id PHòng&emsp;</th>
                                    <th>Tên Phòng</th>
                                    <th>Tên Người Đặt</td>
                                    <th>Số điện thoại</td>
                                    <th>Chức năng</th>
                                </tr>
                                <?php
                                foreach ($data as $userinfor) : ?>
                                    <tr class="title-content">
                                        <td><?= $userinfor['id_phong'] ?></td>
                                        <td><?= phong($userinfor['id_phong'])['tenphong']?></td>
                                        <td><?= $userinfor['hoten'] ?></td>
                                        <td><?= $userinfor['sdt'] ?></td>
                                        <?php 
                                            if(empty($userinfor['monan']))
                                            {
                                                $mangmon = [];
                                            }
                                            else
                                            {
                                                $mangmon = explode("/",$userinfor['monan']);
                                            }
                                            $param = json_encode(array('tenphong'=>phong($userinfor['id_phong'])['tenphong'],'id_dondat' => $userinfor['id_dondat'],'id_phong' => $userinfor['id_phong'],'hoten' => $userinfor['hoten'], 'username' => $userinfor['username'], 'sdt' => $userinfor['sdt'],'cmnd' => $userinfor['cmnd'], 'page' => $page,'mangmon'=>$mangmon,'gio'=> $userinfor['gio'],'ngay'=>$userinfor['ngay'])); ?>
                                        <td class="see-edit-delete">
                                            <a href="#" class="link-see bd-rd-rem" onclick='showModalSeeDH(<?php echo $param ?>)'>
                                                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="eye" class="svg-inline--fa fa-eye fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19.5px" height="19.5px">
                                                    <path class="icon-see-vector-only" fill="currentColor" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                </svg>
                                            </a>
                                            <a href="./suadathang.php?id_dondat=<?=$userinfor['id_dondat']?>" class="link-edit bd-rd-rem">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path class="icon-edit-vector-first" d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) " opacity="0.9"></path>
                                                        <path class="icon-edit-vector-last" d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.5"></path>
                                                    </g>
                                                </svg>
                                            </a>
                                            <a id = "resetpassword" class="link-change-password bd-rd-rem" onclick='showModalconfirm(<?php echo $param?>)'>
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect class="icon-change-vector-last" fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
                                                        <rect class="icon-change-vector-last" fill="#000000" opacity="1" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
                                                    </g>
                                                </svg>
                                                    </a>
                                            <a class="link-delete bd-rd-rem" onclick='showModalDeleteDH(<?php echo $param ?>)'>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path class="icon-delete-vector-first" d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill-rule="nonzero">
                                                        </path>
                                                        <path class="icon-delete-vector-last" d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; 
                                ?>
                            </table>
                        </div>
                        <ul class="list-user-page">
                            <div>
                                <li class="arrow-back-list-user">
                                    <a <?php if ($page == 1)
                                            echo "";
                                        else
                                            echo "href=./nhanvienduyet.php?page=1";
                                        ?>>
                                        <div><i class="fas fa-angle-double-left"></i></div>
                                    </a>
                                </li>
                                <li class="arrow-back-list-user">
                                    <a <?php if ($previous < 1)
                                            echo "";
                                        else
                                            echo "href=./nhanvienduyet.php?page=" . $previous;
                                        ?>>
                                        <div><i class="fas fa-angle-left"></i></div>
                                    </a>
                                </li>
                                <?php
                                for ($i = 1; $i <= $pages; $i++) : ?>
                                    <li class="number-page-list-user">
                                        <a <?php if ($i == $page)
                                                echo "";
                                            else
                                                echo "href=./nhanvienduyet.php?page=" . $i;
                                            ?>>
                                            <div>
                                                <?= $i; ?>
                                            </div>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                <li class="arrow-arrive-list-user">
                                    <a <?php if ($next > $pages)
                                            echo "";
                                        else
                                            echo "href=./nhanvienduyet.php?page=" . $next;
                                        ?>>
                                        <div><i class="fas fa-angle-right"></i></div>
                                    </a>
                                </li>
                                <li class="arrow-arrive-list-user">
                                    <a <?php if ($page == $pages)
                                            echo "";
                                        else
                                            echo "href=./nhanvienduyet.php?page=" . $pages;
                                        ?>>
                                        <div><i class="fas fa-angle-double-right"></i></div>
                                    </a>
                                </li>
                            </div>
                            <div class="show-total-page">
                                <span>Đang xem Trang <?= $page ?></span>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->
    </div>

    <div class="modal-see-user js-modal-see-user" onclick="closeModalSeeDH()">
        <div class="modal-user-detail js-modal-user-detail">
            <div class="modal-close-see js-modal-close-see" onclick="closeModalSeeIcon()">
                <i class="fas fa-times close-see-user"></i>
            </div>
            <h2 class="see-user">Thông tin đơn đặt</h2>
            <form style="max-height: 500px; overflow-y:scroll; margin-bottom:10px">
                <div class="form-input-see">
                    <label>Tên Phòng</label>
                    <h6 class="h6-modal" id='modal-tenphong'></h6>
                </div>
                <div class="form-input-see">
                    <label>Người đặt</label>
                    <h6 class="h6-modal" id='modal-name'></h6>
                </div>
                <div class="form-input-see">
                    <label>CCCD/CMND</label>
                    <h6 class="h6-modal" id='modal-CMND'></h6>
                </div>
                <div class="form-input-see">
                    <label>Số điện thoại</label>
                    <h6 class="h6-modal" id='modal-SDT'></h6>
                </div>
                <div class="form-input-see">
                    <label>Username</label>
                    <h6 class="h6-modal" id='modal-username'></h6>
                </div>
                <div class="form-input-see">
                    <label>Ngày Lúc đặt</label>
                    <h6 class="h6-modal" id='modal-date'></h6>
                </div>
                <div class="form-input-see">
                    <label>Giờ Lúc đặt</label>
                    <h6 class="h6-modal" id='modal-time'></h6>
                </div>
                <h2 class="see-user">MÓN Ăn VÀ NƯỚC</h2>
                <div id ="container-monan">
                    
                </div>
            </form>
        </div>
    </div>

    <div class="modal-delete-user js-modal-delete-user">
        <div class="modal-delete">
            <h4>Bạn có muốn xóa đơn đặt Phòng</h4>
            <h6 id='modal-delete'></h6>
            <div class="btn-infor-save">
                <div class="btn-no-infor">
                    <button type="button" onclick="closeModalDeleteDH()">Hủy</button>
                </div>
                <div class="btn-yes-infor">
                    <button type="button" onclick="del_modalnv()">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-confirm-password js-modal-confirm-password">
        <div class="modal-confirm js-modal-confirm"> 
            <h4>Bạn có muốn duyệt phòng</h4>
            <h6 id = "resetpassword-user"></h6>
            <div>
                <div class="btn-close-password">
                    <button type="button" onclick="closeModalconfirm()">Hủy</button>
                </div>
                <div class="btn-change-password">
                    <button id = "btb-resetpw" name = "reset-button"type="button" onclick="confirm_modalnv()">OK</button>
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