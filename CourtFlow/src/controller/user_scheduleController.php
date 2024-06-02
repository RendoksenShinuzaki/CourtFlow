<?php
session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_POST['pretrialSubmit'])){
    $empId = $_POST['empId'];
    $inboxId = $_POST['docketNumber'];
    $currentDate = date('Y-m-d');
    $PreTrialDate = $_POST['pretrialDate'];
    $PreTrialTimeStart = $_POST['pretrialTimeStart'];
    $PreTrialTimeEnd = $_POST['pretrialTimeEnd'];
    
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileError = $_FILES['file']['error'];
    $folder = "../pages/user/uploads";
    
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trackMessage = "File is now for Scheduled for Pre-Trial";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    
    /*$stmt = $conn->prepare("SELECT * FROM v5_interpreter_scheduling WHERE PreTrialTimeStart=? and PerTrialTimeEnd=?");
    $stmt->bind_param("ss", $PreTrialTimeStart, $PreTrialTimeEnd);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    
    if ($count > 0) {
        $_SESSION['status'] = "Schedule already exist Failed";
        header("Location: ../pages/admin/admin_userManagement.php");
        exit(); // Exit to prevent further execution of the script
    }*/
    
    // Select  all data from inbox_interpreter
    $stmt = $conn->prepare("SELECT * FROM inbox_interpreter WHERE id=?");
    $stmt->bind_param("s", $inboxId);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $SqlResult = $result->fetch_assoc();

    // Transfer data from inbox_interpreter to a new variable
    $V1_CaseId = $SqlResult["V1_CaseId"];
    $V2_CaseId = $SqlResult["V2_CaseId"];
    $V3_CaseId = $SqlResult["V3_CaseId"];
    $V4_CaseId = $SqlResult["CaseId"];
    $docketNumber = $SqlResult["DocketNumber"];
    $fromRTC = $SqlResult["FromEmployee"];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');

    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
            $fileData = file_get_contents($fileTmpName);
            
            $count = $result->num_rows;
            $stmt = $conn->prepare("SELECT * FROM v5_interpreter_scheduling WHERE PreTrialDate = ? AND PreTrialTimeStart = ? AND PreTrialTimeEnd = ?");
            $stmt->bind_param("sss", $PreTrialDate, $PreTrialTimeStart, $PreTrialTimeEnd);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->num_rows;

            if ($count > 0) {
            // Existing schedule found, trigger an alert message
            echo '<script>alert("Schedule already exists for the same time slot on this date.");</script>';
            echo '<script>window.location.href="../pages/user/userSchedule.php";</script>';
            exit(); // Exit to prevent further execution of the script
            
        }

            $stmt = $conn->prepare("INSERT INTO v5_interpreter_scheduling (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, FromRTC, FromEmployee, DocketNumber, FileName, FileType, FileSize, FileData, PreTrialDate, PreTrialTimeStart, PreTrialTimeEnd, DateSubmitted, Version, isFinished) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 5, 0)");
            $stmt->bind_param("sssssssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $fromRTC, $empId, $docketNumber, $fileNameNew, $fileType, $fileSize, $fileData,  $PreTrialDate, $PreTrialTimeStart, $PreTrialTimeEnd, $currentDate);
            $stmtSuccess = $stmt->execute();
            
            $V5_CaseId = $conn->insert_id;
            
            // Update the PAO Version status
            $newStatus = 5;
            $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Version = ? WHERE id = ?");
            $stmt->bind_param("ii", $newStatus, $V1_CaseId);
            $stmt->execute();

            // Update the FISCAL Version status
            $newStatus = 5;
            $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET Version = ? WHERE id = ?");
            $stmt->bind_param("ii", $newStatus, $V2_CaseId);
            $stmt->execute();

            // Update the OCC Version status
            $newStatus = 5;
            $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET Version = ? WHERE id = ?");
            $stmt->bind_param("ii", $newStatus, $V3_CaseId);
            $stmt->execute();

            // Update the RTC Version status
            $newStatus = 5;
            $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET Version =? WHERE id = ?");
            $stmt->bind_param("ii",$newStatus, $V4_CaseId);
            $stmt->execute();

            //Delete from inbox_interpreter
            //$stmt = $conn->prepare("DELETE FROM inbox_interpreter WHERE id = ?");
            //$stmt->bind_param("i", $inboxId);

            if($stmt->execute()) {
                $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, isInbox, Version, Message, Time, AmPm, Date)
                VALUES (?, ?, ?, ?, ?, 1, 5, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $trackMessage, $time, $ampm, $currentDate);
                $stmt->execute();
                // Insert successful
                $stmt->close();
                $conn->close();
                // Redirect to a success page or any desired page
                header("Location: ../pages/user/userSchedule.php?uploadSuccess");
                exit();
            } else {
                // Insert failed
                $stmt->close();
                $conn->close();
            }
        } else {
            header("Location: ../pages/user/userSchedule.php?fileError");
        }
    } else {
        header("Location: ../pages/user/userSchedule.php?fileNotAllowed");
    }
}

