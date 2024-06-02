<?php
session_start();
include('../connection/config.php');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';
date_default_timezone_set('Asia/Manila');

if (isset($_POST['PaoEditSubmitButton'])) {
    // Retrieve the form data
    $id = $_POST['id'];
    $returnId = $_POST['returnId'];
    $ComplainantLastName = $_POST['ComplainantLastName'];
    $ComplainantFirstName = $_POST['ComplainantFirstName'];
    $ComplainantMiddleName = $_POST['ComplainantMiddleName'];
    $AccusedLastName = $_POST['AccusedLastName'];
    $AccusedFirstName = $_POST['AccusedFirstName'];
    $AccusedMiddleName = $_POST['AccusedMiddleName'];
    $trailMessage = $loggedInEmployeeId . " successfully edited a returned case file";
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
    
    $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
    $fileData = file_get_contents($fileTmpName);
    
    $accepted = 0;
    $isReturn = 0;
    $isReturnApproved = 0;
    
    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET complainantLName=?, complainantFName=?, complainantMName=?, accusedLName=?, accusedFName=?, accusedMName=?, FileName=?, FileType=?, FileSize=?, FileData=?, Date=?, Accepted=?, isReturn=?, isReturnApproved=? WHERE id=?");
    $stmt->bind_param("ssssssssssssssi", $ComplainantLastName, $ComplainantFirstName, $ComplainantMiddleName, $AccusedLastName, $AccusedFirstName, $AccusedMiddleName, $fileNameNew, $fileType, $fileSize, $fileData, $currentDate, $accepted, $isReturn, $isReturnApproved, $id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $returnId);
        $stmt->execute();
                
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userRetrievedDocuments.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();

    }
}

if (isset($_POST['FiscalEditSubmitButton'])) {
    // Retrieve the form data
    $id = $_POST['id'];
    $returnId = $_POST['returnId'];
    $penalty = $_POST['penalty'];
    $trailMessage = $loggedInEmployeeId . " successfully edited a returned case file";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    // Select  Penalty
    $stmt = $conn->prepare("SELECT * FROM penalties WHERE id=?");
    $stmt->bind_param("s", $penalty);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $penaltyResult = $result->fetch_assoc();

    $newPenalty = "Republic Act " . $penaltyResult['RepublicAct'] . ", Article " . $penaltyResult['Article'] . ", Section " . $penaltyResult['Section'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileError = $_FILES['file']['error'];
    $folder = "../pages/user/uploads";

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('pdf', 'docx');
    
    $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
    $fileData = file_get_contents($fileTmpName);
    
    $accepted = 0;
    $isReturn = 0;
    $isReturnApproved = 0;
    
    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET Penalty=?, FileName=?, FileType=?, FileSize=?, FileData=?, DateSubmitted=?, Accepted=?, isReturn=?, isReturnApproved=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $newPenalty, $fileNameNew, $fileType, $fileSize, $fileData, $currentDate, $accepted, $isReturn, $isReturnApproved, $id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $returnId);
        $stmt->execute();
                
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userRetrievedDocuments.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();

    }
}

if (isset($_POST['OCCEditSubmitButton'])) {
    // Retrieve the form data
    $id = $_POST['id'];
    $returnId = $_POST['returnId'];
    $trailMessage = $loggedInEmployeeId . " successfully edited a returned case file";
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
    
    $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
    $fileData = file_get_contents($fileTmpName);
    
    $accepted = 0;
    $isReturn = 0;
    $isReturnApproved = 0;
    
    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET FileName=?, FileType=?, FileSize=?, FileData=?, DateSubmitted=?, Accepted=?, isReturn=?, isReturnApproved=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $fileNameNew, $fileType, $fileSize, $fileData, $currentDate, $accepted, $isReturn, $isReturnApproved, $id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $returnId);
        $stmt->execute();
                
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userRetrievedDocuments.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();

    }
}

if (isset($_POST['RTCEditSubmitButton'])) {
    // Retrieve the form data
    $id = $_POST['id'];
    $returnId = $_POST['returnId'];
    $trailMessage = $loggedInEmployeeId . " successfully edited a returned case file";
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
    
    $fileNameNew = $loggedInEmployeeId . " - " . $fileName;
    $fileData = file_get_contents($fileTmpName);
    
    $accepted = 0;
    $isReturn = 0;
    $isReturnApproved = 0;
    
    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET FileName=?, FileType=?, FileSize=?, FileData=?, DateSubmitted=?, Accepted=?, isReturn=?, isReturnApproved=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $fileNameNew, $fileType, $fileSize, $fileData, $currentDate, $accepted, $isReturn, $isReturnApproved, $id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $returnId);
        $stmt->execute();
                
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userRetrievedDocuments.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();

    }
}

if (isset($_GET['PAOViewFile'])) {
    $id = $_GET['PAOViewFile'];
    $trailMessage = $loggedInEmployeeId . " downloaded a file from his/her retrieved documents section";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v1_pao_submit_case WHERE id = ?");
    $stmt->bind_param("i", $id);
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

if (isset($_GET['FiscalViewFile'])) {
    $id = $_GET['FiscalViewFile'];
    $trailMessage = $loggedInEmployeeId . " downloaded a file from his/her retrieved documents section";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v2_fiscal_submit_case WHERE id = ?");
    $stmt->bind_param("i", $id);
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

if (isset($_GET['OCCViewFile'])) {
    $id = $_GET['OCCViewFile'];
    $trailMessage = $loggedInEmployeeId . " downloaded a file from his/her retrieved documents section";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v3_occ_case_assignment WHERE id = ?");
    $stmt->bind_param("i", $id);
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

if (isset($_GET['RTCViewFile'])) {
    $id = $_GET['RTCViewFile'];
    $trailMessage = $loggedInEmployeeId . " downloaded a file from his/her retrieved documents section";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v4_rtc_submit_warrant WHERE id = ?");
    $stmt->bind_param("i", $id);
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
?>
