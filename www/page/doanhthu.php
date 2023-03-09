<?php 

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    require_once "connectDB.php"; 

    if(!isset($_SESSION['username']))
    {
        header("Location: login.php");
        exit;
    }
    else
    {
        $user = user($_SESSION['username']);
        $nameuser = '';
        $nameuser = $user['fullname'];
        $validate = validate($_SESSION['username']);
        if($validate == 1)
        {
            echo "
                <script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.list-items-user').style.display = 'block';
                });
                </script>
            ";
        }
        else if($_SESSION['username'] == 'default')
        {
            echo "
                <script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.list-items-user').style.display = 'block';
                });
                </script>
            ";
        }
        else
        {
            echo "Bạn không đủ quyền truy cập vào trang này<br>";
			echo "<a href='../index.php'> Click để về lại trang chủ</a>";
			exit();
        }
    }
    $conn = open_database();
    $limit = 8;
    $page = isset($_GET['page'])? $_GET['page'] : 1;
    $type = isset($_GET['type'])? $_GET['type'] : 0;
    $del_id = isset($_GET['delid'])? $_GET['delid'] : null;
    $start = ($page -1 ) * $limit;
    date_default_timezone_set("asia/ho_chi_minh");
    $ngay = date("d");
    $thang = date("m");
    $nam = date("Y");
    $alertdel = false;
    if($del_id !=null)
    {
        if(isset($_COOKIE['preventdel']))
        {
            if($_COOKIE['preventdel'] == 1)
            {
                    $delquerry = "DELETE FROM hoadon WHERE id_hoadon = '$del_id'";
                    if(mysqli_query($conn,$delquerry))
                    $alertdel= true;
                    setcookie('preventdel','0');
            }
        }    
    }
    // tatca
    if($type == 0)
    {
        
        if(mysqli_query($conn,"SELECT * from hoadon LIMIT $start, $limit"))
        {
            $count= mysqli_query($conn,"SELECT * from hoadon LIMIT $start, $limit");
            $data = $count->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from hoadon");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
        $result = mysqli_query($conn, 'SELECT SUM(tongtien) AS value_sum FROM hoadon'); 
        $row = mysqli_fetch_assoc($result); 
        $sum = $row['value_sum'];
    }
    //ngay
    elseif($type == 1)
    {
        if(mysqli_query($conn,"SELECT * from hoadon where ngayhd LIKE '$ngay/$thang/$nam' LIMIT $start, $limit"))
        {
            $count= mysqli_query($conn,"SELECT * from hoadon where ngayhd LIKE '$ngay/$thang/$nam' LIMIT $start, $limit");
            $data = $count->fetch_all(MYSQLI_ASSOC);

        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from user where id_role =1");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
        $result = mysqli_query($conn, "SELECT SUM(tongtien) AS value_sum FROM hoadon where ngayhd LIKE '$ngay/$thang/$nam'"); 
        $row = mysqli_fetch_assoc($result); 
        $sum = $row['value_sum'];
    }
    //thang
    elseif($type == 2)
    {
        if(mysqli_query($conn,"SELECT * from hoadon where ngayhd LIKE '%$thang/$nam' LIMIT $start, $limit"))
        {
            $count= mysqli_query($conn,"SELECT * from hoadon where ngayhd LIKE '%/$thang/$nam' LIMIT $start, $limit");
            $data = $count->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from user where id_role =2");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
        $result = mysqli_query($conn, "SELECT SUM(tongtien) AS value_sum FROM hoadon where ngayhd LIKE '%/$thang/$nam'"); 
        $row = mysqli_fetch_assoc($result); 
        $sum = $row['value_sum'];
    }
    //nam
    elseif($type == 3)
    {
        if(mysqli_query($conn,"SELECT * from hoadon where ngayhd LIKE '%/$nam' LIMIT $start, $limit"))
        {
            $count= mysqli_query($conn,"SELECT * from hoadon where ngayhd LIKE '%/$nam' LIMIT $start, $limit");
            $data = $count->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from user where id_role =2");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
        $result = mysqli_query($conn, "SELECT SUM(tongtien) AS value_sum FROM hoadon where ngayhd LIKE '%/$nam'"); 
        $row = mysqli_fetch_assoc($result); 
        $sum = $row['value_sum'];
    }
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
        <?php require_once "./sidebar.php";?>


            <!-- Header -->
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

            <!-- Header -->
            <!--  management-user -->
            <!-- start -->
            <div class="management-user">
            <?php 
                if($alertdel)
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Thành công!</strong>   Xóa Hóa đơn thành công!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
    
             ?>
                <div class="user-list">
                    <div class="list-header">
                        <div class="header-title">
                            <h4>Danh sách Doanh Thu<h6>- <?php echo $total['total'] ?></h6></h4>
                        </div>
                        <div class="header-choose">
                            <a class="user-export js-show-file-type" style="text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path class="icon-export-vector-first"  d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.7"></path>
                                        <path class="icon-export-vector-last" d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000"></path>
                                    </g>
                                </svg>
                                <div class="export">
                                    <span>LỌC</span>
                                    <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                                </div>
                                <div class="choose-export bd-rd-rem js-choose-file-type">
                                    <p>Chọn Chức Vụ</p>
                                    <ul>
                                        <li onclick="window.location.href='./doanhthu.php?page=1&type=0'">
                                           <div>
                                                <i class="la la-print" ></i>
                                                <span>Tất Cả</span>
                                            </div>
                                        </li>
                                        <li onclick="window.location.href='./doanhthu.php?page=1&type=1'">
                                            <div>
                                                <i class="lar la-file-pdf"></i>
                                                <span>Ngày</span>
                                            </div>
                                        </li>
                                        <li onclick="window.location.href='./doanhthu.php?page=1&type=2'">
                                            <div>
                                                <i class="las la-file-csv"></i>
                                                <span>Tháng</span>
                                            </div>
                                        </li>
                                        <li onclick="window.location.href='./doanhthu.php?page=1&type=3'">
                                            <div>
                                                <i class="las la-file-csv"></i>
                                                <span>Năm</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </a>
                        </div>
                        
                    </div>
                    <div class="header-content-table">
                        <div class="table-data-list">
                            <div class="list-management-user">
                                <table class="user-table">
                                    <tr class="title-table">
                                        <th>Ngày&emsp;</th>
                                        <th>HỌ TÊN</th>
                                        <th>Giờ vào</td>
                                        <th>Phòng</th>
                                        <th>Số tiền</td>
                                        <th>Chức năng</th>
                                    </tr>          
                                    <?php 
                                        foreach($data as $userinfor) : ?>
                                                <tr class="title-content">
                                                <td><?= $userinfor['ngayhd'] ?></td>
                                                <td id ='list-chucvu'>
                                                <?php 
                                                   echo $userinfor['hoten']
                                                ?>
                                                </td>
                                                <td><?= $userinfor['giovao'] ?></td>
                                                <td><?= phong($userinfor['id_phong'])['tenphong'] ?></td>
                                                <td><?= $userinfor['tongtien'].'K' ?></td>
                                                <?php $param = json_encode(array('cmnd'=>$userinfor['cmnd'],'sdt'=>$userinfor['sdt'],'gio'=>$userinfor['thoigiansudung'],'tienmon'=> $userinfor['tienmonan'],'tienphong'=> $userinfor['tienphong'],'id_hoadon'=>$userinfor['id_hoadon']));?>
                                                <td class="see-edit-delete">
                                                    <a href="#" class="link-see bd-rd-rem" onclick='showModalSeehoadon(<?php echo $param?>)'>
                                                        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="eye" class="svg-inline--fa fa-eye fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19.5px" height="19.5px">
                                                            <path class="icon-see-vector-only" fill="currentColor" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                        </svg>
                                                    </a>
                                                    <a class="link-delete bd-rd-rem" onclick='showModalDeletehoadon(<?php echo $param?>)'>
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
                                           
                                    <?php endforeach; ?>
                                    <tr class="title-content">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>TỔNG TIỀN<?= ': '.$sum .'K' ?></td>

                                            </tr>
                                </table>
                            </div>
                            <ul class="list-user-page">
                                <div>
                                    <li class="arrow-back-list-user">
                                    <a <?php if($page == 1)
                                                echo "";
                                            else
                                                echo "href=./doanhthu.php?page=1";               
                                        ?>>
                                        <div><i class="fas fa-angle-double-left"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-back-list-user">
                                        <a <?php if($previous<1)
                                                echo "";
                                            else
                                                echo "href=./doanhthu.php?page=".$previous;  
                                        ?>>
                                            <div><i class="fas fa-angle-left"></i></div>
                                        </a>
                                    </li>
                                    <?php 
                                        for($i=1;$i<=$pages;$i++) : ?>
                                    <li class="number-page-list-user">
                                    <a <?php if($i==$page)
                                                echo "";
                                            else
                                                echo "href=./doanhthu.php?page=".$i;  
                                        ?>>
                                        <div>
                                             <?= $i; ?>
                                        </div>
                                     </a>
                                    </li>
                                    <?php endfor; ?>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($next>$pages)
                                                echo "";
                                            else
                                                echo "href=./doanhthu.php?page=".$next;               
                                        ?>>
                                        <div><i class="fas fa-angle-right"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($page == $pages)
                                                echo "";
                                            else
                                                echo "href=./doanhthu.php?page=".$pages;               
                                        ?>>
                                        <div><i class="fas fa-angle-double-right"></i></div>
                                    </a>
                                    </li>
                                </div>
                                <div class="show-total-page">
                                    <span>Đang xem Trang <?= $page?></span>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->
    </div>

    <div class="modal-see-user js-modal-see-user" onclick="closeModalSeeuser()">
        <div class="modal-user-detail js-modal-user-detail">
            <div class="modal-close-see js-modal-close-see" onclick="closeModalSeeIcon()">
                <i class="fas fa-times close-see-user"></i>
            </div>
            <h2 class="see-user">Thông tin hóa đơn</h2>
            <form style="max-height: 500px; overflow-y:scroll; margin-bottom:10px" >
                <div class="form-input-see">
                    <label>CCCD/CMND</label>
                    <h6 class="h6-modal" id = 'modal-CMND'></h6>
                </div>
                <div class="form-input-see">
                    <label>Số điện thoại</label>
                    <h6 class="h6-modal" id = 'modal-SDT'></h6>
                </div>
                <div class="form-input-see">
                    <label>Số giờ đã sử dụng</label>
                    <h6 class="h6-modal" id = 'modal-sudung'></h6>
                </div>
                <div class="form-input-see">
                    <label>Tiền món ăn</label>
                    <h6 class="h6-modal" id = 'modal-monan'></h6>
                </div>
                <div class="form-input-see">
                    <label>Tiền Phòng</label>
                    <h6 class="h6-modal" id = 'modal-phong'></h6>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-delete-user js-modal-delete-user">
        <div class="modal-delete">
            <h4>Bạn có muốn xóa hóa đơn NÀY</h4>
            <h6 id = 'modal-delete'></h6>
            <div class="btn-infor-save">
                <div class="btn-no-infor">
                    <button type="button" onclick="closeModalDeleteuser()">Hủy</button>
                </div>
                <div class="btn-yes-infor">
                    <button type="button" onclick="del_hoadon()">Xóa</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script var id = false src="../main.js"></script>

</body>
</html>