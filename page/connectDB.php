<?php

function open_database(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_sql = "cnpm";
  
    $conn  = new mysqli($servername,$username,$password,$db_sql);
  
    if ($conn  -> connect_errno) {
      die('Connect error:' . $conn  -> connect_error);
    }
    return $conn ;
  }

function verify_password($user, $pass)
{
    $sql = "select * from user where username = '$user'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();
      $hashed_password = $data['password'];
      
      if(!password_verify($pass, $hashed_password))
      {
        return array('code'=>2,'error'=>'Sai mật khẩu');
      }
      else 
        return array('code'=>0,'error'=>'');
    }
    else
    {
        return array('code'=>1,'error'=>'Tài khoản không tồn tại');
    }
    mysqli_close($conn);
}

function validate($user)
{
  $conn = open_database();
  $sql1 = "SELECT * FROM user  WHERE username = '$user'";
  $result1 = $conn->query($sql1);
  if ($result1->num_rows > 0) {
    while($row1 = $result1->fetch_assoc()) {
      $achid = $row1['username'];
      $sql2 = "SELECT * FROM role WHERE username = '$achid'";
      $result2 = $conn->query($sql2);
      if ($result2->num_rows > 0) {
              while($row2 = $result2->fetch_assoc()) {
                  return $row2['id_permisson'];
              }
      }
    }
  }
  mysqli_close($conn);
}

function user($user)
{
  $sql = "select * from user where username = '$user'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_user'=>$data['id_user'],'fullname'=>$data['fullname'],'role'=>$data['id_role'],'username'=>$data['username'],
      'password'=>$data['password'],'cmnd'=>$data['cmnd'],'sdt'=>$data['sdt'],'email'=>$data['email'],'address'=>$data['address'],
      'active'=>$data['active']);
    }
    mysqli_close($conn);
}

function phong($idphong)
{
  $sql = "select * from phong where id_phong = '$idphong'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_phong'=>$data['id_phong'],'tenphong'=>$data['tenphong'],'giaphong'=>$data['giaphong'],'active'=>$data['active'],
      'check_room'=>$data['check_room']);
    }
    mysqli_close($conn);
}
function phonghd($idphonghd)
{
  $sql = "select * from phonghoatdong where id_hoatdong = '$idphonghd'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_hoatdong'=>$data['id_hoatdong'],'username'=>$data['username'],'hoten'=>$data['hoten'],'sdt'=>$data['sdt'],
      'cmnd'=>$data['cmnd'],'id_phong'=>$data['id_phong'],'monan'=>$data['monan'],'ngay'=>$data['ngay'],'gio'=>$data['gio']);
    }
    mysqli_close($conn);
}
function monan($idmon)
{
  $sql = "select * from monan where id_monan = '$idmon'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_monan'=>$data['id_monan'],'hinhanh'=>$data['hinhanh'],'giamon'=>$data['giamon'],'available'=>$data['available'],
      'Tenmon'=>$data['Tenmon'],'phanloai'=>$data['phanloai']);
    }
    mysqli_close($conn);
}
function ds_don_dat($idphong)
{
  $sql = "select * from dondatphong where id_dondat = '$idphong'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return array('id_dondat'=>$data['id_dondat'],'username'=>$data['username'],'hoten'=>$data['hoten'],'sdt'=>$data['sdt'],
      'cmnd'=>$data['cmnd'],'id_phong'=>$data['id_phong'],'monan'=>$data['monan'],'ngay'=>$data['ngay'],'gio'=>$data['gio']);
    }
    mysqli_close($conn);
}
function getData($user)
{
  $sql = "select * from user where username = '$user'";
    $conn = open_database();
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) >=1)
    {
      $stm = $conn->prepare($sql);
      if (!$stm->execute()) {
        return null;
      }

      $result = $stm->get_result();

      $data = $result->fetch_assoc();

      return $data;
    }
    mysqli_close($conn);
}

function is_username_exits($username){
  $sql = "select * from user where username = '$username'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function phong_duoc_dat($idphong){
  $sql = "select * from dondatphong where id_phong = '$idphong'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function phong_co_nguoi($idphong){
  $sql = "select * from phong where id_phong = '$idphong' and active = 0";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}
function da_dat_phong($username){
  $sql = "select * from dondatphong where username = '$username'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);
  
  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}
function is_email_exits($email){
  $sql = "select * from user where email = '$email'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);

  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  $result = $stm->get_result();
  if($result->num_rows > 0){
    return true;
  }
  else{
    return false;
  }
}

function is_password_change($username){
  $sql = "select active from user where username = '$username'";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);

  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
   $result = $stm->get_result();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
          return array('active'=>$row['active']);
    }
  }
}
 

function active_account($username){
  $sql = "update user set active = 1 where username = '$username' and active = 0";
  $mysqli = open_database();

  $stm = $mysqli->prepare($sql);

  if(!$stm->execute()){
    die('Query error: '.$stm->error);
  }
  
  return array('code'=>0,'message'=>'Update thành công');
  mysqli_close($mysqli);
}

function update_password($user,$pass)
{
    
    $encryptpassword = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "update user set password = '$encryptpassword' where username = '$user'";
    $conn = open_database();

    $stm = $conn->prepare($sql);

    if(!$stm->execute()){
      die('Query error: '.$stm->error);
    }
    mysqli_close($conn);
}
?>