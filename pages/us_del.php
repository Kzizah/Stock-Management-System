<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include '../includes/connection.php';

// Ensure the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];  // Get the user ID from the URL

    // Sanitize the user_id to prevent SQL injection (assuming it's an integer)
    $user_id = (int)$user_id;

    // Prepare the SQL query to delete the user from the database
    $query = "DELETE FROM users WHERE ID = ?";

    // Use prepared statements to execute the query securely
    if ($stmt = mysqli_prepare($db, $query)) {
        // Bind the user_id parameter to the prepared statement
        mysqli_stmt_bind_param($stmt, 'i', $user_id);

        // Execute the query and check if successful
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the user page with a success message
            header("Location: user.php?delete_success=true");
            exit;  // Ensure the script stops and the redirection happens
        } else {
            // If deletion fails, show an error message
            echo "Error deleting user: " . mysqli_error($db);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // If query preparation fails
        echo "Failed to prepare the query: " . mysqli_error($db);
    }
} else {
    // If the 'id' parameter is missing, redirect to the user page
    header("Location: user.php");
    exit;
}

// Close the database connection
mysqli_close($db);
?>
