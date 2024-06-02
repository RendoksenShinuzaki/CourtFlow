<?php
session_start();
include('../connection/config.php');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

if (isset($_GET['pending'])) {
    $userId = $_GET['pending'];

    $stmt = $conn->prepare("SELECT isPending FROM rtc_warrant WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $WarrantData = $result->fetch_assoc();
    $isPending = $WarrantData['isPending'];

    $newStatus = ($isPending == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE rtc_warrant SET isPending = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $userId);

    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userArchived.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if(isset($_GET['finished'])) {
    $finishedId = $_GET['finished'];
    //$finished_date = $_GET['finished_date'];

    $stmt = $conn->prepare("SELECT isFinished FROM v6_interpreter_hearing WHERE id = ?");
    $stmt->bind_param("i", $finishedId);
    $stmt->execute();
    $result = $stmt->get_result();
    $HearingData = $result->fetch_assoc();
    $isFinished = $HearingData['isFinished'];

    $newStatus = ($isFinished == 1) ? 0:1;
    $stmt = $conn->prepare("UPDATE v6_interpreter_hearing SET isFinished =? WHERE id =?");
    $stmt->bind_param("ii", $newStatus,$finishedId);

    if ($stmt->execute()) {
        
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userArchived.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if (isset($_POST['reScheduleSubmit'])) {

    $id = $_POST['id'];
    $reschedulePretrialDate = $_POST['reschedulePretrialDate'];
    $reschedulePretrialTimeStart = $_POST['reschedulePretrialTimeStart'];
    $reschedulePretrialTimeEnd = $_POST['reschedulePretrialTimeEnd'];
    $currentDate = date('Y-m-d');

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE v5_interpreter_scheduling SET PreTrialDate=?, PreTrialTimeStart=?, PreTrialTimeEnd=?, DateSubmitted=? WHERE id=?");
    $stmt->bind_param("ssssi", $reschedulePretrialDate, $reschedulePretrialTimeStart, $reschedulePretrialTimeEnd, $currentDate, $id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userArchived.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}


if (isset($_GET['notGuilty'])) {
    $id = $_GET['notGuilty'];

    // Retrieve the isFinished of v5_interpreter_scheduling status
    $stmt = $conn->prepare("SELECT isFinished FROM v5_interpreter_scheduling WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ScheduleData = $result->fetch_assoc();
    $isFinished = $ScheduleData['isFinished'];

    // Update the isFinished v5_interpreter_scheduling status
    $newStatus = ($isFinished == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v5_interpreter_scheduling SET isFinished = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    // Select all data from v5_interpreter_scheduling
    $stmt = $conn->prepare("SELECT * FROM v5_interpreter_scheduling WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    $V1_CaseId = $row["V1_CaseId"];
    $V2_CaseId = $row["V2_CaseId"];
    $V3_CaseId = $row["V3_CaseId"];
    $V4_CaseId = $row["V4_CaseId"];
    $V5_CaseId = $id;

    $fromEmployee = $loggedInEmployeeId;
    $toEmployee = $row['FromRTC'];
    $docketNumber = $row['DocketNumber'];
    $fileName = $row['FileName'];
    $currentDate = date('Y-m-d');

    $currentTime = date('h:i A'); // Get the current time with AM/PM
    $trackMessage = "Accused Pleaded Not Guilty Petitioned to Subpoena";
    
    // Splitting the time and AM/PM
    $timeParts = explode(' ', $currentTime);
    $time = $timeParts[0]; // This will contain 'hh:mm:ss'
    $ampm = $timeParts[1]; // This will contain 'AM' or 'PM'

    $stmt = $conn->prepare("INSERT INTO rtc_for_subpoena (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, FromEmployee, ToEmployee, DocketNumber, FileName, DateSubmitted)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss",$V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $fromEmployee, $toEmployee, $docketNumber, $fileName, $currentDate);

    // Execute the update statement
    if ($stmt->execute()) {
        $stmt = $conn->prepare("INSERT INTO tracking (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, isInbox, Version, Message, Time, AmPm, Date)
                VALUES (?, ?, ?, ?, ?, 1, 4, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $id, $trackMessage, $time, $ampm, $currentDate);
                $stmt->execute();
        // Update successful
        $stmt->close();
        $conn->close();
        // Redirect to a success page or any desired page
        header("Location: ../pages/user/userArchived.php");
        exit();
    } else {
        // Update failed
        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guiltyId'])) {
    $id = $_POST['guiltyId'];

    // Retrieve the isFinished of v5_interpreter_scheduling status
    $stmt = $conn->prepare("SELECT isFinished, DocketNumber, V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId FROM v5_interpreter_scheduling WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $scheduleData = $result->fetch_assoc();
    $isFinished = $scheduleData['isFinished'];

    // Update the isFinished v5_interpreter_scheduling status
    $newStatus = ($isFinished == 1) ? 0 : 1;
    $stmt = $conn->prepare("UPDATE v5_interpreter_scheduling SET isFinished = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);
    $stmt->execute();

    // Select all data from v5_interpreter_scheduling
    $stmt = $conn->prepare("SELECT * FROM v5_interpreter_scheduling WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;
    $row = $result->fetch_assoc();

    $V1_CaseId = $row["V1_CaseId"];
    $V2_CaseId = $row["V2_CaseId"];
    $V3_CaseId = $row["V3_CaseId"];
    $V4_CaseId = $row["V4_CaseId"];
    $V5_CaseId = $id;

    // Handle file upload
    if ($_FILES['fileToSubmit']['error'] === 0) {
        $fileTmpName = $_FILES['fileToSubmit']['tmp_name'];
        $fileData = file_get_contents($fileTmpName);

        // Insert data into interpreter_guilty table
        $fromEmployee = $loggedInEmployeeId;
        $docketNumber = $scheduleData['DocketNumber'];
        $currentDate = date('Y-m-d');

        $stmt = $conn->prepare("INSERT INTO interpreter_guilty (V1_CaseId, V2_CaseId, V3_CaseId, V4_CaseId, V5_CaseId, FromEmployee, DocketNumber, FileName, DateSubmitted, FileData, Version)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 5)");
        $stmt->bind_param("ssssssssss", $V1_CaseId, $V2_CaseId, $V3_CaseId, $V4_CaseId, $V5_CaseId, $fromEmployee, $docketNumber, $_FILES['fileToSubmit']['name'], $currentDate, $fileData);

        // Execute the insert statement
        if ($stmt->execute()) {
            // Insert successful
            $stmt->close();
            $conn->close();
            // Redirect to a success page or any desired page
            header("Location: ../pages/user/userArchived.php");
            exit();
        } else {
            // Insert failed
            $stmt->close();
            $conn->close();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    $documentId = $_POST["DocumentId"];

    // Check if files were uploaded
    if (isset($_FILES["fileToSubmit"])) {
        $fileCount = count($_FILES["fileToSubmit"]["name"]);

        // Iterate through each uploaded file
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES["fileToSubmit"]["name"][$i];
            $fileTmpName = $_FILES["fileToSubmit"]["tmp_name"][$i];

            // Move the uploaded file to a desired directory
            $uploadPath = "path/to/your/upload/directory/" . $fileName;
            move_uploaded_file($fileTmpName, $uploadPath);

            // Insert file information into the database
            $sql = "INSERT INTO your_table_name (DocumentId, FileName) VALUES ('$documentId', '$fileName')";
            // Execute the SQL query (you should use prepared statements for security)
            $conn->query($sql);
        }
    }

    // Additional code after form submission if needed
    // Redirect to another page, show a success message, etc.
    header("Location: success.php");
    exit();
}


