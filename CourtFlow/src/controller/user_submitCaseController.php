<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_POST['paoSubmit'])) {
    $empId = $_POST['empId'];
    $cLName = $_POST['cLName'];
    $cFName = $_POST['cFName'];
    $cMName = $_POST['cMName'];
    $aLName = $_POST['aLName'];
    $aFName = $_POST['aFName'];
    $aMName = $_POST['aMName'];
    $trailMessage = $empId . " successfully submitted a case file";
    $trackMessage = "File Departed From PAO Department";
    $newTrackMessage = "x---PAO Department---x " . $trackMessage;
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

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

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
            $fileData = file_get_contents($fileTmpName);

            $stmt = $conn->prepare("INSERT INTO v1_pao_submit_case (FromEmployee, complainantLName, complainantFName, complainantMName, accusedLName, accusedFName, accusedMName, FileName, FileType, FileSize, FileData, Date, Accepted, isReturn, isReturnApproved, Version)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 1)");
            $stmt->bind_param("ssssssssssss", $empId, $cLName, $cFName, $cMName, $aLName, $aFName, $aMName, $fileNameNew, $fileType, $fileSize, $fileData, $currentDate);
            $stmt->execute();

            $CaseId = $conn->insert_id;

            $selectedRouteId = $_POST['Route']; // Get the selected route ID from the form

            // Fetch the first department ID from dept_sequence table
            $deptSequenceQuery = "SELECT id, dept_sequence FROM test_route WHERE id = $selectedRouteId";
            $deptSequenceResult = $conn->query($deptSequenceQuery);

            if ($deptSequenceResult && $deptSequenceResult->num_rows > 0) {
                $row = $deptSequenceResult->fetch_assoc();
                $routeId = $row['id'];
                $deptSequence = $row['dept_sequence'];

                // Extract the first number from dept_sequence
                $deptSequenceArray = explode(',', $deptSequence);
                $firstDeptSequence = $deptSequenceArray[0];

                // Find the next number in the dept_sequence
                $nextDeptSequenceKey = array_search($currentRoute, $deptSequenceArray);
                $nextDeptSequence = $deptSequenceArray[$nextDeptSequenceKey + 1] ?? null;

                // Insert into current_route
                $currentRoute = $firstDeptSequence;
                $v2ToV7 = null; // Setting V2 to V7 as null

                // Insert into pending_sequence
                $stmt = $conn->prepare("INSERT INTO pending_sequence (route_id, current_route, next_route, V1, V2, V3, V4, V5, V6, V7)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $routeId, $currentRoute, $nextDeptSequence, $CaseId, $v2ToV7, $v2ToV7, $v2ToV7, $v2ToV7, $v2ToV7, $v2ToV7);

                if ($stmt->execute()) {
                    // Insert into pending_sequence successful
                    // Insert tracking
                    $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, isInbox, Version, Message, Time, AmPm, Date)
                        VALUES (?, 0, 1, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $CaseId, $newTrackMessage, $time, $ampm, $currentDate);

                    if ($stmt->execute()) {
                        // Insert successful then insert into audit trail
                        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
                            VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
                        $stmt->execute();

                        $stmt->close();
                        $conn->close();
                        // Redirect to a success page or any desired page
                        header("Location: ../pages/user/userSubmitCase.php?uploadSuccess");
                        exit();
                    } else {
                        // Insert into tracking failed
                        // Handle the failure as needed
                    }
                } else {
                    // Insert into pending_sequence failed
                    // Handle the failure as needed
                }
            } else {
                // Insert into current_route failed
                // Handle the failure as needed
            }
        } else {
            // Handle the case where no department ID is found
            // You may want to redirect to an error page or handle it in another way
            header("Location: ../errorPage.php?deptIdNotFound");
            exit();
        }
    } else {
        header("Location: ../pages/user/userSubmitCase.php?fileError");
    }
} else {
    header("Location: ../pages/user/userSubmitCase.php?fileNotAllowed");
}


