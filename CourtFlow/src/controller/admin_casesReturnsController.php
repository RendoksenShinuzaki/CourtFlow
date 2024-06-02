<?php

session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');

if (isset($_GET['ReturnAccept'])) {
    $id = $_GET['ReturnAccept'];
    
    // Query to get the all the data from returns
    $stmt = $conn->prepare("SELECT * FROM returns WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from returns transfer into new variables
    $CaseId = $row['CaseId'];
    $Version = $row['Version'];
    $FromEmployee = $row['FromEmployee'];
    $toEmployee = $row['ToEmployee'];
    

    if ($Version == 1) {
        // Retrieve the v1_pao_submit_case isReturnApproved status
        $stmt = $conn->prepare("SELECT isReturnApproved FROM v1_pao_submit_case WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturnApproved = $userData['isReturnApproved'];

        // Update the isReturnApproved status
        $newStatus = ($isReturnApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET isReturnApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        if($isReturnApproved == 1){
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "File Returned and Approved",
                text: "The file has been returned and approved.",
            });
          </script>';
        }
        
        // Retrieve the returns isApproved status
        $stmt = $conn->prepare("SELECT isApproved FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isApproved = $userData['isApproved'];
        
        //Update from returns isApproved
        $newStatus = ($isApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE returns SET isApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $id);
        $stmt->execute();
    }
    if ($Version == 2) {
        // Retrieve the v2_fiscal_submit_case isReturnApproved status
        $stmt = $conn->prepare("SELECT isReturnApproved FROM v2_fiscal_submit_case WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturnApproved = $userData['isReturnApproved'];

        // Update the isReturnApproved status
        $newStatus = ($isReturnApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET isReturnApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        if($isReturnApproved == 1){
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "File Returned and Approved",
                text: "The file has been returned and approved.",
            });
          </script>';
        }
        
        // Retrieve the returns isApproved status
        $stmt = $conn->prepare("SELECT isApproved FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isApproved = $userData['isApproved'];
        
        //Update from returns isApproved
        $newStatus = ($isApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE returns SET isApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $id);
        $stmt->execute();
    }
    if ($Version == 3) {
        // Retrieve the v3_occ_case_assignment isReturnApproved status
        $stmt = $conn->prepare("SELECT isReturnApproved FROM v3_occ_case_assignment WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturnApproved = $userData['isReturnApproved'];

        // Update the isReturnApproved status
        $newStatus = ($isReturnApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET isReturnApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        if($isReturnApproved == 1){
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "File Returned and Approved",
                text: "The file has been returned and approved.",
            });
          </script>';
        }
        
        // Retrieve the returns isApproved status
        $stmt = $conn->prepare("SELECT isApproved FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isApproved = $userData['isApproved'];
        
        //Update from returns isApproved
        $newStatus = ($isApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE returns SET isApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $id);
        $stmt->execute();
    }
    if ($Version == 4) {
        // Retrieve the v3_occ_case_assignment isReturnApproved status
        $stmt = $conn->prepare("SELECT isReturnApproved FROM v4_rtc_submit_warrant WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturnApproved = $userData['isReturnApproved'];

        // Update the isReturnApproved status
        $newStatus = ($isReturnApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET isReturnApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        if($isReturnApproved == 1){
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "File Returned and Approved",
                text: "The file has been returned and approved.",
            });
          </script>';
        }
        
        // Retrieve the returns isApproved status
        $stmt = $conn->prepare("SELECT isApproved FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isApproved = $userData['isApproved'];
        
        //Update from returns isApproved
        $newStatus = ($isApproved == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE returns SET isApproved = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $id);
        $stmt->execute();
    }

    if ($stmt->execute()) {
        $trailMessage = "Admin accepted the return of a case file from " . $FromEmployee . " to " .  $toEmployee;
        $currentDate = date('Y-m-d');
        $currentTime = date('h:i A'); // Get the current time with AM/PM
    
        // Splitting the time and AM/PM
        $timeParts = explode(' ', $currentTime);
        $time = $timeParts[0]; // This will contain 'hh:mm:ss'
        $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
        
         // Insert successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/admin/admin_casesReturns.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['ReturnDecline'])) {
    $id = $_GET['ReturnDecline'];

    // Query to get the all the data from returns
    $stmt = $conn->prepare("SELECT * FROM returns WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from returns transfer into new variables
    $CaseId = $row['CaseId'];
    $Version = $row['Version'];

    if ($Version == 1) {
        // Retrieve the v1_pao_submit_case isReturn status
        $stmt = $conn->prepare("SELECT isReturn FROM v1_pao_submit_case WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturn = $userData['isReturn'];

        // Update the isReturn status
        $newStatus = ($isReturn == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v1_pao_submit_case SET isReturn = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
    }
    if ($Version == 2) {
        // Retrieve the v2_fiscal_submit_case isReturn status
        $stmt = $conn->prepare("SELECT isReturn FROM v2_fiscal_submit_case WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturn = $userData['isReturn'];

        // Update the isReturn status
        $newStatus = ($isReturn == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v2_fiscal_submit_case SET isReturn = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
    }
    if ($Version == 3) {
        // Retrieve the v3_occ_case_assignment isReturn status
        $stmt = $conn->prepare("SELECT isReturn FROM v3_occ_case_assignment WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturn = $userData['isReturn'];

        // Update the isReturn status
        $newStatus = ($isReturn == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v3_occ_case_assignment SET isReturn = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
    }
    if ($Version == 4) {
        // Retrieve the v4_rtc_submit_warrant isReturn status
        $stmt = $conn->prepare("SELECT isReturn FROM v4_rtc_submit_warrant WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $isReturn = $userData['isReturn'];

        // Update the isReturn status
        $newStatus = ($isReturn == 1) ? 0 : 1;
        $stmt = $conn->prepare("UPDATE v4_rtc_submit_warrant SET isReturn = ? WHERE id = ?");
        $stmt->bind_param("ii", $newStatus, $CaseId);
        $stmt->execute();

        //Delete from returns
        $stmt = $conn->prepare("DELETE FROM returns WHERE id = ?");
        $stmt->bind_param("i", $id);
    }

    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/admin/admin_casesReturns.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_GET['ViewFile'])) {
    $id = $_GET['ViewFile'];
    
    // Query to get the all the data from returns
    $stmt = $conn->prepare("SELECT * FROM returns WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    // Data from returns transfer into new variables
    $CaseId = $row['CaseId'];
    $Version = $row['Version'];
    
    if ($Version == 1) {
        // Retrieve the v1_pao_submit_case isReturn status
        $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v1_pao_submit_case WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();
        
        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
    }
    if ($Version == 2) {
        // Retrieve the v2_fiscal_submit_case isReturn status
        $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v2_fiscal_submit_case WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();
        
        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
    }
    if ($Version == 3) {
        // Retrieve the v3_occ_case_assignment isReturn status
        $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v3_occ_case_assignment WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();
        
        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
    }
    if ($Version == 4) {
        // Retrieve the v3_occ_case_assignment isReturn status
        $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM v4_rtc_submit_warrant WHERE id = ?");
        $stmt->bind_param("i", $CaseId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($fileName, $fileType, $fileData);
        $stmt->fetch();
        
        // Set appropriate headers to indicate the file type
        header("Content-type: $fileType");
        header("Content-Disposition: inline; filename=$fileName");

        // Output the file data to the browser
        echo $fileData;
    }
    
    $stmt->close();
    $conn->close();
}