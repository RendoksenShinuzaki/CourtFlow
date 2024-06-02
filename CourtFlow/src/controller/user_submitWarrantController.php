<?php
session_start();
include('../connection/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_POST['submitWarrant'])) {
    $empId = $_POST['empId'];
    $warrantId = $_POST['docketNumber'];
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i A');
    $trailMessage = $empId . " successfully submitted a warrant";
    $trackMessage = "Warrant Submitted to Interpter Department";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $stmt = $conn->prepare("SELECT * FROM rtc_warrant WHERE id=?");
    $stmt->bind_param("s", $warrantId);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $SqlResult = $result->fetch_assoc();

    $V1_CaseId = $SqlResult["V1_CaseId"];
    $V2_CaseId = $SqlResult["V2_CaseId"];
    $V3_CaseId = $SqlResult["V3_CaseId"];

    $fileName = $SqlResult['FileName'];
    $fileType = $SqlResult['FileType'];
    $fileSize = $SqlResult['FileSize'];
    $fileData = $SqlResult['FileData'];
    $docketNumber = $SqlResult["DocketNumber"];

    $stmt = $conn->prepare("INSERT INTO v4_rtc_submit_warrant (V1_CaseId, V2_CaseId, V3_CaseId, FromEmployee, DocketNumber, FileName, FileType, FileSize, FileData, DateSubmitted, Accepted, Version)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 4)");
    $stmt->bind_param("ssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $empId, $docketNumber,  $fileName, $fileType, $fileSize, $fileData, $currentDate);
    $stmt->execute();
    
    $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?, 1, 4, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $warrantId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();

    // Update the PAO Version status
    $newStatus = 4;
    $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Version = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $V1_CaseId);
    $stmt->execute();

    // Update the FISCAL Version status
    $newStatus = 4;
    $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET Version = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $V2_CaseId);
    $stmt->execute();

    // Update the OCC Version status
    $newStatus = 4;
    $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET Version = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $V3_CaseId);
    $stmt->execute();

    //Delete from rtc_warrant
    $stmt = $conn->prepare("DELETE FROM rtc_warrant WHERE id = ?");
    $stmt->bind_param("i", $warrantId);

    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, Date)
        VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $trailMessage, $currentTime, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userSubmitWarrant.php?uploadSuccess");
        exit();
    } else {
        // Insert failed
        $stmt->close();
        $conn->close();
    }
}