if (isset($_POST['fiscalSubmit'])) {
    $empId = $_POST['empId'];
    $caseTitle = $_POST['caseTitle'];
    $penalty = $_POST['penalty'];
    $inboxId = $_POST['inboxFile'];
    $trailMessage = $empId . " successfully submitted a case file";
    $trackMessage = "File Departed from Fiscal Department";
    $newTrackMessage = "x---Fiscal Department---x " . $trackMessage;
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $FiscalCurrentTime = date('h:i A');

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

    // Select  Penalty
    $stmt = $conn->prepare("SELECT * FROM penalties WHERE id=?");
    $stmt->bind_param("s", $penalty);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $penaltyResult = $result->fetch_assoc();

    $newPenalty = "Republic Act " . $penaltyResult['RepublicAct'] . ", Article " . $penaltyResult['Article'] . ", Section " . $penaltyResult['Section'];

    // Select  inbox_fiscal
    $stmt = $conn->prepare("SELECT * FROM inbox_fiscal WHERE id=?");
    $stmt->bind_param("s", $inboxId);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $inboxResult = $result->fetch_assoc();

    // The CaseId data from inbox_fiscal table
    $V1_CaseId = $inboxResult["CaseId"];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
            $fileData = file_get_contents($fileTmpName);

            // insert to v2_fiscal_submit_case
            $stmt = $conn->prepare("INSERT INTO v2_fiscal_submit_case (V1_CaseId, FromEmployee, Penalty, FileName, FileType, FileSize, FileData, DateSubmitted, Accepted, Version)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 2)");
            $stmt->bind_param("ssssssss", $V1_CaseId, $empId, $newPenalty, $fileNameNew, $fileType, $fileSize, $fileData, $currentDate);
            $stmt->execute();

            $V2_CaseId = $conn->insert_id;
            //Insert tracking
            $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, isInbox, Version, Message, Time, AmPm, Date)
            VALUES (?, ?, 0, 2, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $V1_CaseId, $V2_CaseId, $newTrackMessage, $time, $ampm, $currentDate);

            if ($stmt->execute()) {

                // Insert successful then insert into audit trail
                $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
                  VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
                $stmt->execute();

                // Update the Version status in v1_pao_submit_case
                $newStatus = 2;
                $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Version = ? WHERE id = ?");
                $stmt->bind_param("ii", $newStatus, $V1_CaseId);
                $stmt->execute();

                //Update from fiscal_inbox
                $submitted = 1;
                $stmt = $conn->prepare("UPDATE inbox_fiscal SET Submitted = ? WHERE id = ?");
                $stmt->bind_param("ii", $submitted, $inboxId);
                $stmt->execute();

                $stmt->close();
                $conn->close();
                // Redirect to a success page or any desired page
                header("Location: ../pages/user/userSubmitCase.php?uploadSuccess");
                exit();
            } else {
                // Insert failed
                $stmt->close();
                $conn->close();
            }
        } else {
            header("Location: ../pages/user/userSubmitCase.php?fileError");
        }
    } else {
        header("Location: ../pages/user/userSubmitCase.php?fileNotAllowed");
    }
}

