<?php

session_start();
include('../connection/config.php');

if (isset($_GET['view'])) {
    $Id = $_GET['view'];

    $stmt = $conn->prepare("SELECT FileName, FileType, FileData FROM fiscal_submitcase WHERE id = ?");
    $stmt->bind_param("i", $Id);
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
    } else {
        echo "File not found in the database.";
    }
    
    $stmt->close();
    $conn->close();
}

?>