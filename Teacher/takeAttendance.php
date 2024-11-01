<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$classId = isset($_GET['userId']) ? $_GET['userId'] : '0';

function getCourseNames($conn) {
    $sql = "SELECT * name FROM lopHoc WHERE giangvienId='$userId'";
    $result = $conn->query($sql);

    $courseNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courseNames[] = $row;
        }
    }
    return $courseNames;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);

    if (!empty($attendanceData)) {
        foreach ($attendanceData as $data) {
            $sinhVienId = $data['sinhVienId'];
            $attendanceStatus = $data['attendanceStatus'];
            $course = $data['course'];
            $unit = $data['unit'];
            $date = date("Y-m-d"); 

            $sql = "INSERT INTO diemDanh(studentRegistrationNumber, course, unit, attendanceStatus, dateMarked)  
                    VALUES ('$sinhVienId', '$course', '$unit', '$attendanceStatus', '$date')";
            
            if ($conn->query($sql) === TRUE) {
                $message = " Attendance Recorded Successfully For $course : $unit on $date";
            } else {
                echo "Error inserting attendance data: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "No attendance data received.<br>";
    }
} else {
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="../admin/img/logo/attnlg.png" rel="icon">
  <title>Giảng viên</title>
  <link rel="stylesheet" href="css/styles.css">
  <script defer src="face-api.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>


<body>

<?php include 'includes/topbar.php';?>
    <section class="main">
        <?php include 'includes/sidebar.php';?>
        
    <div class="main--content">
    <div id="messageDiv" class="messageDiv"  style="display:none;" > </div>
    <form class="lecture-options" id="selectForm">
    <select required name="course" id="courseSelect"  onChange="updateTable()">
        <option value="" selected>Lớp học</option>
        <?php
       
        $courseNames = getCourseNames($conn);
        foreach ($courseNames as $course) {
            echo '<option value="' . $course["id"] . '">' . $course["tenLopHoc"] . '</option>';
        }
        ?>
    </select>

    <select required name="unit" id="unitSelect" onChange="updateTable()">
        <option value="" selected>Buổi học</option>
        <?php
        $unitNames = getUnitNames($conn);
        foreach ($unitNames as $unit) {
            echo '<option value="' . $unit["unitCode"] . '">' . $unit["name"] . '</option>';
        }
        ?>
    </select>
    
    <select required name="venue" id="venueSelect" onChange="updateTable()">
        <option value="" selected>Select Venue</option>
        <?php
        $venueNames = getVenueNames($conn);
        foreach ($venueNames as $venue) {
            echo '<option value="' . $venue["className"] . '">' . $venue["className"] . '</option>';
        }
        ?>
    </select>
   
    </form>
    <div class="attendance-button">
      <button id="startButton" class="add" >Launch Facial Recognition</button>
      <button id="endButton"class="add" style="display:none">End Attendance Process</button>
      <button id="endAttendance" class="add" >END Attendance Taking</button>
    </div>
   
    <div class="video-container" style="display:none;">
        <video  id="video" width="600" height="450" autoplay></video>
        <canvas id="overlay"></canvas>
    </div>

    <div class="table-container">

                <div id="studentTableContainer" >
               

                    
                </div>
                
    </div>

</div>
</section>
    <script>

 </script>
   
<script  src="script.js"></script>
<script  src="../admin/javascript/main.js"></script>





</body>
</html>