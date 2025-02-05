<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

function getCategoryById($db, $category_id) {
    $sql = "SELECT * FROM category WHERE CATEGORY_ID = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row;
    }
    return null;
}

function updateCategory($db, $category_id, $new_category_name) {
    $new_category_name = mysqli_real_escape_string($db, $new_category_name);
    $update_sql = "UPDATE category SET CNAME = ? WHERE CATEGORY_ID = ?";
    $stmt_update = $db->prepare($update_sql);
    $stmt_update->bind_param('si', $new_category_name, $category_id);
    
    return $stmt_update->execute();
}

function redirectWithMessage($message, $location) {
    echo "<script type='text/javascript'>
            alert('$message');
            window.location = '$location';
          </script>";
    exit;
}

function renderEditForm($category_name) {
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Edit Category</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" 
                           class="form-control" 
                           name="category_name" 
                           id="category_name" 
                           value="<?php echo htmlspecialchars($category_name); ?>" 
                           required>
                </div>
                <button type="submit" class="btn btn-success">Update Category</button>
                <a href="category_list.php" class="btn btn-danger">Cancel</a>
            </form>
        </div>
    </div>
    <?php
}

function handleCategoryEdit($db) {
    // Check if category ID is provided
    if (!isset($_GET['id'])) {
        redirectWithMessage('Invalid category ID!', 'category_list.php');
    }

    $category_id = $_GET['id'];
    
    // Get category details
    $category = getCategoryById($db, $category_id);
    if (!$category) {
        redirectWithMessage('Category not found!', 'category.php');
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['category_name'])) {
            $success = updateCategory($db, $category_id, $_POST['category_name']);
            
            if ($success) {
                redirectWithMessage('Category updated successfully!', 'category.php');
            } else {
                redirectWithMessage('Error updating category!', '');
            }
        }
    }
    
    return $category;
}

// Main execution
$category = handleCategoryEdit($db);
renderEditForm($category['CNAME']);

include '../includes/footer.php';
?>