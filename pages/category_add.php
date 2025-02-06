<?php
//session_start(); // Start the session at the beginning

include '../includes/connection.php'; // Include your database connection
include '../includes/sidebar.php'; // Include your sidebar
include '../pages/functions.php'; // Include your functions

// Call the checkUser  Access function to verify user access
checkUserAccess($db); // Call the function with the database connection


function redirectWithMessage($message, $location) {
    echo "<script type='text/javascript'>
            alert('$message');
            window.location = '$location';
          </script>";
    exit;
}

function addCategory($db, $category_name) {
    try {
        $query = "INSERT INTO category (CNAME) VALUES (?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $category_name);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Category added successfully!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error adding category: ' . $db->error
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error adding category: ' . $e->getMessage()
        ];
    }
}

function handleFormSubmission($db) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['category_name'])) {
            $result = addCategory($db, $_POST['category_name']);
            
            if ($result['success']) {
                redirectWithMessage($result['message'], 'category.php');
            } else {
                redirectWithMessage($result['message'], '');
            }
        }
    }
}


function renderAddCategoryForm() {
    ?>
    <form method="POST" action="">
        <input type="text" name="category_name" required>
        <button type="submit">Add Category</button>
    </form>
    <?php
}

// Main execution
checkUserAccess($db);
handleFormSubmission($db);
renderAddCategoryForm();

include '../includes/footer.php';
?>