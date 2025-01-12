<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch category details for editing
    $sql = "SELECT * FROM category WHERE CATEGORY_ID = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $category_name = $row['CNAME'];
    } else {
        echo '<script type="text/javascript">alert("Category not found!"); window.location = "category.php";</script>';
        exit;
    }

    // Update category if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_category_name = mysqli_real_escape_string($db, $_POST['category_name']);
        
        // Update category in database
        $update_sql = "UPDATE category SET CNAME = ? WHERE CATEGORY_ID = ?";
        $stmt_update = $db->prepare($update_sql);
        $stmt_update->bind_param('si', $new_category_name, $category_id);

        if ($stmt_update->execute()) {
            echo '<script type="text/javascript">alert("Category updated successfully!"); window.location = "category.php";</script>';
        } else {
            echo '<script type="text/javascript">alert("Error updating category!");</script>';
        }
    }
} else {
    echo '<script type="text/javascript">alert("Invalid category ID!"); window.location = "category_list.php";</script>';
    exit;
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Edit Category</h4>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" name="category_name" id="category_name" value="<?php echo htmlspecialchars($category_name); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update Category</button>
            <a href="category_list.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
