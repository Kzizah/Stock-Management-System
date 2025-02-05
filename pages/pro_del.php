<?php
// Enable error reporting for debugging purposes (only in development environments)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include '../includes/connection.php';

// Function to sanitize and validate product ID
function sanitizeProductId($id) {
    // Sanitize the product_id to prevent SQL injection and ensure it's a valid integer
    $product_id = (int)$id;

    // Check if product_id is valid (greater than 0)
    return ($product_id > 0) ? $product_id : false;
}

// Function to delete a product by ID
function deleteProduct($db, $product_id) {
    $query = "DELETE FROM product WHERE PRODUCT_ID = ?";

    if ($stmt = mysqli_prepare($db, $query)) {
        // Bind the product_id parameter to the prepared statement
        mysqli_stmt_bind_param($stmt, 'i', $product_id);

        // Execute the query and check if successful
        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        return false;
    }
}

// Function to redirect with a message
function redirectToProductPage($status) {
    $statusMessage = ($status === 'success') ? 'delete_success=true' : 'delete_error=true';
    header("Location: product.php?$statusMessage");
    exit;  // Ensure the script stops and the redirection happens
}

// Ensure the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $product_id = sanitizeProductId($_GET['id']);  // Sanitize the product ID

    // If the product ID is valid, delete the product
    if ($product_id) {
        $deleteSuccess = deleteProduct($db, $product_id);
        // Redirect based on the result of the delete operation
        redirectToProductPage($deleteSuccess ? 'success' : 'error');
    } else {
        // If product_id is invalid, redirect with an error
        redirectToProductPage('error');
    }
} else {
    // If the 'id' parameter is missing, redirect to the product page
    header("Location: product.php");
    exit;
}

// Close the database connection
mysqli_close($db);
?>
