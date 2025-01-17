<?php
// Include the connection file
include '../includes/connection.php';

// Check if the ID parameter exists in the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Get the supplier ID from the query string
    $supplier_id = $_GET['id'];

    // Prepare the DELETE query
    $query = "DELETE FROM supplier WHERE SUPPLIER_ID = ?";
    $stmt = mysqli_prepare($db, $query);

    // Bind the supplier ID as a parameter
    mysqli_stmt_bind_param($stmt, 'i', $supplier_id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // If the query is successful, redirect back with a success message
        echo "<script>
                alert('Supplier deleted successfully.');
                window.location.href = 'supplier.php';
              </script>";
    } else {
        // If the query fails, display an error message
        echo "<script>
                alert('Error deleting supplier. Please try again.');
                window.location.href = 'supplier.php';
              </script>";
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // If the ID is missing or invalid, redirect with an error message
    echo "<script>
            alert('Invalid request. Supplier not found.');
            window.location.href = 'supplier.php';
          </script>";
}

// Close the database connection
mysqli_close($db);
?>
