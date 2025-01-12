\<?php
// Include necessary files
include '../includes/connection.php';
include '../includes/sidebar.php';

// Check if 'action' and 'id' are set in the URL
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Delete the customer from the database
    $query = "DELETE FROM customer WHERE CUST_ID = $customer_id";
    $result = mysqli_query($db, $query);

    // Check if the delete query was successful
    if ($result) {
        // Redirect to customer page with success message
        echo '<script type="text/javascript">
                alert("Customer successfully deleted.");
                window.location = "customer.php";
              </script>';
    } else {
        // If deletion fails, show an error
        echo '<script type="text/javascript">
                alert("Error deleting customer. Please try again.");
                window.location = "customer.php";
              </script>';
    }
} else {
    // If parameters are missing or invalid, show an error and redirect
    echo '<script type="text/javascript">
            alert("Invalid request. Please try again.");
            window.location = "customer.php";
          </script>';
}
?>
