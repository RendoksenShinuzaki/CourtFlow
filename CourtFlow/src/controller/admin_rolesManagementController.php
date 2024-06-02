<?php

session_start();
include('../connection/config.php');

//Add Role
if (isset($_POST['AddRoleBtn'])) {
    $role = $_POST['addRole'];
    $caseHistory = isset($_POST['caseHistory']) ? 1 : 0;
    $route = isset($_POST['route']) ? 1 : 0;
    $submitCase = isset($_POST['submitCase']) ? 1 : 0;
    $submitWarrant = isset($_POST['submitWarrant']) ? 1 : 0;
    $retrievedDocuments = isset($_POST['retrievedDocuments']) ? 1 : 0;
    $matrixPenalties = isset($_POST['matrixPenalties']) ? 1 : 0;
    $sentCases = isset($_POST['sentCases']) ? 1 : 0;
    $inbox = isset($_POST['inbox']) ? 1 : 0;
    $caseAssignment = isset($_POST['caseAssignment']) ? 1 : 0;
    $retrievedCases = isset($_POST['retrievedCases']) ? 1 : 0;
    $warrantArrest = isset($_POST['warrantArrest']) ? 1 : 0;
    $archived = isset($_POST['archived']) ? 1 : 0;
    $scheduledHearing = isset($_POST['scheduledHearing']) ? 1 : 0;
    $documents = isset($_POST['documents']) ? 1 : 0;
    $caseList = isset($_POST['caseList']) ? 1 : 0;
    $notification = isset($_POST['notification']) ? 1 : 0;
    $QRScanner = isset($_POST['QRScanner']) ? 1 : 0;

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO roles (Role, CaseHistory, Route, SubmitCase, SubmitWarrant RetrievedDocuments, MatrixPenalties, SentCases, Inbox, CaseAssignment, RetrievedCases, WarrantArrest, Archived, ScheduledHearing, Documents, CaseList, Notification, QRScanner) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssssss", $role, $caseHistory, $route, $submitCase, $submitWarrant, $retrievedDocuments, $matrixPenalties, $sentCases, $inbox, $caseAssignment, $retrievedCases, $warrantArrest, $archived, $scheduledHearing, $documents, $caseList, $notification,$QRScanner);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful
        $stmt->close();
        $conn->close();

        // Redirect to a success page or any desired page
        header("Location: ../pages/admin/admin_rolesManagement.php?AddSuccessful");
        exit();
    } else {
        // Insert failed
        $stmt->close();
        $conn->close();

        // Handle the insert failure, display an error message, or redirect to an error page
        // ...
    }
}


// Edit Role
if (isset($_POST['EditRoleBtn'])) {
    $id = $_POST['id'];
    $role = $_POST['editRole'];
    $caseHistory = isset($_POST['editCaseHistory']) ? 1 : 0;
    $route = isset($_POST['editRoute']) ? 1 : 0;
    $submitCase = isset($_POST['editSubmitCase']) ? 1 : 0;
    $submitWarrant = isset($_POST['editSubmitWarrant']) ? 1 : 0;
    $retrievedDocuments = isset($_POST['editRetrievedDocuments']) ? 1 : 0;
    $matrixPenalties = isset($_POST['editMatrixPenalties']) ? 1 : 0;
    $sentCases = isset($_POST['editSentCases']) ? 1 : 0;
    $inbox = isset($_POST['editInbox']) ? 1 : 0;
    $caseAssignment = isset($_POST['editCaseAssignment']) ? 1 : 0;
    $retrievedCases = isset($_POST['editRetrievedCases']) ? 1 : 0;
    $warrantArrest = isset($_POST['editWarrantArrest']) ? 1 : 0;
    $archived = isset($_POST['editArchived']) ? 1 : 0;
    $scheduledHearing = isset($_POST['editScheduledHearing']) ? 1 : 0;
    $documents = isset($_POST['editDocuments']) ? 1 : 0;
    $caseList = isset($_POST['editCaseList']) ? 1 : 0;
    $notification = isset($_POST['editNotification']) ? 1 : 0;
    $QRScanner = isset($_POST['editQrScanner']) ? 1 : 0;

    // Prepare the insert statement
    $stmt = $conn->prepare("UPDATE roles SET Role=?, CaseHistory=?, Route=?, SubmitCase=?, SubmitWarrant=?, RetrievedDocuments=?, MatrixPenalties=?, SentCases=?, Inbox=?, CaseAssignment=?, RetrievedCases=?, WarrantArrest=?, Archived=?, ScheduledHearing=?, Documents=?, CaseList=? , Notification=?, QRScanner=? WHERE id=?");
    $stmt->bind_param("ssssssssssssssssssi", $role, $caseHistory, $route, $submitCase, $submitWarrant, $retrievedDocuments, $matrixPenalties, $sentCases, $inbox, $caseAssignment, $retrievedCases, $warrantArrest, $archived, $scheduledHearing, $documents, $caseList, $notification, $QRScanner, $id);

    // Execute the insert statement
    if ($stmt->execute()) {
        // Insert successful
        $stmt->close();
        $conn->close();

        // Redirect to a success page or any desired page
        header("Location: ../pages/admin/admin_rolesManagement.php?EditSuccessful");
        exit();
    } else {
        // Insert failed
        $stmt->close();
        $conn->close();

        // Handle the insert failure, display an error message, or redirect to an error page
        // ...
    }
}
?>