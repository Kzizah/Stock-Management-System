<?php
include '../includes/connection.php';

// Debugging output
echo '<pre>';
print_r($_GET);
echo '</pre>';
exit;

// Check if 'id' and 'type' are set in the URL
if (isset($_GET['id']) && isset($_GET['type']) && $_GET['type'] == 'product') {
    $product_id = intval($_GET['id']); // Sanitize input

    // Prepare the delete query
    $query = 'DELETE FROM product WHERE PRODUCT_ID = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $product_id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">
                alert("Product Successfully Deleted.");
                window.location = "product.php";
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Error deleting product.");
                window.location = "product.php";
              </script>';
    }
} else {
    echo '<script type="text/javascript">
            alert("Invalid request.");
            window.location = "product.php";
          </script>';
}
?>