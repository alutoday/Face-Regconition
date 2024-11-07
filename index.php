<?php 
include 'Includes/dbcon.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="admin/img/logo/attnlg.png" rel="icon">
    <title>Hệ thống điểm danh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/loginStyle.css">
</head>
<body>
<div class="container" id="signin">
    <h1>ĐĂNG NHẬP</h1>
    <div id="messageDiv" class="messageDiv" style="display:none;"></div>

    <form method="post" action="">
         <select required name="userType">
            <option value="">--Chọn vai trò--</option>
            <option value="Administrator">Administrator</option>
            <option value="Teacher">Giảng viên</option>
            <option value="Student">Sinh viên</option> 
         </select>
        <input type="email" name="email" placeholder="example@gmail.com" required>
        <input type="password" name="password" placeholder="password" required>       
        <input type="submit" class="btn-login" value="Login" name="login" />
    </form>     
</div> 
<script>
function showMessage(message) {
  var messageDiv = document.getElementById('messageDiv');
  messageDiv.style.display="block";
  messageDiv.innerHTML = message;x
  messageDiv.style.opacity = 1;
  setTimeout(function() {
    messageDiv.style.opacity = 0;
  }, 5000);
}
</script> 

<?php
if (isset($_POST['login'])) {
    $userType = $_POST['userType'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); 

    if ($userType == "Administrator") {
        $query = "SELECT * FROM admin WHERE email = '$email' AND matKhau = '$password'";
        $rs = $conn->query($query);
        
        if ($rs && $rs->num_rows > 0) {
            $rows = $rs->fetch_assoc();
            $_SESSION['userId'] = $rows['id'];
            $_SESSION['hoTen'] = $rows['hoTen']; 
            $_SESSION['email'] = $rows['email']; 

            echo "<script type='text/javascript'>window.location = ('Admin/index.php');</script>";
        } else {
            $message = "Invalid Username/Password!";
            echo "<script>showMessage('" . $message . "');</script>";
        }
    } else if ($userType == "Teacher") {
        $query = "SELECT * FROM giangvien WHERE email = '$email' AND matKhau = '$password'";
        $rs = $conn->query($query);
        
        if ($rs && $rs->num_rows > 0) {
            $rows = $rs->fetch_assoc();
            $_SESSION['userId'] = $rows['id'];

            echo "<script type='text/javascript'>window.location = ('Teacher/takeAttendance.php?userId=$userId');</script>";
        } else {
            $message = "Invalid Username/Password!";
            echo "<script>showMessage('" . $message . "');</script>";
        }
    } else if ($userType == "Student") {
        $query = "SELECT * FROM sinhvien WHERE email = '$email' AND password = '$password'";
        $rs = $conn->query($query);
        
        if ($rs && $rs->num_rows > 0) {
            $rows = $rs->fetch_assoc();
            $_SESSION['userId'] = $rows['id'];

            echo "<script type='text/javascript'>window.location = ('Student/takeAttendance.php');</script>";
        } else {
            $message = "Invalid Username/Password!";
            echo "<script>showMessage('" . $message . "');</script>";
        }
    }
}
?>
</body>
</html>
