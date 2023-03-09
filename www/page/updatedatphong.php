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
    $conn  = open_database();
    $array_soluong = []; 
    $longtext ='';
    if(isset($_POST['updatephong']) && isset($_POST['id_dondat']))
    {
        $hoten = $_POST['name'];
        $sdt = $_POST['sdt'];
        $cmnd = $_POST['cmnd'];
        $id_dondat = $_POST['id_dondat'];
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
                if($_POST["soluong?$id"] ==0)
                    continue;
                $soluongid = $_POST["soluong?$id"]."?"."$id"."?"."$tenmon";
                array_push($array_soluong, $soluongid);
            }
        }
        foreach($array_soluong as $data) 
        {
            $longtext = $longtext.$data.'/';
        }
        $longtext = rtrim($longtext, "/ ");
        $sql = "UPDATE dondatphong
        SET hoten = '$hoten', sdt = '$sdt',cmnd = '$cmnd',sdt = '$sdt',monan = '$longtext'
        WHERE id_dondat = '$id_dondat'";

        if(mysqli_query($conn,$sql))
        {
            $link ="?checkdat=1";
            header("Location: ./nhanvienduyet.php$link");
        }
        else
        {
            $link ="?checkdat=0";
            header("Location: ./nhanvienduyet.php$link");
        }
    }
    mysqli_close($conn);
?>