if(isset($_POST["hearingSubmit"])) {
    $empId = $_POST['HearingempId'];
    $DocketId = $_POST['HearingDocketNumber'];
    $HearingcurrentDate = date('Y-m-d');
    $HearingDate = $_POST['hearingDate'];
    $HearingTimeStart = $_POST['hearingTimeStart'];
    $HearingTimeEnd = $_POST['hearingTimeEnd'];

    $fileName = $_FILES['hearingfile']['name'];
    $fileTmpName = $_FILES['hearingfile']['tmp_name'];
    $fileSize = $_FILES['hearingfile']['size'];
    $fileType = $_FILES['hearingfile']['type'];
    $fileError = $_FILES['hearingfile']['error'];
    $folder = "../pages/user/uploads";
    
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trackMessage = "File is now for Scheduled Hearing";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');

    // Select  all data from rtc_submit_subpoena
    $stmt = $conn->prepare("SELECT * FROM rtc_submit_subpoena WHERE id=?");
    $stmt->bind_param("s", $DocketId);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $SqlResult = $result->fetch_assoc();

    // Transfer data from rtc_submit_subpoena to a new variable
    $V1_CaseId = $SqlResult["V1_CaseId"];
    $V2_CaseId = $SqlResult["V2_CaseId"];
    $V3_CaseId = $SqlResult["V3_CaseId"];
    $V4_CaseId = $SqlResult["V4_CaseId"];
    $V5_CaseId = $SqlResult["V5_CaseId"];
    $V6_CaseId = $DocketId;
    $docketNumber = $SqlResult["DocketNumber"];

    if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
            $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
            $fileData = file_get_contents($fileTmpName);

            $stmt = $conn->prepare("INSERT INTO v6_interpreter_hearing (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, V6_CaseId, DocketNumber, FileName, FileType, FileSize, FileData, hearingDate, hearingTimeStart, hearingTimeEnd, DateSubmitted, Version, isFinished)
                  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,6,0)");
            $stmt->bind_param("sssssssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $V6_CaseId, $docketNumber, $fileNameNew, $fileType, $fileSize, $fileData,  $HearingDate, $HearingTimeStart, $HearingTimeEnd, $HearingcurrentDate);
            $stmt->execute();

            $stmt = $conn->prepare("SELECT isFinished FROM rtc_submit_subpoena WHERE id = ?");
            $stmt->bind_param("i", $DocketId);
            $stmt->execute();
            $result = $stmt->get_result();
            $HearingData = $result->fetch_assoc();
            $isFinished = $HearingData['isFinished'];
            
            $newStatus = 6;
            $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Version = ? WHERE id = ?");
            $stmt->bind_param("ii", $newStatus, $V1_CaseId);
            $stmt->execute();

            $newStatus = ($isFinished == 1) ? 0:1;
            $stmt = $conn->prepare("UPDATE rtc_submit_subpoena SET isFinished =? WHERE id =?");
            $stmt->bind_param("ii", $newStatus,$DocketId);

            //Delete from rtc_submit_subpoena
            // $stmt = $conn->prepare("DELETE FROM rtc_submit_subpoena WHERE id = ?");
            // $stmt->bind_param("i", $DocketId);

            if($stmt->execute()) {
                $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, V6_CaseId, isInbox, Version, Message, Time, AmPm, Date)
                VALUES (?, ?, ?, ?, ?, ?, 1, 6, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $inboxId, $trackMessage, $time, $ampm, $currentDate);
                $stmt->execute();
                // Insert successful
                $stmt->close();
                $conn->close();
                // Redirect to a success page or any desired page
                header("Location: ../pages/user/userSchedule.php?uploadSuccess");
                exit();

            } else {
                // Insert failed
                $stmt->close();
                $conn->close();
            }   
        } else {
            header("Location: ../pages/user/userSchedule.php?fileError");
        }
    } else {
        header("Location: ../pages/user/userSchedule.php?fileNotAllowed");
    }
}
?>

