<?php
session_start();
include('../connection/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_POST['warrantSubmit'])) {
    $empId = $_POST['empId'];
    $inboxId = $_POST['docketNumber'];
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i A');
    $trailMessage = $empId . " successfully filed a warrant";
    $trackMessage = "Warrant Filed";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileError = $_FILES['file']['error'];
    $folder = "../pages/user/uploads";

    // Select  all data from inbox_rtc
    $stmt = $conn->prepare("SELECT * FROM inbox_rtc WHERE id=?");
    $stmt->bind_param("s", $inboxId);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $SqlResult = $result->fetch_assoc();

    // Transfer data from inbox_rtc to a new variable
    $V1_CaseId = $SqlResult["V1_CaseId"];
    $V2_CaseId = $SqlResult["V2_CaseId"];
    $V3_CaseId = $SqlResult["CaseId"];
    $docketNumber = $SqlResult["DocketNumber"];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
            $fileData = file_get_contents($fileTmpName);

            $stmt = $conn->prepare("INSERT INTO rtc_warrant (V1_CaseId, V2_CaseId, V3_CaseId, FromEmployee, DocketNumber,  FileName, FileType, FileSize, FileData, DateIssued, IsPending)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("ssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $empId, $docketNumber,  $fileNameNew, $fileType, $fileSize, $fileData, $currentDate);
            $stmt->execute();

            //Delete from inbox_rtc
            $stmt = $conn->prepare("DELETE FROM inbox_rtc WHERE id = ?");
            $stmt->bind_param("i", $inboxId);

            if ($stmt->execute()) {
                // Insert successful then insert into audit trail
                $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, Date)
                  VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $trailMessage, $currentTime, $currentDate);
                $stmt->execute();
                    
            $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, isInbox, Version, Message, Time, AmPm, Date)
                VALUES (?, ?, ?, 1, 3, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $trackMessage, $time, $ampm, $currentDate);
            $stmt->execute();
                
                $stmt->close();
                $conn->close();
                // Redirect to a success page or any desired page
                header("Location: ../pages/user/userWarrant.php?uploadSuccess");
                exit();
            } else {
                // Insert failed
                $stmt->close();
                $conn->close();
            }
        } else {
            header("Location: ../pages/user/userWarrant.php?fileError");
        }
    } else {
        header("Location: ../pages/user/userWarrant.php?fileNotAllowed");
    }
}
