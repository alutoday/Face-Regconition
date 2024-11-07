<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="img/logo/attnlg.png" rel="icon">
  <title>Admin</title>
  <link rel="stylesheet" href="css/styles.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/topbar.php'; ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <div class="overview">
                <div class="title">
                    <h2 class="section--title">Tổng quan</h2>
                </div>
                <div class="cards">
                    <div class="card card-1">
                        <?php 
                        $query1 = mysqli_query($conn, "SELECT * FROM sinhvien");                       
                        $students = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Sinh viên</h5>
                                <h1><?php echo $students; ?></h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                    </div>
                    <div class="card card-1">
                        <?php 
                        $query1 = mysqli_query($conn, "SELECT * FROM lophoc");                       
                        $unit = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Lớp học</h5>
                                <h1><?php echo $unit; ?></h1>
                            </div>
                            <i class="ri-file-text-line card--icon--lg"></i>
                        </div>
                    </div>
                    <div class="card card-1">
                        <?php 
                        $query1 = mysqli_query($conn, "SELECT * FROM giangvien");                       
                        $totalLecture = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Giảng viên</h5>
                                <h1><?php echo $totalLecture; ?></h1>
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="table-container ">
                <a href="createTeacher.php" style="text-decoration:none;"> 
                    <div class="title">
                        <h2 class="section--title">Giảng viên</h2>
                        <button class="add"><i class="ri-terminal-line"></i>Cập nhật</button>                        
                    </div>
                </a>
                <div class="table table-sizelimit">
                    <table>
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Khoa</th>
                                <th>Email</th>
                                <th>Điện thoại</th>                                
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql = "SELECT l.*, f.tenKhoa FROM giangvien l LEFT JOIN khoa f ON l.khoaId = f.id GROUP BY tenKhoa";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["hoTen"] . "</td>";
                                    echo "<td>" . $row["tenKhoa"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td>" . $row["dienThoai"] . "</td>";
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

            <div class="table-container">
                <a href="createStudent.php" style="text-decoration:none;"> 
                    <div class="title">
                        <h2 class="section--title">Sinh viên</h2>
                        <button class="add"><i class="ri-terminal-line"></i>Cập nhật</button>                        
                    </div>
                </a>
                <div class="table table-sizelimit">
                    <table>
                        <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Họ tên</th>
                                <th>Khoa</th>
                                <th>Email</th>
                                <th>Điện thoại</th>    
                                <th>Giới tính</th>                          
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql = "SELECT sv.*, k.tenKhoa FROM sinhvien sv LEFT JOIN khoa k ON sv.khoaId = k.id";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . $row["hoTen"] . "</td>";
                                    echo "<td>" . $row["tenKhoa"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td>" . $row["dienThoai"] . "</td>";
                                    echo "<td>" . $row["gioiTinh"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Không tìm thấy bản ghi</td></tr>";
                            }
                        ?>     
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-container">
                <a href="createClass.php" style="text-decoration:none;">
                    <div class="title">
                        <h2 class="section--title">Lớp học</h2>
                        <button class="add"><i class="ri-terminal-line"></i>Cập nhật</button>                        
                    </div>
                </a>
                <div class="table table-sizelimit">
                    <table>
                        <thead>
                            <tr>
                                <th>Tên lớp học</th>
                                <th>Giảng viên</th>
                                <th>Ngày tạo</th>
                                <th>Tổng sinh viên</th>
                                <th>Tổng buổi học</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                          $sql = "SELECT 
                          lh.id AS class_id,
                          lh.tenLopHoc AS tenlophoc,
                          gv.hoTen AS ten_giangvien,
                          lh.ngayTao AS date_created,
                          COUNT(DISTINCT sv.id) AS total_students,
                          COUNT(DISTINCT bh.id) AS total_units
                            FROM lophoc lh
                            LEFT JOIN giangvien gv ON lh.giangvienId = gv.id
                            LEFT JOIN sinhvien_lophoc sl ON lh.id = sl.lopHocId
                            LEFT JOIN sinhvien sv ON sl.sinhVienId = sv.id
                            LEFT JOIN buoihoc bh ON bh.lopHocId = lh.id
                            GROUP BY lh.id, gv.hoTen, lh.ngayTao";

                            $result = $conn->query($sql);

                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["tenlophoc"] . "</td>";
                                    echo "<td>" . $row["ten_giangvien"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td>" . $row["total_students"] . "</td>";
                                    echo "<td>" . $row["total_units"] . "</td>";
                                    echo "<td>
                                            <span>
                                                <a href='viewStudents.php?classId=" . $row['class_id'] . "' class='view-students'>
                                                    <button>Xem sinh viên</button>
                                                </a>
                                            </span>
                                        </td>";
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
        </div>
    </section>
    <script src="javascript/main.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
