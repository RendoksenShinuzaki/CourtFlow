<?php
session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_POST['addPenalty'])) {
    $empId = $_POST['empId'];
    $republicact = $_POST['addRA'];
    $article = $_POST['addarticle'];
    $section = $_POST['addsection'];
    $caseFine = $_POST['addCaseFine'];
    $bailable = $_POST['bailable'];
    $trailMessage = $empId . " successfully added a penalty";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Check if the Republic Act already exists
    $checkStmt = $conn->prepare("SELECT RepublicAct FROM penalties WHERE RepublicAct = ?");
    $checkStmt->bind_param("s", $republicact);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // If the Republic Act already exists, display an error message
        echo "Republic Act already exists. Please enter a different Republic Act.";
    } else {
        // Insert the new record since the Republic Act does not exist
        $stmt = $conn->prepare("INSERT INTO penalties (EmployeeId, RepublicAct, Article, Section, CaseFine, Bailable)
                    VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $empId, $republicact, $article, $section, $caseFine, $bailable);

        if ($stmt->execute()) {
            // Insert successful then insert into audit trail
            $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
            VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
            $stmt->execute();
                
            $stmt->close();
            $conn->close();
            // Redirect to a success page or any desired page
            header("Location: ../pages/user/userMatrixPenalties.php");
            exit();
        } else {
            // Insert failed
            $stmt->close();
            $conn->close();
        }
    }
}

if (isset($_POST['delete'])) {
    // Retrieve the ID of the record to be deleted
    $id = $_POST['id'];
    $trailMessage = $loggedInEmployeeId . " deleted a penalty";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM penalties WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the delete statement
    if ($stmt->execute()) {
        // Delete successful then insert into audit trail
        $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
        VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userMatrixPenalties.php");
        exit();
    } else {
        // Deletion failed
        $stmt->close();
        $conn->close();
        // Handle the error or redirect to an error page
    }
}
?>