<!--IF TRYING TO UPLOAD MULTIPLE FILES
//?php
session_start();
include('../connection/config.php');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_POST['pretrialSubmit'])) {
    $empId = $_POST['empId'];
    $rtcID = $_POST['docketNumber'];
    $currentDate = date('Y-m-d');
    $PreTrialDate = $_POST['pretrialDate'];
    $PreTrialTimeStart = $_POST['pretrialtimestart'];
    $PreTrialTimeEnd = $_POST['pretrialtimeend'];

    $stmt = $conn->prepare("SELECT * FROM rtc_warrant WHERE id=?");
    $stmt->bind_param("s", $rtcID);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $SqlResult = $result->fetch_assoc();

    $V1_CaseId = $SqlResult["V1_CaseId"];
    $V2_CaseId = $SqlResult["V2_CaseId"];
    $V3_CaseId = $SqlResult["V3_CaseId"];
    $V4_CaseId = $rtcID;
    $docketNumber = $SqlResult["DocketNumber"];

    // Retrieve the logged-in employee ID from the session (replace 'loggedInEmployeeId' with your actual session variable name)
    $loggedInEmployeeId = isset($_SESSION['loggedInEmployeeId']) ? $_SESSION['loggedInEmployeeId'] : '';

    // Loop through each uploaded file
    foreach ($_FILES['file']['name'] as $key => $fileName) {
        $fileTmpName = $_FILES['file']['tmp_name'][$key];
        $fileSize = $_FILES['file']['size'][$key];
        $fileType = $_FILES['file']['type'][$key];
        $fileError = $_FILES['file']['error'][$key];
        $folder = "../pages/user/uploads";

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('pdf', 'docx');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                $fileNameNew = $loggedInEmployeeId . "_" . "Case" . rand(1000, 100000) . "." . $fileActualExt;
                $fileData = file_get_contents($fileTmpName);

                $stmt = $conn->prepare("INSERT INTO interpreter_scheduling (FromEmployee, DocketNumber, V1_CaseID, V2_CaseID, V3_CaseID, V4_CaseID, FileName, FileType, FileSize, FileData, PreTrialDate, PreTrialTimeStart, PreTrialTimeEnd, DateSubmitted, Version, isFinished)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 5, 0)");
                $stmt->bind_param("ssssssssssssss", $empId, $docketNumber, $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $fileNameNew, $fileType, $fileSize, $fileData,  $PreTrialDate, $PreTrialTimeStart, $PreTrialTimeEnd, $currentDate);

                if ($stmt->execute()) {
                    // Insert successful
                    $stmt->close();
                    // Continue processing other files or redirect as needed
                } else {
                    // Insert failed
                    $stmt->close();
                }
            } else {
                header("Location: ../pages/user/userSchedule.php?fileError");
                exit();
            }
        } else {
            header("Location: ../pages/user/userSchedule.php?fileNotAllowed");
            exit();
        }
    }

    // Redirect to a success page or any desired page after processing all files
    header("Location: ../pages/user/userSchedule.php?uploadSuccess");
    exit();
}
?>//