if (isset($_POST['subpoenaSubmit'])) {

    $empId = $_POST['empId'];
    $id = $_POST['docketNumber'];
    $trailMessage = $empId . " successfully submitted a  subpoena ";
    $trackMessage = "Subpoena Attached";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

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

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');

    // Select all the data from  rtc_for_subpoena table
    $stmt = $conn->prepare("SELECT * FROM rtc_for_subpoena WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $rtcResult = $result->fetch_assoc();

    // The data from rtc_for_subpoena table transfer to a variable
    $V1_CaseId = $rtcResult["V1_CaseId"];
    $V2_CaseId = $rtcResult["V2_CaseId"];
    $V3_CaseId = $rtcResult["V3_CaseId"];
    $V4_CaseId = $rtcResult["V4_CaseId"];
    $V5_CaseId = $rtcResult["V5_CaseId"];
    $docketNumber = $rtcResult["DocketNumber"];


    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
            $fileData = file_get_contents($fileTmpName);

            // insert to rtc_submit_subpoena
            $stmt = $conn->prepare("INSERT INTO rtc_submit_subpoena (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, FromEmployee, DocketNumber, FileName, FileType, FileSize, FileData, DateSubmitted)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $empId, $docketNumber, $fileNameNew, $fileType, $fileSize, $fileData, $currentDate);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, isInbox, Version, Message, Time, AmPm, Date)
            VALUES (?, ?, ?, ?, ?, 1, 4, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $id, $trackMessage, $time, $ampm, $currentDate);
            $stmt->execute();

            //Update from rtc_for_subpoena
            $submitted = 1;
            $stmt = $conn->prepare("UPDATE rtc_for_subpoena SET Submitted = ? WHERE id = ?");
            $stmt->bind_param("ii", $submitted, $id);

            if ($stmt->execute()) {
                // Insert successful then insert into audit trail
                $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
                  VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
                $stmt->execute();

                $stmt->close();
                $conn->close();
                // Redirect to a success page or any desired page
                header("Location: ../pages/user/userSubmitCase.php?uploadSuccess");
                exit();
            } else {
                // Insert failed
                $stmt->close();
                $conn->close();
            }
        } else {
            header("Location: ../pages/user/userSubmitCase.php?fileError");
        }
    } else {
        header("Location: ../pages/user/userSubmitCase.php?fileNotAllowed");
    }
}

//di ni final gi sundog rani sa taas for reference
if (isset($_POST["summarySubmit"])) {

    $empId = $_POST['empId'];
    $id = $_POST['docketNumber'];
    $currentDate = date('Y-m-d');
    $trackMessage = "File Departed From Interpreter Department";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

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

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');

    // Select all the data from  rtc_for_subpoena table
    $stmt = $conn->prepare("SELECT * FROM v6_interpreter_hearing WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $rtcResult = $result->fetch_assoc();

    // The data from v6_interpreter_hearing table transfer to a variable
    $V1_CaseId = $rtcResult["V1_CaseId"];
    $V2_CaseId = $rtcResult["V2_CaseId"];
    $V3_CaseId = $rtcResult["V3_CaseId"];
    $V4_CaseId = $rtcResult["V4_CaseId"];
    $V5_CaseId = $rtcResult["V5_CaseId"];
    $V6_CaseId = $rtcResult["V6_CaseId"];
    $V6 = $id;
    $V7_CaseId = $id;
    $docketNumber = $rtcResult["DocketNumber"];


    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
            $fileData = file_get_contents($fileTmpName);

            // insert to rtc_submit_subpoena
            $stmt = $conn->prepare("INSERT INTO v7_interpreter_submit_summary (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, V6_CaseId, v6, V7_CaseId, FromEmployee, DocketNumber, FileName, FileType, FileSize, FileData, DateSubmitted, Version)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7)");
            $stmt->bind_param("sssssssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $V6_CaseId, $V6, $V7_CaseId, $empId, $docketNumber, $fileNameNew, $fileType, $fileSize, $fileData, $currentDate);


            if ($stmt->execute()) {
                $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, V6_CaseId, isInbox, Version, Message, Time, AmPm, Date)
                    VALUES (?, ?, ?, ?, ?, ?, 1, 7, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $id, $trackMessage, $time, $ampm, $currentDate);
                $stmt->execute();
                // Insert successful
                $stmt->close();
                $conn->close();
                // Redirect to a success page or any desired page
                header("Location: ../pages/user/userSubmitCase.php?uploadSuccess");
                exit();
            } else {
                // Insert failed
                $stmt->close();
                $conn->close();
            }
        } else {
            header("Location: ../pages/user/userSubmitCase.php?fileError");
        }
    } else {
        header("Location: ../pages/user/userSubmitCase.php?fileNotAllowed");
    }
}
