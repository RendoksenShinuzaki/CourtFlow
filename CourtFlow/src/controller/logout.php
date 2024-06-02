<?php

session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');

$loggedInRole = $_SESSION['role'] ?? '';

// Check if the logout button is clicked
if (isset($_POST['logout'])) {
    
    header('Location: ../pages/index.php');

    $trailMessage = $loggedInRole . " logged out ";
    $currentDate = date('Y-m-d');
    $currentTime = date('h:i A'); // Get the current time with AM/PM

    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'
    
    // Logout successful then insert into audit trail
    $stmt = $conn->prepare("INSERT INTO audit_trail (Message, Time, AmPm, Date)
    VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $trailMessage, $time, $ampm, $currentDate);
    $stmt->execute();
    
    session_destroy();
    unset($_SESSION['adminLoggedIn']);
    unset($_SESSION['userLoggedIn']);
    
    
    
    exit;
}

?>