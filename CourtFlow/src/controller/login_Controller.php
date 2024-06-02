<?php

session_start();
include('../connection/config.php');
date_default_timezone_set('Asia/Manila');


if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user credentials match in the users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email=? AND Password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;

    if($num > 0){
        $row = $result->fetch_assoc();
        $_SESSION['employeeId'] = $row['EmployeeId'];
        $_SESSION['branch'] = $row['Branch'];
        $_SESSION['lastName'] = $row['LastName'];
        $_SESSION['firstName'] = $row['FirstName'];
        $_SESSION['middleName'] = $row['MiddleName'];
        $_SESSION['gender'] = $row['Gender'];
        $_SESSION['role'] = $row['Role'];
        $_SESSION['active'] = $row['isActive'];
        $_SESSION['userLoggedIn'] = true;
        header("location: ../pages/user/userWelcome.php");
        
        $trailMessage = $row['EmployeeId'] . " logged in ";
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
        
        exit();
    } else {
        // Check if user credentials match in the admins table
        $stmt = $conn->prepare("SELECT * FROM admins WHERE Email=? AND Password=?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $admins = $result->num_rows;

        if($admins > 0){
            $row = $result->fetch_assoc();
            $_SESSION['role'] = $row['Role'];
            $_SESSION['adminLoggedIn'] = true;
            header("location:../pages/admin/adminDashboard.php");
            
            $trailMessage = $row['Role'] . " logged in ";
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
        
            exit();
        } else {
            header("location:../pages/index.php");
            exit();
        }
    }
}
?>