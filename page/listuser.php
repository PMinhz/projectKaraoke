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
    $limit = 10;
    $page = isset($_GET['page'])? $_GET['page'] : 1;
    $type = isset($_GET['type'])? $_GET['type'] : 0;
    $del_id = isset($_GET['delid'])? $_GET['delid'] : null;
    $res_id = isset($_GET['resid'])? $_GET['resid'] : null;
    $start = ($page -1 ) * $limit;
    $alertdel = false;
    $alertres = false;
    if($del_id !=null)
    {
        if(isset($_COOKIE['preventdel']))
        {
            if($_COOKIE['preventdel'] == 1)
            {
                    $delquerry = "DELETE FROM user WHERE id_user = '$del_id'";
                    if(mysqli_query($conn,$delquerry))
                    $alertdel= true;
                    setcookie('preventdel','0');
            }
        }    
    }
    if(isset($_GET['resid']))
    {
        if(isset($_COOKIE['preventres']))
        {
            if($_COOKIE['preventres'] == 1)
            {
                $sql = "select password,username from user where id_user = '$res_id'";
                $stm = $conn->prepare($sql);

                if(!$stm->execute()){
                    die('Query error: '.$stm->error);
                }
                
                $result = $stm->get_result();

                $username = '';
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()) {
                        $username = $row['username'];
                    $encryptpassword = password_hash($username, PASSWORD_DEFAULT);
                    $sqlr = "UPDATE user SET `password` = '".$encryptpassword."' WHERE id_user = '".$res_id."'";
                    $sqlr2 = "UPDATE user SET active = 0 WHERE id_user = '$res_id'";
                    mysqli_query($conn,$sqlr) or die("Query failed.");
                    mysqli_query($conn,$sqlr2) or die("Query failed.");
                    }
                }
                $alertres = true;
                setcookie('preventres','0');
            }
        }
    }

    if($type == 0)
    {
        if(mysqli_query($conn,"SELECT * from user where id_role != 0 LIMIT $start, $limit"))
        {
            $count= mysqli_query($conn,"SELECT * from user where id_role != 0 LIMIT $start, $limit");
            $data = $count->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from user where id_role != 0");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
    }
    elseif($type == 1)
    {
        if(mysqli_query($conn,"SELECT * from user where id_role = 1 LIMIT $start, $limit"))
        {
            $count= mysqli_query($conn,"SELECT * from user where id_role =1 LIMIT $start, $limit");
            $data = $count->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from user where id_role =1");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
    }
    elseif($type == 2)
    {
        if(mysqli_query($conn,"SELECT * from user where id_role = 2 LIMIT $start, $limit"))
        {
            $count= mysqli_query($conn,"SELECT * from user where id_role =2 LIMIT $start, $limit");
            $data = $count->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            exit;
        }
        $count= mysqli_query($conn,"SELECT count(*) as total from user where id_role =2");
        $total = $count->fetch_assoc();
        $pages = ceil($total['total']/$limit);
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
                    <strong>Thành công!</strong>   Xóa user thành công!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
    
             ?>
             <?php 
                if($alertres)
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Thành công!</strong>   Khôi phục mật khẩu thành công!   
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                    </div>";
    
             ?>
                <div class="user-list">
                    <div class="list-header">
                        <div class="header-title">
                            <h4>Danh sách người dùng<h6>- <?php echo $total['total'] ?></h6></h4>
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
                                        <li onclick="window.location.href='./listuser.php?page=1&type=0'">
                                           <div>
                                                <i class="la la-print" ></i>
                                                <span>Tất Cả</span>
                                            </div>
                                        </li>
                                        <li onclick="window.location.href='./listuser.php?page=1&type=1'">
                                            <div>
                                                <i class="lar la-file-pdf"></i>
                                                <span>Khách hàng</span>
                                            </div>
                                        </li>
                                        <li onclick="window.location.href='./listuser.php?page=1&type=2'">
                                            <div>
                                                <i class="las la-file-csv"></i>
                                                <span>Nhân viên</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </a>
                            <a href="#" class="user-add" style="text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path class="icon-adduser-vector-first" d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.9"/>
                                        <path class="icon-adduser-vector-last" d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                    </g>
                                </svg>
                                <span>Thêm nhân viên</span>
                            </a>
                        </div>
                        
                    </div>
                    <div class="header-content-table">
                        <div class="table-data-list">
                            <div class="list-management-user">
                                <table class="user-table">
                                    <tr class="title-table">
                                        <th>id&emsp;</th>
                                        <th>Nhân viên</th>
                                        <th>Chức vụ</td>
                                        <th>Tên người dùng</th>
                                        <th>Số điện thoại</td>
                                        <th>Chức năng</th>
                                    </tr>          
                                    <?php 
                                        foreach($data as $userinfor) : ?>
                                                <tr class="title-content">
                                                <td><?= $userinfor['id_user'] ?></td>
                                                <td>
                                                    <div class="infor-employ">
                                                        <img src="https://i.pinimg.com/originals/2c/84/5a/2c845a66b8ad2a8aafd288bdc16cd459.jpg" alt="">
                                                        <div>
                                                            <span><?= $userinfor['fullname'] ?></span>
                                                            <span><?= $userinfor['email'] ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td id ='list-chucvu'>
                                                <?php 
                                                    if($userinfor['id_role'] == 1)
                                                        echo "Khách Hàng";
                                                    else if($userinfor['id_role'] == 2)
                                                        echo "Nhân Viên";
                                                ?>
                                                </td>
                                                <td><?= $userinfor['username'] ?></td>
                                                <td><?= $userinfor['sdt'] ?></td>
                                                <?php $param = json_encode(array('id_user'=>$userinfor['id_user'],'id_role'=>$userinfor['id_role'],'fullname'=>$userinfor['fullname'],'username'=>$userinfor['username'],'sdt' => $userinfor['sdt'],'email' => $userinfor['email'],'address'=> $userinfor['address'],'cmnd'=>$userinfor['cmnd'],'page'=>$page,'type'=>$type));?>
                                                <td class="see-edit-delete">
                                                    <a href="#" class="link-see bd-rd-rem" onclick='showModalSeeUser(<?php echo $param?>)'>
                                                        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="eye" class="svg-inline--fa fa-eye fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19.5px" height="19.5px">
                                                            <path class="icon-see-vector-only" fill="currentColor" d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                                        </svg>
                                                    </a>
                                                    <!-- <a href="#" class="link-edit bd-rd-rem" onclick="showModalEdituser()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19.5px" height="19.5px" viewBox="0 0 24 24" version="1.1">										
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">											
                                                                <rect x="0" y="0" width="24" height="24"></rect>											
                                                                <path class="icon-edit-vector-first" d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) " opacity="0.9"></path>											
                                                                <path class="icon-edit-vector-last" d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.5"></path>										
                                                            </g>									
                                                        </svg>
                                                    </a> -->
                                                    <a id = "resetpassword" class="link-change-password bd-rd-rem" onclick='showModalComfirmPass(<?php echo $param?>)'>
                                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="19.5px" height="19.5px" viewBox="0 0 122.879 118.662" enable-background="new 0 0 122.879 118.662" xml:space="preserve">
                                                            <g>
                                                                <path class="icon-change-vector-last" fill-rule="evenodd" clip-rule="evenodd" d="M43.101,54.363h4.138v-8.738c0-4.714,1.93-8.999,5.034-12.105v-0.004 c3.105-3.105,7.392-5.034,12.108-5.034c4.714,0,8.999,1.929,12.104,5.034l0.004,0.004c3.104,3.105,5.034,7.392,5.034,12.104v8.738 l3.297,0.001c0.734,0,1.335,0.601,1.335,1.335v28.203c0,0.734-0.602,1.335-1.336,1.335H43.101c-0.734,0-1.336-0.602-1.336-1.335 V55.698C41.765,54.964,42.366,54.363,43.101,54.363L43.101,54.363z M16.682,22.204c-1.781,2.207-3.426,4.551-5.061,7.457 c-5.987,10.645-8.523,22.731-7.49,34.543c1.01,11.537,5.432,22.827,13.375,32.271c2.853,3.392,5.914,6.382,9.132,8.968 c11.112,8.935,24.276,13.341,37.405,13.216c13.134-0.125,26.209-4.784,37.145-13.981c3.189-2.682,6.179-5.727,8.915-9.13 c6.396-7.957,10.512-17.29,12.071-27.138c1.532-9.672,0.595-19.829-3.069-29.655c-3.487-9.355-8.814-17.685-15.775-24.206 C96.695,8.333,88.593,3.755,79.196,1.483c-2.943-0.712-5.939-1.177-8.991-1.374c-3.062-0.197-6.193-0.131-9.401,0.224 c-2.011,0.222-3.459,2.03-3.238,4.041c0.222,2.01,2.03,3.459,4.04,3.237c2.783-0.308,5.495-0.366,8.141-0.195 c2.654,0.171,5.23,0.568,7.731,1.174c8.106,1.959,15.104,5.914,20.838,11.288c6.138,5.751,10.847,13.125,13.941,21.427 c3.212,8.613,4.035,17.505,2.696,25.959c-1.36,8.589-4.957,16.739-10.553,23.699c-2.469,3.071-5.121,5.78-7.912,8.127 c-9.591,8.067-21.031,12.153-32.502,12.263c-11.473,0.109-23.001-3.762-32.764-11.61c-2.895-2.328-5.621-4.983-8.129-7.966 c-6.917-8.224-10.771-18.092-11.655-28.202c-0.908-10.375,1.317-20.988,6.572-30.331c1.586-2.82,3.211-5.071,5.013-7.241 l0.533,14.696c0.071,2.018,1.765,3.596,3.782,3.524s3.596-1.765,3.524-3.782l-0.85-23.419c-0.071-2.019-1.765-3.596-3.782-3.525 c-0.126,0.005-0.25,0.016-0.372,0.032v-0.003L3.157,16.715c-2.001,0.277-3.399,2.125-3.122,4.126 c0.276,2.002,2.124,3.4,4.126,3.123L16.682,22.204L16.682,22.204L16.682,22.204z M53.899,54.363h20.963v-8.834 c0-2.883-1.18-5.504-3.077-7.403l-0.002,0.001c-1.899-1.899-4.521-3.08-7.402-3.08c-2.883,0-5.504,1.18-7.404,3.078 c-1.898,1.899-3.077,4.521-3.077,7.404V54.363L53.899,54.363L53.899,54.363z M64.465,69.795l2.116,9.764l-5.799,0.024l1.701-9.895 c-1.584-0.509-2.733-1.993-2.733-3.747c0-2.171,1.76-3.931,3.932-3.931c2.17,0,3.931,1.76,3.931,3.931 C67.612,67.845,66.261,69.433,64.465,69.795L64.465,69.795L64.465,69.795z"/>
                                                            </g>
                                                        </svg>
                                                    </a>
                                                    <a class="link-delete bd-rd-rem" onclick='showModalDeleteUser(<?php echo $param?>,this.parentNode.parentNode.rowIndex)'>
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
                                </table>
                            </div>
                            <ul class="list-user-page">
                                <div>
                                    <li class="arrow-back-list-user">
                                    <a <?php if($page == 1)
                                                echo "";
                                            else
                                                echo "href=./listuser.php?page=1";               
                                        ?>>
                                        <div><i class="fas fa-angle-double-left"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-back-list-user">
                                        <a <?php if($previous<1)
                                                echo "";
                                            else
                                                echo "href=./listuser.php?page=".$previous;  
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
                                                echo "href=./listuser.php?page=".$i;  
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
                                                echo "href=./listuser.php?page=".$next;               
                                        ?>>
                                        <div><i class="fas fa-angle-right"></i></div>
                                    </a>
                                    </li>
                                    <li class="arrow-arrive-list-user">
                                    <a <?php if($page == $pages)
                                                echo "";
                                            else
                                                echo "href=./listuser.php?page=".$pages;               
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
            <h2 class="see-user">Thông tin người dùng hệ thống</h2>
            <form action="POST">
                <div class="form-input-see">
                    <label>Họ tên</label>
                    <h6 class="h6-modal" id = 'modal-name'></h6>
                </div>
                <div class="form-input-see">
                    <label>Chức vụ</label>
                    <h6 class="h6-modal" id = 'modal-Chucvu'></h6>
                </div>
                <div class="form-input-see">
                    <label>CCCD/CMND</label>
                    <h6 class="h6-modal" id = 'modal-CMND'></h6>
                </div>
                <div class="form-input-see">
                    <label>Số điện thoại</label>
                    <h6 class="h6-modal" id = 'modal-SDT'></h6>
                </div>
                <div class="form-input-see">
                    <label>Email</label>
                    <h6 class="h6-modal" id = 'modal-email'></h6>
                </div>
                <div class="form-input-see">
                    <label>Địa chỉ</label>
                    <h6 class="h6-modal" id = 'modal-diachi'></h6>
                </div>
                <div class="form-input-see">
                    <label>Tên đăng nhập</label>
                    <h6 class="h6-modal" id = 'modal-username'></h6>
                </div>
            </form>
        </div>
    </div>


    <div class="modal-edit-user js-modal-edit-user">
        <div class="modal-edit-detail js-modal-edit-detail">
            <div class="modal-close-edit" onclick="closeModalEdituser()">
                <i class="fas fa-times close-edit-user"></i>
            </div>
            <h2 class="see-user">Chỉnh sửa thông tin nhân viên</h2>
            <form action="POST">
                <div class="infor-edit">
                    <div>
                        <div class="form-input-edit">
                            <label><script> </script></label>
                            <input class="bd-rd-rem" id="name" name="name" type="text" placeholder="Họ tên">
                            <div class="error-username"></div>
                        </div>
                        <div class="form-input-edit">
                            <label>Phòng ban</label>
                            <select name="phongban">
                                <option value="1">&nbsp Kinh tế</option>
                                <option value="1">&nbsp Kinh tế</option>
                            </select>
                            <div class="error-username"></div>
                        </div>
                        <div class="form-input-edit">
                            <label>Chức vụ</label>
                            <select>
                                <option value="1" selected disabled>&nbsp Chọn chức vụ</option>
                                <option value="1">&nbsp Trưởng phòng</option>
                            </select>                                    
                            <div class="error-username"></div>
                        </div>
                        <div class="form-input-edit">
                            <label>CCCD/CMND</label>
                            <input class="bd-rd-rem" id="name" type="text" placeholder="CCCD">
                            <div class="error-username"></div>
                        </div>
                    </div>
                    <div>
                        <div class="form-input-edit">
                            <label>Số điện thoại</label>
                            <input class="bd-rd-rem" id="name" type="text" placeholder="SĐT">
                            <div class="error-username"></div>
                        </div>
                        <div class="form-input-edit">
                            <label>Email</label>
                            <input class="bd-rd-rem" id="name" type="text" name="email"placeholder="Email">
                            <div class="error-username"></div>
                        </div>
                        <div class="form-input-edit">
                            <label>Địa chỉ</label>
                            <input class="bd-rd-rem" id="name" type="text" placeholder="Địa chỉ">
                            <div class="error-username"></div>
                        </div>
                        <div class="form-input-edit">
                            <label>Tên đăng nhập</label>
                            <input class="bd-rd-rem" id="name" type="text" placeholder="Tên đăng nhập" disabled>
                            <div class="error-username"></div>
                        </div>
                    </div>
                </div>
                <div class="btn-edit">
                    <div class="btn-save-edit">
                        <button type="button" onclick="showModalSaveInfor()">Lưu</button>
                    </div>
                    <div class="modal-save-infor js-modal-save-infor">
                        <div class="modal-save"> 
                            <h4>Bạn muốn thay đổi thông tin của </h4>
                            <h6>Lương Minh Quang</h6>
                            <div class="btn-infor-save">
                                <div class="btn-no-infor">
                                    <button type="button" onclick="closeModalSaveinfor()">Hủy bỏ</button>
                                </div>
                                <div class="btn-yes-infor">
                                    <button type="submit">Đồng ý</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-confirm-password js-modal-confirm-password">
        <div class="modal-confirm js-modal-confirm"> 
            <h4>Bạn muốn đổi lại mật khẩu của nhân viên</h4>
            <h6 id = "resetpassword-user"></h6>
            <div>
                <div class="btn-close-password">
                    <button type="button" onclick="closeModalComfirmPass()">Hủy</button>
                </div>
                <div class="btn-change-password">
                    <button id = "btb-resetpw" name = "reset-button"type="button" onclick="respass_modal()">Đổi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-delete-user js-modal-delete-user">
        <div class="modal-delete">
            <h4>Bạn có muốn xóa thông tin nhân viên</h4>
            <h6 id = 'modal-delete'>Lương Minh Quang</h6>
            <div class="btn-infor-save">
                <div class="btn-no-infor">
                    <button type="button" onclick="closeModalDeleteuser()">Hủy</button>
                </div>
                <div class="btn-yes-infor">
                    <button type="button" onclick="del_modal()">Xóa</button>
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