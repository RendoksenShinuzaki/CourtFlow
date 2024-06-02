<?php
session_start();
include('../../connection/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['update']) && $_POST['update'] == "update"){
    $array = $_POST['arrayorder'];
    $RouteName = $_POST['RouteName'];

    // Check if RouteName is not empty
    if (!empty($RouteName)) {
        // Prepare the SQL statement
        $sql = "INSERT INTO test_route (id, route_name, dept_sequence) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Get the sequence values from 'arrayorder'
        parse_str($array, $sequenceValues);

        // Concatenate the sequence values
        $deptSequence = implode(',', $sequenceValues['arrayorder']);

        // Insert into the database
        $stmt->bind_param("iss", $idval, $RouteName, $deptSequence);

        // Execute the prepared statement
        if($stmt->execute()){
            echo "Route Inserted\n";
        } else {
            echo "Error inserting route: " . $stmt->error . "\n";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo 'RouteName cannot be empty';
    }
} else {
    echo 'Invalid request';
}
?>
