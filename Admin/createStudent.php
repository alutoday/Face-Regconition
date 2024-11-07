<?php 
error_reporting(0);
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

function checkIdExists($conn, $id) {
    $query = mysqli_query($conn, "SELECT * FROM SinhVien WHERE id='$id'");
    return mysqli_num_rows($query) > 0; 
}

// Adding a student
if (isset($_POST['addStudent'])) {
    $id = $_POST["id"];
    $hoTen = $_POST["hoTen"];
    $khoaID = $_POST["khoaId"]; 
    $email = $_POST["email"];
    $dienThoai = $_POST["dienThoai"];
    $gioiTinh = $_POST["gioiTinh"];
    $ngayTao = date("Y-m-d");
    $ngayCapNhat = date("Y-m-d");
    $matKhau = md5($email); 

    $query = mysqli_query($conn, "SELECT * FROM sinhvien WHERE email='$email'");
    $ret = mysqli_fetch_array($query);
    
    if ($ret > 0) { 
        $message = "Sinh viên đã tồn tại";
    } else {
        do {
            $id = "SV" . substr($khoaID, 0, 4) . rand(100, 999);
        } while (checkIdExists($conn, $id)); 

        $query = mysqli_query($conn, "INSERT INTO sinhvien (id, hoTen, khoaId, email, dienthoai, matKhau, gioiTinh, ngayTao, ngayCapNhat) 
        VALUES ('$id', '$hoTen', '$khoaID', '$email', '$dienThoai', '$matKhau', '$gioiTinh', '$ngayTao', '$ngayCapNhat')");
        
        if ($query) {
            $message = "Sinh viên đã được thêm thành công";
        } else {
            $message = "Lỗi trong quá trình thêm sinh viên: " . mysqli_error($conn); 
        }
    }
}

// Updating a student
if (isset($_POST['update'])) {
    $id = $_POST["id"];
    $hoTen = $_POST["hoTen"];
    $khoaID = $_POST["khoaId"]; 
    $email = $_POST["email"];
    $dienThoai = $_POST["dienThoai"];
    $gioiTinh = $_POST["gioiTinh"];
    $ngayCapNhat = date("Y-m-d");
    $matKhau = md5($email); 

    $query = mysqli_query($conn, "UPDATE sinhvien SET hoTen='$hoTen', khoaId='$khoaID', email='$email', dienthoai='$dienThoai', gioiTinh='$gioiTinh', matKhau='$matKhau', ngayCapNhat='$ngayCapNhat' WHERE id='$id'");
    
    if ($query) {
        echo "<script type='text/javascript'>window.location = 'createStudents.php'</script>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred!</div>";
    }
}

// Deleting a student
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $id = $_GET['Id'];
    
    $query = mysqli_query($conn, "DELETE FROM sinhvien WHERE id='$id'");

    if ($query) {
        echo "<script type='text/javascript'>window.location = 'createStudents.php'</script>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="img/logo/attnlg.png" rel="icon">
  <title>Quản lí sinh viên</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
  <script src="./javascript/addStudent.js"></script>   
</head>
<body>

<?php include 'includes/topbar.php'; ?>
<section class="main">
<?php include "Includes/sidebar.php"; ?>
   <div class="main--content"> 
      <div id="overlay"></div>
      <div id="messageDiv" class="messageDiv" style="display:none;"></div>

      <div class="table-container">
          <div class="title" id="addStudent">
              <h2 class="section--title">Sinh viên</h2>
              <button class="add"><i class="ri-add-line"></i>Thêm sinh viên</button>
          </div>

          <div class="table">
              <table>
                  <thead>
                      <tr>
                          <th>MSSV</th>
                          <th>Họ tên</th>
                          <th>Khoa</th>
                          <th>Điện thoại</th>
                          <th>Email</th>
                          <th>Giới tính</th>
                          <th>Settings</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                      $sql = "SELECT sinhvien.*, khoa.tenKhoa FROM sinhvien LEFT JOIN khoa ON khoa.id = sinhvien.khoaId";
                      $result = $conn->query($sql);
                      
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              echo "<tr>";
                              echo "<td>" . $row["id"] . "</td>";
                              echo "<td>" . $row["hoTen"] . "</td>";
                              echo "<td>" . $row["tenKhoa"] . "</td>";
                              echo "<td>" . $row["dienThoai"] . "</td>";
                              echo "<td>" . $row["email"] . "</td>";
                              echo "<td>" . $row["gioiTinh"] . "</td>";
                              echo "<td><span><i class='ri-edit-line edit'></i><i class='ri-delete-bin-line delete'></i></span></td>";
                              echo "</tr>";
                          }
                      } else {
                          echo "<tr><td colspan='7'>Không có sinh viên nào</td></tr>";
                      }
                      ?>     
                  </tbody>
              </table>
          </div>
      </div>

      <div class="formDiv--" id="addStudentForm" style="display:none;"> 
          <form method="post">
              <div style="display:flex; justify-content:space-around;">
                  <div class="form-title">
                      <p>Thêm sinh viên</p>
                  </div>
                  <div>
                      <span class="close">&times;</span>
                  </div>
              </div>
              <div>
                  <input type="text" name="hoTen" placeholder="Họ tên" required>
                  <select required name="khoaId">
                      <option value="" selected>Chọn khoa</option>
                      <?php
                      $facultyNames = getFacultyNames($conn);
                      foreach ($facultyNames as $faculty) {
                          echo '<option value="' . $faculty["id"] . '">' . $faculty["tenKhoa"] . '</option>';
                      }
                      ?>
                  </select>
                  <input type="email" name="email" placeholder="Email" required>
                  <input type="text" name="dienThoai" placeholder="Điện thoại" required>
                  <select required name="gioiTinh">
                      <option value="" selected>Chọn giới tính</option>
                      <option value="Nam">Nam</option>
                      <option value="Nữ">Nữ</option>
                  </select>
                  <br/>
                  <button type="submit" name="addStudent">Save Student</button>
              </div>
          </form>
      </div>
   </div>
</section>

<script src="javascript/main.js"></script>
<script>
    const addStudent = document.getElementById('addStudent');
    const addStudentForm = document.getElementById("addStudentForm");
    const overlay = document.getElementById("overlay");

    addStudent.addEventListener("click", function() {
        addStudentForm.style.display = "block";
        overlay.style.display = "block";
        document.body.style.overflow = 'hidden'; 
    });

    overlay.addEventListener("click", function() {
        addStudentForm.style.display = "none";
        overlay.style.display = "none";
        document.body.style.overflow = 'auto'; 
    });

    document.querySelector(".close").addEventListener("click", function() {
        addStudentForm.style.display = "none";
        overlay.style.display = "none";
        document.body.style.overflow = 'auto'; 
    });
</script>
</body>
</html>
