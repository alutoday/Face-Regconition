<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

error_reporting(E_ALL); 
ini_set('display_errors', 1); 

function getFacultyNames($conn) {
    $sql = "SELECT * FROM khoa";
    $result = $conn->query($sql);

    $facultyNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $facultyNames[] = $row;
        }
    }

    return $facultyNames;
}

function checkIdExists($conn, $id) {
    $query = mysqli_query($conn, "SELECT * FROM giangvien WHERE id='$id'");
    return mysqli_num_rows($query) > 0; 
}

if (isset($_POST["addLecture"])) {
    $hoTen = $_POST["hoTen"];
    $khoaID = $_POST["khoaId"]; 
    $email = $_POST["email"];
    $dienThoai = $_POST["dienThoai"];
    $ngayTao = date("Y-m-d");
    $ngayCapNhat = date("Y-m-d");
    $matKhau = md5($email); 

    $query = mysqli_query($conn, "SELECT * FROM giangvien WHERE email='$email'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) { 
        $message = "Giảng viên đã tồn tại";
    } else {
        do {
            $id = "GV" . substr($khoaID, 0, 4) . rand(100, 999);
        } while (checkIdExists($conn, $id)); 

        $query = mysqli_query($conn, "INSERT INTO giangvien (id, hoTen, khoaId, email, dienthoai, matKhau, ngayTao, ngayCapNhat) 
        VALUES ('$id', '$hoTen', '$khoaID', '$email', '$dienThoai', '$matKhau', '$ngayTao','$ngayCapNhat')");
        
        if ($query) {
            $message = "Giảng viên đã được thêm thành công";
        } else {
            $message = "Lỗi trong quá trình thêm Giảng viên: " . mysqli_error($conn); 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/logo/attnlg.png" rel="icon">
    <title>Quản lí Giảng viên</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
    <script src="./javascript/addStudent.js"></script>   
    <script src="https://unpkg.com/face-api.js"></script>
</head>
<body>
<?php include "Includes/topbar.php"; ?>
<section class="main">
    <?php include "Includes/sidebar.php"; ?>
    <div class="main--content"> 
        <div id="overlay"></div>
        <div id="messageDiv" class="messageDiv" style="display:none;"></div>

        <div class="table-container" >
            <a href="#add-form" style="text-decoration:none;"> 
                <div class="title" id="addLecture">
                    <h2 class="section--title">Giảng viên</h2>
                    <button class="add"><i class="ri-add-line"></i>Thêm Giảng viên</button>
                </div>
            </a>
            <div class="table">
            <table>
                <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Khoa</th>                                 
                            <th>Email</th>
                            <th>Điện thoại</th>                                
                            <th>Settings</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql = "SELECT giangvien.*, khoa.* FROM giangvien  LEFT JOIN khoa  ON khoa.id = giangvien.khoaId"; 
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["hoTen"] . "</td>";
                                echo "<td>" . $row["tenKhoa"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["dienThoai"] . "</td>";
                                echo "<td><span><i class='ri-edit-line edit'></i><i class='ri-delete-bin-line delete'></i></span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Không tìm thấy bản ghi</td></tr>";
                        }
                    ?>                     
                    </tbody>
                </table>
            </div>
        </div>

        <div id="addLectureForm" style="display:none;">
            <form method="POST" action="" name="addLecture" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p>Thêm Giảng viên</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>        
                <input type="text" name="hoTen" placeholder="Tên" required>
                <select required name="khoaId"> 
                    <option value="" selected>Chọn Khoa</option>
                    <?php
                    $facultyNames = getFacultyNames($conn);
                    foreach ($facultyNames as $faculty) {
                        echo '<option value="' . $faculty["id"] . '">' . $faculty["tenKhoa"] . '</option>';
                    }
                    ?>
                </select>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="dienThoai" placeholder="Điện thoại" required>
                <input type="submit" class="submit" value="Lưu Giảng viên" name="addLecture">
            </form>		  
        </div>
    </div>
</section>

<script src="javascript/main.js"></script>
<script src="javascript/addLecture.js"></script>
<script>
    <?php if (isset($message)) {
        echo "alert('" . $message . "');";
    } ?>
</script>
</body>
</html>
