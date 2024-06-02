<?php

session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['occSubmit'])) {
    $empId = $_POST['empId'];
    $docketNumber = $_POST['docketNumber'];
    $branch = $_POST['branch'];
    $inboxId = $_POST['inboxFile'];
    $currentDate = date('Y-m-d');
    $newDocketNumber = "RDVO" . $docketNumber;
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $empId . " successfully assigned a docket number";
    $trackMessage = "File Departed from OCC Department";
    
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

    // Select  inbox_occ
    $stmt = $conn->prepare("SELECT * FROM inbox_occ WHERE id=?");
    $stmt->bind_param("s", $inboxId);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $inboxResult = $result->fetch_assoc();

    $V1_CaseId = $inboxResult["V1_CaseId"];
    $V2_CaseId = $inboxResult["CaseId"];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');


    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $fileNameNew = $fileName . $fileActualExt;
            $fileData = file_get_contents($fileTmpName);

            // insert to v3_occ_case_assignment
            $stmt = $conn->prepare("INSERT INTO v3_occ_case_assignment (V1_CaseId, V2_CaseId, FromEmployee, DocketNumber, ToBranch,  FileName, FileType, FileSize, FileData, DateSubmitted, Accepted, Version)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 3)");
            $stmt->bind_param("ssssssssss", $V1_CaseId, $V2_CaseId, $empId, $newDocketNumber, $branch,  $fileNameNew, $fileType, $fileSize, $fileData, $currentDate);
            $stmt->execute();
            
            
            $V3_CaseId = $conn->insert_id;
            //Insert tracking
            $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, isInbox, Version, Message, Time, AmPm, Date)
            VALUES (?, ?, ?, 0, 3, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $trackMessage, $time, $ampm, $currentDate);
            
            if ($stmt->execute()) {
                // Insert successful then insert into audit trail
                $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
                  VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
                $stmt->execute();
                
                // Update the PAO Version status
                $newStatus = 3;
                $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Version = ? WHERE id = ?");
                $stmt->bind_param("ii", $newStatus, $V1_CaseId);
                $stmt->execute();

                // Update the FISCAL Version status
                $newStatus = 3;
                $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET Version = ? WHERE id = ?");
                $stmt->bind_param("ii", $newStatus, $V2_CaseId);
                $stmt->execute();

                //Delete from inbox_occ
                $stmt = $conn->prepare("DELETE FROM inbox_occ WHERE id = ?");
                $stmt->bind_param("i", $inboxId);
                $stmt->execute();
                
                $stmt->close();
                $conn->close();
                // Redirect to a success page or any desired page
                header("Location: ../pages/user/userCaseAssignment.php?uploadSuccess");
                exit();
            } else {
                // Insert failed
                $stmt->close();
                $conn->close();
            }
        } else {
            header("Location: ../pages/user/userCaseAssignment.php?fileError");
        }
    } else {
        header("Location: ../pages/user/userCaseAssignment.php?fileNotAllowed");
    }
}
