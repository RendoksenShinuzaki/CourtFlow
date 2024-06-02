<?php
session_start();
include('../connection/config.php');

if (isset($_POST['addBranches'])) {
    $branch = $_POST['branch'];

    // Check the current number of entries
    $result = $conn->query("SELECT COUNT(*) as count FROM branches");
    $row = $result->fetch_assoc();
    $currentCount = $row['count'];

    // Check if the current count is less than 12 before allowing insertion
    if ($currentCount < 12) {
        $stmt = $conn->prepare("INSERT INTO branches (Branch) VALUES (?)");
        $stmt->bind_param("s", $branch);

        // Execute the insert statement
        if ($stmt->execute()) {
            // Insert successful
            $stmt->close();
            $conn->close();
            // Redirect to a success page or any desired page
            header("Location: ../pages/admin/admin_branchManagement.php");
            exit();
        } else {
            // Insert failed
            $stmt->close();
            $conn->close();
        }
    } else {
        // Maximum limit reached
        $conn->close();
        echo "Maximum limit of 12 branches has been reached.";
    }
}
?>





