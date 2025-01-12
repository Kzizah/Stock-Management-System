<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include '../includes/connection.php';

// Ensure the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];  // Get the product ID from the URL

    // Sanitize the product_id to prevent SQL injection (assuming it's an integer)
    $product_id = (int)$product_id;

    // Prepare the SQL query to delete the product from the database
    $query = "DELETE FROM product WHERE PRODUCT_ID = ?";

    // Use prepared statements to execute the query securely
    if ($stmt = mysqli_prepare($db, $query)) {
        // Bind the product_id parameter to the prepared statement
        mysqli_stmt_bind_param($stmt, 'i', $product_id);

        // Execute the query and check if successful
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the product page with a success message
            header("Location: product.php?delete_success=true");
            exit;  // Ensure the script stops and the redirection happens
        } else {
            // If deletion fails, show an error message
            echo "Error deleting product: " . mysqli_error($db);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // If query preparation fails
        echo "Failed to prepare the query: " . mysqli_error($db);
    }
} else {
    // If the 'id' parameter is missing, redirect to the product page
    header("Location: product.php");
    exit;
}

// Close the database connection
mysqli_close($db);
?>
