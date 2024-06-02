<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../connection/config.php');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';
date_default_timezone_set('Asia/Manila');

if (isset($_GET['FiscalAccept'])) {
    $Id = $_GET['FiscalAccept'];

    // Retrieve the v1_pao_submit_case Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v1_pao_submit_case WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Accepted = 1 WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();

    // Query to get the all the data from v1_pao_submit_case
    $stmt = $conn->prepare("SELECT * FROM v1_pao_submit_case WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v1_pao_submit_case transfer to another variables
    $caseId = $Id;
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $complainantName = $row['complainantLName'] . ", " . $row['complainantFName'] . " " . $row['complainantMName'];
    $accusedName = $row['accusedLName'] . ", " . $row['accusedFName'] . " " . $row['accusedMName'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has accepted a case file to his/her inbox";
    $trackMessage = "File Accepted By Fiscal";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    // Insert into inbox_fiscal
    $stmt = $conn->prepare("INSERT INTO inbox_fiscal(CaseId, ToEmployee, FromEmployee, ComplainantName, AccusedName, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $caseId, $toEmployee, $fromEmployee, $complainantName, $accusedName, $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Insert tracking
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, 1, 1, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['OCCAccept'])) {
    $Id = $_GET['OCCAccept'];

    // Retrieve the v2_fiscal_submit_case Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v2_fiscal_submit_case WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $Id);
    $stmt->execute();

    // Query to get the all the data from v2_fiscal_submit_case
    $stmt = $conn->prepare("SELECT * FROM v2_fiscal_submit_case WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v2_fiscal_submit_case transfer into new variables
    $caseId = $Id;
    $V1_CaseID = $row['V1_CaseId'];
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $penalty = $row['Penalty'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has accepted a case file to his/her inbox";
    $trackMessage = "File Accepted By OCC";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Insert into inbox_occ
    $stmt = $conn->prepare("INSERT INTO inbox_occ (CaseId, V1_CaseId, ToEmployee, FromEmployee, Penalty, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $caseId, $V1_CaseID, $toEmployee, $fromEmployee, $penalty, $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Insert tracking
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, 1, 2, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $V1_CaseID, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['RTCAccept'])) {
    $Id = $_GET['RTCAccept'];

    // Retrieve the v3_occ_case_assignment Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v3_occ_case_assignment WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $Id);
    $stmt->execute();

    // Query to get the all the data from v3_occ_case_assignment
    $stmt = $conn->prepare("SELECT * FROM v3_occ_case_assignment WHERE id=? ");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v3_occ_case_assignment transfer into new variables
    $caseId = $Id;
    $V1_CaseID = $row['V1_CaseId'];
    $V2_CaseID = $row['V2_CaseId'];
    $docketNumber = $row['DocketNumber'];
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has accepted a case file to his/her inbox";
    $trackMessage = "RTC Employee Accepted the File";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Insert into inbox_rtc
    $stmt = $conn->prepare("INSERT INTO inbox_rtc (CaseId, v1_CaseId, v2_CaseId, DocketNumber, ToEmployee, FromEmployee, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $caseId, $V1_CaseID, $V2_CaseID, $docketNumber, $toEmployee, $fromEmployee, $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId,  isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, ?, 1, 3, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $V1_CaseID, $V2_CaseID, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
                
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['InterpreterAccept'])) {
    $Id = $_GET['InterpreterAccept'];

    // Retrieve the v4_rtc_submit_warrant Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v4_rtc_submit_warrant WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $Id);
    $stmt->execute();

    // Query to get the all the data from v4_rtc_submit_warrant
    $stmt = $conn->prepare("SELECT * FROM v4_rtc_submit_warrant WHERE id=? ");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v4_rtc_submit_warrant transfer into new variables
    $caseId = $Id;
    $V1_CaseID = $row['V1_CaseId'];
    $V2_CaseID = $row['V2_CaseId'];
    $V3_CaseID = $row['V3_CaseId'];
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $docketNumber = $row['DocketNumber'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has accepted a case file to his/her inbox";
    $trackMessage = "Interpreter Department has accepted the File";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Insert into inbox_interpreter
    $stmt = $conn->prepare("INSERT INTO inbox_interpreter (CaseId, V1_CaseId, V2_CaseId, V3_CaseId, FromEmployee, ToEmployee, DocketNumber, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $caseId, $V1_CaseID, $V2_CaseID, $V3_CaseID, $fromEmployee, $toEmployee, $docketNumber,  $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?, 1, 4, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $V1_CaseID, $V2_CaseID, $V3_CaseID, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['FiscalView'])) {
    $Id = $_GET['FiscalView'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has downloaded a file from case list";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v1_pao_submit_case WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();

        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
        
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
    } else {
        echo "File not found in the database.";
    }

    $stmt->close();
    $conn->close();
}

if(isset($_GET['OCCView'])){
    $Id = $_GET['OCCView'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has downloaded a file from case list";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v2_fiscal_submit_case WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();

        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
        
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
    } else {
        echo "File not found in the database.";
    }

    $stmt->close();
    $conn->close();
}

if(isset($_GET['RTCView'])){
    $Id = $_GET['RTCView'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has downloaded a file from case list";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v3_occ_case_assignment WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();

        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
        
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
    } else {
        echo "File not found in the database.";
    }

    $stmt->close();
    $conn->close();
}

if(isset($_GET['InterpreterView'])){
    $Id = $_GET['InterpreterView'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $toEmployee . " has downloaded a file from case list";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v4_rtc_submit_warrant WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();

        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
        
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
    } else {
        echo "File not found in the database.";
    }

    $stmt->close();
    $conn->close();
}

if(isset($_GET['InterpreterSummaryView'])){
    $Id = $_GET['InterpreterSummaryView'];
    
    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM rtc_submit_subpoena WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();

        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
    } else {
        echo "File not found in the database.";
    }

    $stmt->close();
    $conn->close();
}


if (isset($_POST['FiscalReturnSubmitButton'])) {
    $id = $_POST['id'];
    $toEmployee = $_POST['toEmployee'];
    $returnReason = $_POST['returnReason'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " is trying to return a case to " . $toEmployee;
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Retrieve the v1_pao_submit_case isReturn status
    $stmt = $conn->prepare("SELECT isReturn FROM v1_pao_submit_case WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $isReturn = $userData['isReturn'];

    // Update the isReturn status
    $newStatus = ($isReturn == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET isReturn = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    $caseId = $id;
    
    // Insert into returns
    $stmt = $conn->prepare("INSERT INTO returns(CaseId, Version, ToEmployee, FromEmployee, Reason)
                VALUES (?, 1, ?, ?, ?)");
    $stmt->bind_param("ssss", $caseId, $toEmployee, $loggedInEmployeeId, $returnReason);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_POST['OCCReturnSubmitButton'])) {
    $id = $_POST['id'];
    $toEmployee = $_POST['toEmployee'];
    $returnReason = $_POST['returnReason'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " is trying to return a case to " . $toEmployee;
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Retrieve the v2_fiscal_submit_case isReturn status
    $stmt = $conn->prepare("SELECT isReturn FROM v2_fiscal_submit_case WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $isReturn = $userData['isReturn'];

    // Update the isReturn status
    $newStatus = ($isReturn == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET isReturn = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    $caseId = $id;
    
    // Insert into returns
    $stmt = $conn->prepare("INSERT INTO returns(CaseId, Version, ToEmployee, FromEmployee, Reason)
                VALUES (?, 2, ?, ?, ?)");
    $stmt->bind_param("ssss", $caseId, $toEmployee, $loggedInEmployeeId, $returnReason);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_POST['RTCReturnSubmitButton'])) {
    $id = $_POST['id'];
    $toEmployee = $_POST['toEmployee'];
    $returnReason = $_POST['returnReason'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " is trying to return a case to " . $toEmployee;
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Retrieve the v3_occ_case_assignment isReturn status
    $stmt = $conn->prepare("SELECT isReturn FROM v3_occ_case_assignment WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $isReturn = $userData['isReturn'];

    // Update the isReturn status
    $newStatus = ($isReturn == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET isReturn = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    $caseId = $id;
    
    // Insert into returns
    $stmt = $conn->prepare("INSERT INTO returns(CaseId, Version, ToEmployee, FromEmployee, Reason)
                VALUES (?, 3, ?, ?, ?)");
    $stmt->bind_param("ssss", $caseId, $toEmployee, $loggedInEmployeeId, $returnReason);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_POST['InterpreterReturnSubmitButton'])) {
    $id = $_POST['id'];
    $toEmployee = $_POST['toEmployee'];
    $returnReason = $_POST['returnReason'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " is trying to return a case to " . $toEmployee;
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Retrieve the v4_rtc_submit_warrant isReturn status
    $stmt = $conn->prepare("SELECT isReturn FROM v4_rtc_submit_warrant WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $isReturn = $userData['isReturn'];

    // Update the isReturn status
    $newStatus = ($isReturn == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET isReturn = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    $caseId = $id;
    
    // Insert into returns
    $stmt = $conn->prepare("INSERT INTO returns(CaseId, Version, ToEmployee, FromEmployee, Reason)
                VALUES (?, 4, ?, ?, ?)");
    $stmt->bind_param("ssss", $caseId, $toEmployee, $loggedInEmployeeId, $returnReason);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['ClerkSummaryAccept'])) {
    $Id = $_GET['ClerkSummaryAccept'];

    // Retrieve the v7_interpreter_submit_summary Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v7_interpreter_submit_summary WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt2 = $conn->prepare("UPDATE v7_interpreter_submit_summary SET Accepted = ? WHERE id = ?");
    $stmt2->bind_param("ii", $newStatus, $Id);
    $stmt2->execute();
    
    $newVersion = 8;
    $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Version = ? WHERE id = ?");
    $stmt->bind_param("ii", $newVersion, $Id);
    $stmt->execute();

    // Query to get the all the data from v7_interpreter_submit_summary
    $stmt = $conn->prepare("SELECT * FROM v7_interpreter_submit_summary WHERE id=? ");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v7_interpreter_submit_summary transfer into new variables
    $caseId = $Id;
    $V1_CaseID = $row['V1_CaseId'];
    $V2_CaseID = $row['V2_CaseId'];
    $V3_CaseID = $row['V3_CaseId'];
    $V4_CaseID = $row['V4_CaseId'];
    $V5_CaseID = $row['v5_CaseId'];
    $V6_CaseID = $row['V6_CaseId'];
    $V7_CaseID = $row['V7_CaseId'];
    $fromEmployee = $row['FromEmployee'];
    $docketNumber = $row['DocketNumber'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A');
    $trackMessage = "Complete: Clerk In Charge Recevied the Case File";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Insert into inbox_interpreter
    $stmt = $conn->prepare("INSERT INTO inbox_clerk_summary (CaseId, V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, V6_CaseId, V7_CaseId, FromEmployee, DocketNumber, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $caseId, $V1_CaseID, $V2_CaseID, $V3_CaseID, $V4_CaseID, $V5_CaseID, $V6_CaseID, $V7_CaseID, $fromEmployee, $docketNumber,  $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
            
    $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, V6_CaseId, V7_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, 8, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $V1_CaseID, $V2_CaseID, $V3_CaseID, $V4_CaseId, $V5_CaseId, $V6_CaseId, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if(isset($_GET["ClerkArraignmentAccept"])) {
    $Id = $_GET["ClerkArraignmentAccept"];

    // Retrieve the v5_interpreter_scheduling Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM interpreter_guilty WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE interpreter_guilty SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $Id);
    $stmt->execute();

    // Query to get the all the data from v5_interpreter_scheduling
    $stmt = $conn->prepare("SELECT * FROM interpreter_guilty WHERE id=? ");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v5_interpreter_scheduling transfer into new variables
    $caseId = $Id;
    $V1_CaseID = $row['V1_CaseId'];
    $V2_CaseID = $row['V2_CaseId'];
    $V3_CaseID = $row['V3_CaseId'];
    $V4_CaseID = $row['V4_CaseId'];
    $V5_CaseID = $row['V5_CaseId'];
    $fromEmployee = $row['FromEmployee'];
    $docketNumber = $row['DocketNumber'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');

    // Insert into inbox_interpreter
    $stmt = $conn->prepare("INSERT INTO inbox_clerk_guilty (CaseId, V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, FromEmployee, DocketNumber, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $caseId, $V1_CaseID, $V2_CaseID, $V3_CaseID, $V4_CaseID, $V5_CaseID, $fromEmployee, $docketNumber,  $fileName, $currentDate);

    $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, V6_CaseId, V7_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?, 1, 5, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $V1_CaseID, $V2_CaseID, $V3_CaseID, $V4_CaseId, $V5_CaseId, $V6_CaseId, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();

    // Execute the insert statement
    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }

}

if (isset($_POST['FiscalRemarksSubmitButton'])) {
    // Create Remarks
    $id = $_POST['id'];
    $remarksReason = $_POST['remarksReason'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " has acccepted and created a remarks for a case that is on hold for too long ";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    // Insert into remarks
    $stmt = $conn->prepare("INSERT INTO remarks(CaseId, Version, FromEmployee, Reason)
                VALUES (?, 1, ?, ?)");
    $stmt->bind_param("sss", $id, $loggedInEmployeeId, $remarksReason);
    $stmt->execute();
    
    // Add to fiscal inbox
    // Retrieve the v1_pao_submit_case Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v1_pao_submit_case WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];
    
    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Accepted = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Query to get the all the data from v1_pao_submit_case
    $stmt = $conn->prepare("SELECT * FROM v1_pao_submit_case WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();
    
    // Data from v1_pao_submit_case transfer to another variables
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $complainantName = $row['complainantLName'] . ", " . $row['complainantFName'] . " " . $row['complainantMName'];
    $accusedName = $row['accusedLName'] . ", " . $row['accusedFName'] . " " . $row['accusedMName'];
    $fileName = $row['FileName'];
    $trackMessage = "File Accepted By Fiscal and created a remarks";
    
    // Insert into inbox_fiscal
    $stmt = $conn->prepare("INSERT INTO inbox_fiscal(CaseId, ToEmployee, FromEmployee, ComplainantName, AccusedName, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $id, $toEmployee, $fromEmployee, $complainantName, $accusedName, $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Insert tracking
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, 1, 1, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_POST['OCCRemarksSubmitButton'])) {
    //Create Remarks
    $id = $_POST['id'];
    $remarksReason = $_POST['remarksReason'];

    // Retrieve the v2_fiscal_submit_case Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v2_fiscal_submit_case WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    // Query to get the all the data from v2_fiscal_submit_case
    $stmt = $conn->prepare("SELECT * FROM v2_fiscal_submit_case WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v2_fiscal_submit_case transfer into new variables
    $caseId = $id;
    $V1_CaseID = $row['V1_CaseId'];
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $penalty = $row['Penalty'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " has acccepted and created a remarks for a case that is on hold for too long ";
    $trackMessage = "File Accepted By OCC and created a remarks";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    // Insert into remarks
    $stmt = $conn->prepare("INSERT INTO remarks(CaseId, Version, FromEmployee, Reason)
                VALUES (?, 2, ?, ?)");
    $stmt->bind_param("sss", $caseId, $loggedInEmployeeId, $remarksReason);
    $stmt->execute();

    // Insert into inbox_occ
    $stmt = $conn->prepare("INSERT INTO inbox_occ (CaseId, V1_CaseId, ToEmployee, FromEmployee, Penalty, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $caseId, $V1_CaseID, $toEmployee, $fromEmployee, $penalty, $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Insert tracking
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, 1, 2, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $V1_CaseID, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_POST['RTCRemarksSubmitButton'])) {
    //Create Remarks
    $id = $_POST['id'];
    $remarksReason = $_POST['remarksReason'];

    // Retrieve the v3_occ_case_assignment Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v3_occ_case_assignment WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    // Query to get the all the data from v3_occ_case_assignment
    $stmt = $conn->prepare("SELECT * FROM v3_occ_case_assignment WHERE id=? ");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v3_occ_case_assignment transfer into new variables
    $caseId = $id;
    $V1_CaseID = $row['V1_CaseId'];
    $V2_CaseID = $row['V2_CaseId'];
    $docketNumber = $row['DocketNumber'];
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " has acccepted and created a remarks for a case that is on hold for too long ";
    $trackMessage = "RTC Employee Accepted the File and created a remarks";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    // Insert into remarks
    $stmt = $conn->prepare("INSERT INTO remarks(CaseId, Version, FromEmployee, Reason)
                VALUES (?, 3, ?, ?)");
    $stmt->bind_param("sss", $caseId, $loggedInEmployeeId, $remarksReason);
    $stmt->execute();

    // Insert into inbox_rtc
    $stmt = $conn->prepare("INSERT INTO inbox_rtc (CaseId, v1_CaseId, v2_CaseId, DocketNumber, ToEmployee, FromEmployee, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $caseId, $V1_CaseID, $V2_CaseID, $docketNumber, $toEmployee, $fromEmployee, $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId,  isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, ?, 1, 3, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $V1_CaseID, $V2_CaseID, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
                
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_POST['InterpreterRemarksSubmitButton'])) {
    //Create Remarks
    $id = $_POST['id'];
    $remarksReason = $_POST['remarksReason'];

    // Retrieve the v4_rtc_submit_warrant Accepted status
    $stmt = $conn->prepare("SELECT Accepted FROM v4_rtc_submit_warrant WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    // Query to get the all the data from v4_rtc_submit_warrant
    $stmt = $conn->prepare("SELECT * FROM v4_rtc_submit_warrant WHERE id=? ");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from v4_rtc_submit_warrant transfer into new variables
    $caseId = $id;
    $V1_CaseID = $row['V1_CaseId'];
    $V2_CaseID = $row['V2_CaseId'];
    $V3_CaseID = $row['V3_CaseId'];
    $toEmployee = $loggedInEmployeeId;
    $fromEmployee = $row['FromEmployee'];
    $docketNumber = $row['DocketNumber'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " has acccepted and created a remarks for a case that is on hold for too long ";
    $trackMessage = "Interpreter Department has accepted the File and created a remarks";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    // Insert into remarks
    $stmt = $conn->prepare("INSERT INTO remarks(CaseId, Version, FromEmployee, Reason)
                VALUES (?, 4, ?, ?)");
    $stmt->bind_param("sss", $caseId, $loggedInEmployeeId, $remarksReason);
    $stmt->execute();

    // Insert into inbox_interpreter
    $stmt = $conn->prepare("INSERT INTO inbox_interpreter (CaseId, V1_CaseId, V2_CaseId, V3_CaseId, FromEmployee, ToEmployee, DocketNumber, FileName, DateAccepted)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $caseId, $V1_CaseID, $V2_CaseID, $V3_CaseID, $fromEmployee, $toEmployee, $docketNumber,  $fileName, $currentDate);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, isInbox, Version, Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?, 1, 4, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $V1_CaseID, $V2_CaseID, $V3_CaseID, $caseId, $trackMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userCaseList.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}