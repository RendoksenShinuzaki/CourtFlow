<?php

session_start();
include('../connection/config.php');


// Function to generate a unique ID based on the role
function generateUniqueId($roleName, $userCount) {
    $prefix = strtoupper($roleName);
    $userCountStr = str_pad($userCount, 4, '0', STR_PAD_LEFT);
    return $prefix . '-' . $userCountStr;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verification($email,$verify_token)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'courtflowmail@gmail.com';
    $mail->Password = 'eafe kyxt gnln peuz';

    $mail->SMTPSecure = "ssl";           
    $mail->Port = 465;

    $mail->setFrom('courtflowmail@gmail.com',"CourtFlow");
    $mail->addAddress($email);

    $mail->isHTML(true);                                  
    $mail->Subject = 'Email Verification from CourtFlow';

    $email_template = "<h2> You have been added on CourtFlow </h2>
                       <h3> Please verify your email address within the given link. </h3>
                       <br><br>
                       <a href='http://localhost/CourtFlow/src/controller/userverificationController.php?token=$verify_token'>Verify</a>";

    $mail->Body = $email_template;
    $mail->send();
    //echo 'Message has been sent';
}


// Add User
if (isset($_POST['addUser'])) {
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $contact = $_POST['contactNum'];
    $password = $_POST['password'];
    $verify_token = md5(rand());

    // Check if email already exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    // If email already exists, display an error message
    if ($count > 0) {
        $_SESSION['status'] = "Registration Failed";
        header("Location: ../pages/admin/admin_userManagement.php");
        exit(); // Exit to prevent further execution of the script
    } else {
        // If email does not exist, prepare the insert statement

        // Get the maximum user count for the given role
        $roleStmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING(EmployeeId, -4) AS SIGNED)) AS maxCount FROM users WHERE Role=?");
        $roleStmt->bind_param("s", $role);
        $roleStmt->execute();
        $roleResult = $roleStmt->get_result();
        $roleRow = $roleResult->fetch_assoc();
        $userCount = $roleRow['maxCount'] + 1;


        // Generate a unique ID based on the role
        $uniqueId = generateUniqueId($role, $userCount);

        // Set the branch value based on the role
        $branch = ($role === 'RTC') ? $_POST['branch'] : 'None';

        // Prepare the insert statement
        $stmt = $conn->prepare("INSERT INTO users (EmployeeId, Branch, LastName, FirstName, MiddleName, Gender, Role, Email, ContactNumber, Password, verify_token, isActive)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sssssssssss", $uniqueId, $branch, $lname, $fname, $mname, $gender, $role, $email, $contact, $password, $verify_token);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT * FROM branches WHERE Branch=?");
        $stmt->bind_param("s", $branch);
        $stmt->execute();
        $result = $stmt->get_result();
        $num = $result->num_rows;

        sendemail_verification("$email","$verify_token");
        $_SESSION['status'] = "Registration Successful wait for them to Verify the Email Address!";
        header("Location:../pages/admin/admin_userManagement.php");
        exit(0);

        if($num > 0){
            $isAvailable = 1;
            $stmt = $conn->prepare("UPDATE branches SET IsAvailable=? WHERE Branch=?");
            $stmt->bind_param("si", $isAvailable, $branch);
            $stmt->execute();
        }



        // Execute the insert statement
        if ($stmt->execute()) {
            // Insert successful
            $stmt->close();
            $conn->close();
            // Redirect to a success page or any desired page
            header("Location: ../pages/admin/admin_userManagement.php");
            exit();
            } else {
            // Insert failed
            $stmt->close();
            $conn->close();
            echo "Error: " . $stmt->error; // Add error handling
        }
    }
}

if (isset($_POST['update'])) {
    // Retrieve the form data
    $id = $_POST['id'];
    $lastName = $_POST['editLname'];
    $firstName = $_POST['editFname'];
    $middleName = $_POST['editMname'];
    $gender = $_POST['editGender'];
    $contact = $_POST['editContactNum'];
    $password = $_POST['editPassword'];

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE users SET LastName=?, FirstName=?, MiddleName=?, Gender=?, ContactNumber=?, Password=? WHERE id=?");
    $stmt->bind_param("ssssssi", $lastName, $firstName, $middleName, $gender, $contact, $password, $id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/admin/admin_userManagement.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();

    }
}


// Check if the ban button is clicked
if (isset($_GET['ban'])) {
    $userId = $_GET['ban'];

    // Retrieve the user's isActive status
    $stmt = $conn->prepare("SELECT isActive FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $isActive = $userData['isActive'];

    // Update the isActive status
    $newStatus = ($isActive == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE users SET isActive = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $userId);
    // Execute the update statement
    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/admin/admin_userManagement.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();

    }
}
?>