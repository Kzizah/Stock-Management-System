<?php
include '../includes/connection.php';

// Check if the 'id' parameter is passed
if (isset($_GET['id'])) {
    $supplier_id = $_GET['id']; // Get the supplier ID from the URL

    // Sanitize the input
    $supplier_id = (int)$supplier_id;

    // Query to delete the supplier from the database
    $query = "DELETE FROM supplier WHERE SUPPLIER_ID = ?";

    // Use prepared statements to avoid SQL injection
    if ($stmt = mysqli_prepare($db, $query)) {
        // Bind the supplier ID to the prepared statement
        mysqli_stmt_bind_param($stmt, 'i', $supplier_id);

        // Execute the query and check if successful
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the supplier page with a success message
            header("Location: supplier.php?delete_success=true");
            exit;  // Ensure the script stops and the redirection happens
        } else {
            // If deletion fails, show an error message
            echo "Error deleting supplier: " . mysqli_error($db);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // If query preparation fails
        echo "Failed to prepare the query: " . mysqli_error($db);
    }
} else {
    // If the 'id' parameter is missing, redirect to the supplier page
    header("Location: supplier.php");
    exit;
}

// Close the database connection
mysqli_close($db);
?>
