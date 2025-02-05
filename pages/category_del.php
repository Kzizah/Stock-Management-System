<?php
include '../includes/connection.php';

function redirectWithMessage($message, $location = '') {
    echo "<script type='text/javascript'>
            alert('$message');
            " . ($location ? "window.location = '$location';" : "") . "
          </script>";
    if ($location) {
        exit;
    }
}

function checkForExistingProducts($db, $category_id) {
    $query = "SELECT COUNT(*) as count FROM product WHERE CATEGORY_ID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'] > 0;
}

function deleteCategory($db, $category_id) {
    try {
        // Begin transaction
        $db->begin_transaction();

        // Check for existing products
        if (checkForExistingProducts($db, $category_id)) {
            $db->rollback();
            return [
                'success' => false,
                'message' => 'Cannot delete category: There are products associated with this category. Please remove or reassign these products first.'
            ];
        }

        // Proceed with deletion if no products exist
        $delete_query = "DELETE FROM category WHERE CATEGORY_ID = ?";
        $stmt = $db->prepare($delete_query);
        $stmt->bind_param('i', $category_id);
        
        if ($stmt->execute()) {
            $db->commit();
            return [
                'success' => true,
                'message' => 'Category deleted successfully!'
            ];
        } else {
            $db->rollback();
            return [
                'success' => false,
                'message' => 'Error deleting category: ' . $db->error
            ];
        }
    } catch (Exception $e) {
        $db->rollback();
        return [
            'success' => false,
            'message' => 'Error deleting category: ' . $e->getMessage()
        ];
    }
}

function validateCategoryId() {
    if (!isset($_GET['id'])) {
        redirectWithMessage('Invalid category ID!', 'category.php');
        return false;
    }
    return true;
}

function handleCategoryDeletion($db) {
    // Validate category ID
    if (!validateCategoryId()) {
        return;
    }

    $category_id = $_GET['id'];
    
    // Attempt to delete the category
    $result = deleteCategory($db, $category_id);
    
    if ($result['success']) {
        redirectWithMessage($result['message'], 'category.php');
    } else {
        redirectWithMessage($result['message'], 'category.php');
    }
}

// Main execution
handleCategoryDeletion($db);
?>