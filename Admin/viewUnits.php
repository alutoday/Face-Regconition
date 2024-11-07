<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

$classId = isset($_GET['classId']) ? $_GET['classId'] : '0';

$sql = "SELECT l.tenLopHoc, b.gioBatDau, b.gioKetThuc 
        FROM lophoc l
        JOIN buoihoc b ON l.id = b.lopHocId
        WHERE l.id = '$classId'";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $className = $row['tenLopHoc'];
    $gioBatDau = $row['gioBatDau'];
    $gioKetThuc = $row['gioKetThuc'];
} else {
    echo "Không tìm thấy lớp học.";
    exit;
}

$aSql = $conn->prepare("SELECT 
                        (SELECT COUNT(*) FROM diemdanh d 
                         JOIN sinhvien s ON d.sinhVienId = s.id 
                         WHERE d.buoiHocId IN (SELECT id FROM buoihoc WHERE lopHocId = ?) 
                         AND d.gioDiemDanh <= ?) AS totalOnTime,
                         
                        (SELECT COUNT(*) FROM diemdanh d 
                         JOIN sinhvien s ON d.sinhVienId = s.id 
                         WHERE d.buoiHocId IN (SELECT id FROM buoihoc WHERE lopHocId = ?) 
                         AND d.gioDiemDanh > ? AND d.gioDiemDanh < ?) AS totalLate,
                         
                        (SELECT COUNT(*) FROM sinhvien s 
                         WHERE s.khoaId = (SELECT khoaId FROM lophoc WHERE id = ?) 
                         AND s.id NOT IN (SELECT d.sinhVienId FROM diemdanh d 
                                         WHERE d.buoiHocId IN (SELECT id FROM buoihoc WHERE lopHocId = ?))) AS totalAbsent");

$aSql->bind_param("sssssss", $classId, $gioBatDau, $classId, $gioKetThuc, $gioKetThuc, $classId, $classId);
$aSql->execute();
$result1 = $aSql->get_result();

if ($result1 && $result1->num_rows > 0) {
    $row = $result1->fetch_assoc();
    $totalOnTime = $row['totalOnTime'];
    $totalLate = $row['totalLate'];
    $totalAbsent = $row['totalAbsent'];
} else {
    echo "Không tìm thấy lớp học.";
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
                <h2 class="section--title">Danh sách buổi học - <?php echo $className; ?></h2>
            </div>
            <div class="table-container">
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                                <th>Tổng SV đúng giờ</th>
                                <th>Tổng SV trễ</th>
                                <th>Tổng SV vắng mặt</th>
                                <th>Chi tiết điểm danh</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            echo "<tr>";
                            echo "<td>" . $gioBatDau . "</td>";
                            echo "<td>" . $gioKetThuc . "</td>"; 
                            echo "<td>" . $totalOnTime . "</td>"; 
                            echo "<td>" . $totalLate. "</td>"; 
                            echo "<td>" . $totalAbsent . "</td>"; 

                            echo "<td>
                                            <span>
                                                <a href='attendanceDetail.php?classId=" . $classId . "' class='view-students'>
                                                    <button>Chi tiết</button>
                                                </a>
                                            </span>
                                        </td>";
                            echo "</tr>";
                        
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
