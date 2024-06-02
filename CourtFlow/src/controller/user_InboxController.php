<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_GET['FiscalInboxReturn'])) {
    $Id = $_GET['FiscalInboxReturn'];
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trailMessage = $loggedInEmployeeId . " has returned a case to case list from his/her inbox";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    //Select the CaseId from inbox_fiscal
    $stmt = $conn->prepare("SELECT * FROM inbox_fiscal WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Update the Accepted status
    $caseId = $row['CaseId'];
    $stmt = $conn->prepare("SELECT Accepted FROM v1_pao_submit_case WHERE id = ?");
    $stmt->bind_param("i", $caseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $caseId);
    $stmt->execute();

    //Delete from inbox_fiscal
    $stmt = $conn->prepare("DELETE FROM inbox_fiscal WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    
    //Delete from remarks
    $Version = 1;
    $stmt = $conn->prepare("DELETE FROM remarks WHERE CaseId = ? AND Version = ?");
    $stmt->bind_param("ii", $caseId, $Version);
    $stmt->execute();
    
    //Delete from tracking
    $isInbox = 1;
    $stmt = $conn->prepare("DELETE FROM tracking WHERE V1_CaseId = ? AND isInbox = ?");
    $stmt->bind_param("ii", $caseId, $isInbox);

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
        header("Location: ../pages/user/userInbox.php?ReturnSuccessful");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['OCCInboxReturn'])) {
    $Id = $_GET['OCCInboxReturn'];
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    $trailMessage = $loggedInEmployeeId . " has returned a case to case list from his/her inbox";

    //Select the CaseId from inbox_occ
    $stmt = $conn->prepare("SELECT * FROM inbox_occ WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Update the Accepted status
    $caseId = $row['CaseId'];
    $stmt = $conn->prepare("SELECT Accepted FROM v2_fiscal_submit_case WHERE id = ?");
    $stmt->bind_param("i", $caseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $caseId);
    $stmt->execute();

    //Delete from inbox_occ
    $stmt = $conn->prepare("DELETE FROM inbox_occ WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    
    //Delete from remarks
    $Version = 2;
    $stmt = $conn->prepare("DELETE FROM remarks WHERE CaseId = ? AND Version = ?");
    $stmt->bind_param("ii", $caseId, $Version);
    $stmt->execute();
    
    //Delete from tracking
    $isInbox = 1;
    $stmt = $conn->prepare("DELETE FROM tracking WHERE V2_CaseId = ? AND isInbox = ?");
    $stmt->bind_param("ii", $caseId, $isInbox);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, Date)
        VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $trailMessage, $currentTime, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userInbox.php?ReturnSuccessful");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['RTCInboxReturn'])) {
    $Id = $_GET['RTCInboxReturn'];
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    $trailMessage = $loggedInEmployeeId . " has returned a case to case list from his/her inbox";

    //Select the CaseId from inbox_rtc
    $stmt = $conn->prepare("SELECT * FROM inbox_rtc WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Update the Accepted status
    $caseId = $row['CaseId'];
    $stmt = $conn->prepare("SELECT Accepted FROM v3_occ_case_assignment WHERE id = ?");
    $stmt->bind_param("i", $caseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $caseId);
    $stmt->execute();

    //Delete from inbox_rtc
    $stmt = $conn->prepare("DELETE FROM inbox_rtc WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    
    //Delete from remarks
    $Version = 3;
    $stmt = $conn->prepare("DELETE FROM remarks WHERE CaseId = ? AND Version = ?");
    $stmt->bind_param("ii", $caseId, $Version);
    $stmt->execute();
    
    //Delete from tracking
    $isInbox = 1;
    $stmt = $conn->prepare("DELETE FROM tracking WHERE V3_CaseId = ? AND isInbox = ?");
    $stmt->bind_param("ii", $caseId, $isInbox);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, Date)
        VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $trailMessage, $currentTime, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userInbox.php?ReturnSuccessful");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['InterpreterInboxReturn'])) {
    $Id = $_GET['InterpreterInboxReturn'];
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    $trailMessage = $loggedInEmployeeId . " has returned a case to case list from his/her inbox";

    //Select the CaseId from inbox_interpreter
    $stmt = $conn->prepare("SELECT * FROM inbox_interpreter WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Update the Accepted status
    $caseId = $row['CaseId'];
    $stmt = $conn->prepare("SELECT Accepted FROM v4_rtc_submit_warrant WHERE id = ?");
    $stmt->bind_param("i", $caseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $caseId);
    $stmt->execute();

    //Delete from inbox_interpreter
    $stmt = $conn->prepare("DELETE FROM inbox_interpreter WHERE id = ?");
    $stmt->bind_param("i", $Id);
    $stmt->execute();
    
    //Delete from remarks
    $Version = 4;
    $stmt = $conn->prepare("DELETE FROM remarks WHERE CaseId = ? AND Version = ?");
    $stmt->bind_param("ii", $caseId, $Version);
    $stmt->execute();
    
    //Delete from tracking
    $isInbox = 1;
    $stmt = $conn->prepare("DELETE FROM tracking WHERE V4_CaseId = ? AND isInbox = ?");
    $stmt->bind_param("ii", $caseId, $isInbox);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, Date)
        VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $trailMessage, $currentTime, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userInbox.php?ReturnSuccessful");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['ClerkInboxReturn'])) {
    $Id = $_GET['ClerkInboxReturn'];
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    $trailMessage = $loggedInEmployeeId . " has returned a case to case list from his/her inbox";

    //Select the CaseId from inbox_clerk_summary
    $stmt = $conn->prepare("SELECT * FROM inbox_clerk_summary WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Update the Accepted status
    $caseId = $row['CaseId'];
    $stmt = $conn->prepare("SELECT Accepted FROM v7_interpreter_submit_summary WHERE id = ?");
    $stmt->bind_param("i", $caseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v7_interpreter_submit_summary SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $caseId);
    $stmt->execute();

    //Delete from inbox_interpreter
    $stmt = $conn->prepare("DELETE FROM inbox_clerk_summary WHERE id = ?");
    $stmt->bind_param("i", $Id);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, Date)
        VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $trailMessage, $currentTime, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userInbox.php?ReturnSuccessful");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET["ClerkGuiltyReturn"])) {
    $Id = $_GET['ClerkGuiltyReturn'];
    //Select the CaseId from inbox_clerk_guilty
    $stmt = $conn->prepare("SELECT * FROM inbox_clerk_guilty WHERE id=?");
    $stmt->bind_param("s", $Id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Update the Accepted status
    $caseId = $row['CaseId'];
    $stmt = $conn->prepare("SELECT Accepted FROM interpreter_guilty WHERE id = ?");
    $stmt->bind_param("i", $caseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $accepted = $userData['Accepted'];

    // Update the Accepted status
    $newStatus = ($accepted == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE interpreter_guilty SET Accepted = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $caseId);
    $stmt->execute();

    //Delete from inbox_interpreter
    $stmt = $conn->prepare("DELETE FROM inbox_clerk_guilty WHERE id = ?");
    $stmt->bind_param("i", $Id);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userInbox.php?ReturnSuccessful");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET["ClerkInboxDownload"])) {
    $id = $_GET['ClerkInboxDownload'];

    //Select the CaseId from inbox_clerk_summary
    $stmt = $conn->prepare("SELECT V7.FileName AS V7FileName, V7.FileType AS V7FileType, V7.FileData AS V7FileData,
    V6.FileName AS V6FileName, V6.FileType AS V6FileType, V6.FileData AS V6FileData, 
    V5.FileName AS V5FileName, V5.FileType AS V5FileType, V5.FileData AS V5FileData,
    RTC.FileName AS RTCFileName, RTC.FileType AS RTCFileType, RTC.FileData AS RTCFileData,
    V4.FileName AS V4FileName, V4.FileType AS V4FileType, V4.FileData AS V4FileData,
    V3.FileName AS V3FileName, V3.FileType AS V3FileType, V3.FileData AS V3FileData, 
    V2.FileName AS V2FileName, V2.FileType AS V2FileType, V2.FileData AS V2FileData, 
    V1.FileName AS V1FileName, V1.FileType AS V1FileType, V1.FileData AS V1FileData, 
    V7.V1_CaseId, V7.V2_CaseId, V7.V3_CaseId, V7.V4_CaseId, V7.V5_CaseId, V7.v6, V7.id 
    FROM v7_interpreter_submit_summary V7 
    INNER JOIN v6_interpreter_hearing V6 ON V7.v6 = V6.id
    INNER JOIN v5_interpreter_scheduling V5 ON V6.V5_CaseId = V5.id
    INNER JOIN rtc_submit_subpoena RTC ON V5.id = RTC.V5_CaseId
    INNER JOIN v4_rtc_submit_warrant V4 ON V5.V4_CaseId = V4.id
    INNER JOIN v3_occ_case_assignment V3 ON V4.V3_CaseId = V3.id
    INNER JOIN v2_fiscal_submit_case V2 ON V3.V2_CaseId = V2.id
    INNER JOIN v1_pao_submit_case V1 ON V2.V1_CaseId = V1.id");

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create a zip archive
        $zip = new ZipArchive();
        $zipFilename = "CourtFlow_Files.zip";

        if ($zip->open($zipFilename, ZipArchive::CREATE) === true) {
            while ($row = $result->fetch_assoc()) {
                // Add each file to the zip archive
                $zip->addFromString($row['V7FileName'], $row['V7FileData']);
                $zip->addFromString($row['V6FileName'], $row['V6FileData']);
                $zip->addFromString($row['V5FileName'], $row['V5FileData']);
                $zip->addFromString($row['V5FileName'], $row['V5FileData']);
                $zip->addFromString($row['V4FileName'], $row['V4FileData']);
                $zip->addFromString($row['V3FileName'], $row['V3FileData']);
                $zip->addFromString($row['V2FileName'], $row['V2FileData']);
                $zip->addFromString($row['V1FileName'], $row['V1FileData']);
            }

            // Close the zip archive
            $zip->close();

            // Set appropriate headers to indicate the file type
            header('Content-type: application/zip');
            header("Content-Disposition: attachment; filename=$zipFilename");

            // Output the zip file to the browser
            readfile($zipFilename);

            // Delete the temporary zip file
            unlink($zipFilename);
        } else {
            echo "Failed to create the zip archive.";
        }
    } else {
        echo "No files found in the database for the given ID.";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_GET["ClerkGuiltyDownload"])) {
    $id = $_GET['ClerkGuiltyDownload'];

    $stmt = $conn->prepare("SELECT clerk.FileName AS clerkFileName, clerk.FileType AS clerkFileType, clerk.FileData AS clerkFileData,  
    v5.FileName AS v5FileName, v5.FileType AS v5FileType, v5.FileData AS v5FileData, 
    v4.FileName AS v4FileName, v4.FileType AS v4FileType, v4.FileData AS v4FileData,
    v3.FileName AS v3FileName, v3.FileType AS FileType, v3.FileData AS v3FileData, 
    v2.FileName AS v2FileName, v2.FileType AS v2FileType, v2.FileData AS v2FileData, 
    v1.FileName AS v1FileName, v1.FileType AS v1FileType, v1.FileData AS v1FileData 
    FROM inbox_clerk_guilty clerk 
    INNER JOIN v5_interpreter_scheduling v5 ON clerk.V5_CaseId = v5.id
    INNER JOIN v4_rtc_submit_warrant v4 ON v5.V4_CaseId = v4.id
    INNER JOIN v3_occ_case_assignment v3 ON v4.V3_CaseId = v3.id
    INNER JOIN v2_fiscal_submit_case v2 ON v3.V2_CaseId = v2.id
    INNER JOIN v1_pao_submit_case v1 on v2.V1_CaseId = v1.id");

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Create a zip archive
        $zip = new ZipArchive();
        $zipFilename = "CourtFlow_Files.zip";

        if ($zip->open($zipFilename, ZipArchive::CREATE) === true) {
            while ($row = $result->fetch_assoc()) {
                // Add each file to the zip archive
                $zip->addFromString($row['clerkFileName'], base64_decode($row['clerkFileData']));
                $zip->addFromString($row['v5FileName'], base64_decode($row['v5FileData']));
                $zip->addFromString($row['v4FileName'], base64_decode($row['v4FileData']));
                $zip->addFromString($row['v3FileName'], base64_decode($row['v3FileData']));
                $zip->addFromString($row['v2FileName'], base64_decode($row['v2FileData']));
                $zip->addFromString($row['v1FileName'], base64_decode($row['v1FileData']));
            }

            // Close the zip archive
            $zip->close();

            // Set appropriate headers to indicate the file type
            header('Content-type: application/zip');
            header("Content-Disposition: attachment; filename=$zipFilename");

            // Output the zip file to the browser
            readfile($zipFilename);

            // Delete the temporary zip file
            unlink($zipFilename);
        } else {
            echo "Failed to create the zip archive.";
        }
    } else {
        echo "No files found in the database for the given ID.";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['GuiltySubmit'])) {
    
    try {
        // Get an array of clerkIDs
        $clerkIDs = $_POST['clerkID'];

        
        // Loop through each clerkID
        foreach ($clerkIDs as $id) {
            
            // Count the number of files uploaded for the current clerkID
            $fileCount = count($_FILES['fileToSubmit']['name']);

            // Loop through each file for the current clerkID
            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = $_FILES['fileToSubmit']['name'][$i];
                $fileTmpName = $_FILES['fileToSubmit']['tmp_name'][$i];
                $fileSize = $_FILES['fileToSubmit']['size'][$i];
                $fileType = $_FILES['fileToSubmit']['type'][$i];
                $fileError = $_FILES['fileToSubmit']['error'][$i];
                $folder = "../pages/user/uploads";

                $fileExt = explode('.', $fileName);
                $fileActualExt = strtolower(end($fileExt));

                $allowed = array('pdf', 'docx');
                
                $stmt = $conn->prepare("SELECT isGenerated FROM inbox_clerk_guilty WHERE id = ?");
                $stmt->bind_param("i", $Id);
                $stmt->execute();
                $result = $stmt->get_result();
                $userData = $result->fetch_assoc();
                $isGenerated = $userData['isGenerated'];

                // Update the Generated status
                $newStatus = ($isGenerated == 1) ? 0 : 1;
                $stmt = $conn->prepare("UPDATE inbox_clerk_guilty SET isGenerated = ? WHERE id = ?");
                $stmt->bind_param("ii", $newStatus, $Id);
                $stmt->execute();

                if (in_array($fileActualExt, $allowed)) {
                    if ($fileError === 0) {
                        $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
                        $fileData = file_get_contents($fileTmpName);

                        // Prepare and execute the database insert statement
                        $stmt = $conn->prepare("INSERT INTO v8_clerk_guilty_followup (id_guilty, FileName, FileType, FileSize, FileData)
                          VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssss", $id, $fileNameNew, $fileType, $fileSize, $fileData);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        header("Location: ../pages/user/userInbox.php?fileError");
                        exit();
                    }
                } else {
                    header("Location: ../pages/user/userInbox.php?fileNotAllowed");
                    exit();
                }
            }
        }

        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userInbox.php?uploadSuccess");
        exit();
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage();
    }
}





