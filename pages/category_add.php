<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Check user type and redirect if necessary
$query = 'SELECT ID, t.TYPE FROM users u JOIN type t ON t.TYPE_ID = u.TYPE_ID WHERE ID = ?';
$stmt = $db->prepare($query);
$stmt->bind_param('i', $_SESSION['MEMBER_ID']);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $Aa = $row['TYPE'];
    if ($Aa == 'User ') {
        echo '<script type="text/javascript">
                alert("Restricted Page! You will be redirected to POS");
                window.location = "pos.php";
              </script>';
        exit; // Stop further execution
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    // Insert new category into the database
    $query = "INSERT INTO category (CNAME) VALUES (?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $category_name);
    $stmt->execute();

    echo '<script>alert("Category added successfully!"); window.location = "category.php";</script>';
}

?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Add New Category</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
