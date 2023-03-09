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
    $conn  = open_database();
    $array_soluong = []; 
    $longtext ='';
    if(isset($_POST['adduser']) && isset($_POST['sdt']) && isset($_POST['cmnd']) && isset($_POST['hoten']) && isset($_POST['idphong']) && isset($_POST['username']))
    {
        date_default_timezone_set("asia/ho_chi_minh");
        $username = $_POST['username'];
        $hoten = $_POST['hoten'];
        $sdt = $_POST['sdt'];
        $cmnd = $_POST['cmnd'];
        $idphong = $_POST['idphong'];
        $gio = date("h:ia");
        $ngay = date("Y-m-d");
        $ngay = date("d/m/Y", strtotime($ngay));
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
        $longtext = rtrim($longtext, "/ ");
        $sql = "INSERT INTO dondatphong(username,hoten,sdt,cmnd,id_phong,monan,ngay,gio)
        VALUES ('$username','$hoten','$sdt','$cmnd','$idphong','$longtext','$ngay','$gio')";
        $sql2 = "UPDATE phong SET active = 1 WHERE id_phong = '$idphong'";
        if(phong_duoc_dat($idphong) || (phong_co_nguoi($idphong)==false) || da_dat_phong($username))
        {
            $link = da_dat_phong($username)?"?idphong=".$idphong."&checkdat=4":"?idphong=".$idphong."&checkdat=3";
            header("Location: ./datphong.php$link");
        }
        else
        {
            if(mysqli_query($conn,$sql))
            {
                $link = "?idphong=".$idphong."&checkdat=1";
                if(mysqli_query($conn,$sql2))
                {
                    header("Location: ./datphong.php$link");
                }
                else
                {
                    echo "Lỗi Đột Xuất";
                    exit;
                }
            }
            else
            {
                $link = "?idphong=".$idphong."&checkdat=0";
                header("Location: ./datphong.php$link");
            }
        }
    }
    mysqli_close($conn);
?>