<?php
include '../includes/connection.php';

// Check if the 'id' parameter is passed
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id']; // Get the transaction ID from the URL

    // Sanitize the input
    $transaction_id = (int)$transaction_id;

    // Query to delete the transaction from the database
    $query = "DELETE FROM transaction WHERE TRANS_ID = ?";

    // Use prepared statements to avoid SQL injection
    if ($stmt = mysqli_prepare($db, $query)) {
        // Bind the transaction ID to the prepared statement
        mysqli_stmt_bind_param($stmt, 'i', $transaction_id);

        // Execute the query and check if successful
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the transaction page with a success message
            header("Location: transaction.php?delete_success=true");
            exit;  // Ensure the script stops and the redirection happens
        } else {
            // If deletion fails, show an error message
            echo "Error deleting transaction: " . mysqli_error($db);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // If query preparation fails
        echo "Failed to prepare the query: " . mysqli_error($db);
    }
} else {
    // If the 'id' parameter is missing, redirect to the transaction page
    header("Location: transaction.php");
    exit;
}

// Close the database connection
mysqli_close($db);
?>
