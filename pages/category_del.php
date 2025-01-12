<?php
include '../includes/connection.php';

// Check if the ID is set
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Delete the category from the database
    $delete_query = "DELETE FROM category WHERE CATEGORY_ID = ?";
    $stmt = $db->prepare($delete_query);
    $stmt->bind_param('i', $category_id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">
                alert("Category deleted successfully!");
                window.location = "category.php"; // Redirect back to category page
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Error deleting category!");
              </script>';
    }
} else {
    echo '<script type="text/javascript">
            alert("Invalid category ID!");
            window.location = "category.php"; // Redirect back to category page
          </script>';
}
?>
