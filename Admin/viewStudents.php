<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

$classId = isset($_GET['classId']) ? $_GET['classId'] : '0';

$sql = "SELECT lh.tenLopHoc AS tenlophoc, sv.id AS student_id, sv.hoTen AS student_name, sv.email AS student_email, sv.dienThoai AS student_phone, sv.gioiTinh AS student_gender
        FROM sinhvien_lophoc sl
        LEFT JOIN sinhvien sv ON sl.sinhVienId = sv.id
        LEFT JOIN lophoc lh ON sl.lopHocId = lh.id
        WHERE lh.id = '$classId'";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $className = $row['tenlophoc'];
} else {
    echo "Không tìm thấy sinh viên trong lớp học.";
    exit;
}
?>

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
            <div class="title">
                <h2 class="section--title">Danh sách sinh viên - <?php echo $className; ?></h2>
            </div>
            <div class="table-container">
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Giới tính</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $result->data_seek(0);
                        
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["student_id"] . "</td>";
                            echo "<td>" . $row["student_name"] . "</td>";
                            echo "<td>" . $row["student_email"] . "</td>";
                            echo "<td>" . $row["student_phone"] . "</td>";
                            echo "<td>" . $row["student_gender"] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
