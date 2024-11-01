<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

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
function getLectureNames($conn) {
    $sql = "SELECT * FROM giangvien";
    $result = $conn->query($sql);

    $lectureNames = array();  
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lectureNames[] = $row;
        }
    }

    return $lectureNames;
}
function getCourseNames($conn) {
    $sql = "SELECT * FROM lopHoc";
    $result = $conn->query($sql);

    $courseNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courseNames[] = $row;
        }
    }

    return $courseNames;
}

    if (isset($_POST["addCourse"])) {
        $tenLopHoc = $_POST["tenLopHoc"];
        $giangvienId = $_POST["giangvienId"];       
        $ngayTao = date("Y-m-d");
        $ngayCapNhat = date("Y-m-d");
        $query=mysqli_query($conn,"insert into lopHoc(tenLopHoc,giangvienId,ngayTao,ngayCapNhat) 
            value('$tenLopHoc','$giangvienId','$ngayTao','$ngayCapNhat')");
            $message = " Thêm lớp học thành công!";

    }


    if (isset($_POST["addUnit"])) {
        $lopHocId = $_POST["lopHocId"];
        $gioBatDau = date("Y-m-d");
        $gioKetThuc = date("Y-m-d");

        $query=mysqli_query($conn,"insert into buoiHoc(lopHocID,gioBatDau,gioKetThuc) 
        value('$unitName','$unitCode','$courseID','$dateRegistered')");
        $message = "Thêm buổi học thành công";    
    
    }
    if (isset($_POST["addFaculty"])) {
        $tenKhoa = $_POST["tenKhoa"];
        $Id = $_POST["Id"];

        $query=mysqli_query($conn,"select * from khoa where id='$id'");
        $ret=mysqli_fetch_array($query);
            if($ret > 0){ 

                $message = " Đã tồn tại khoa này";}
        else{
            $query=mysqli_query($conn,"insert into khoa(id, tenKhoa) 
            value('$id','$tenKhoa')");
            $message = "Đã thêm khoa này";

        }
       
       
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="img/logo/attnlg.png" rel="icon">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/styles.css">
 <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
 <script src="./javascript/confirmation.js" defer></script>
</head>
<body>
<?php include 'includes/topbar.php'?>
<section class="main">
<?php include 'includes/sidebar.php';?>
 <div class="main--content">
    <div id="overlay"></div>
 <div class="overview">
                <div class="title">
                <h2 class="section--title">Tổng quan</h2>
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Hôm nay</option>
                        <option value="lastweek">Tuần trước</option>
                        <option value="lastmonth">Tháng trước</option>
                        <option value="lastyear">Năm trước</option>
                        <option value="alltime">Tất cả</option>
                    </select>
                </div>
                <div class="cards">
                    <div id="addCourse" class="card card-1">
                        <?php 
                        $query1=mysqli_query($conn,"SELECT * from lopHoc");                       
                        $courses = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                            <button class="add"><i class="ri-add-line"></i>Thêm lớp học</button>
                                <h1><?php echo $courses;?> Lớp học</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>
                       
                    </div>
                    <div class="card card-1" id="addUnit">
                        <?php 
                        $query1=mysqli_query($conn,"SELECT * from buoihoc");                       
                        $unit = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data" >
                            <div class="card--content">
                            <button class="add"><i class="ri-add-line"></i>Thêm buổi học</button>
                                <h1><?php echo $unit;?> Buổi học</h1>
                            </div>
                            <i class="ri-file-text-line card--icon--lg"></i>
                        </div>
                        
                    </div>
                   
                    <div class="card card-1" id="addFaculty">
                        <?php 
                        $query1=mysqli_query($conn,"SELECT * from khoa");                       
                        $faculty = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                            <button class="add"><i class="ri-add-line"></i>Thêm Khoa</button>
                                <h1><?php echo $faculty;?> Khoa </h1> 
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>
                       
                    </div>
                </div>
            </div>
            <div id="messageDiv" class="messageDiv" style="display:none;"></div>

            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">Lớp học</h2>
                </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Tên lớp học</th>
                                <th>Giáo viên</th>
                                <th>Ngày tạo</th>
                                <th>Tổng sinh viên</th>
                                <th>Tổng buối học</th>
                                <th>Sinh viên</th>
                                <th>Buổi học</th>

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
                                                    <button>Chi tiết</button>
                                                </a>
                                            </span>                                             
                                        </td>";
                                    echo "<td>
                                    <span>
                                                <a href='viewUnits.php?classId=" . $row['class_id'] . "' class='view-units'>
                                                    <button>Chi tiết</button>
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
<script src="javascript/addCourse.js"></script>
<script src="javascript/confirmation.js"></script>
<?php if(isset($message)){
    echo "<script>showMessage('" . $message . "');</script>";
} 
?>
</body>
</html>


        
      
