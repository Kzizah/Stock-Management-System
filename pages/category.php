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

// Search functionality for categories
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = mysqli_real_escape_string($db, $_POST['search']);
}

// Fetch categories based on search query
$sql = "SELECT CATEGORY_ID, CNAME FROM category WHERE CNAME LIKE '%$search_query%' ORDER BY CNAME ASC";
$result = mysqli_query($db, $sql) or die("Bad SQL: $sql");

?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Categories</h4>
    </div>
    <div class="card-body">
        <!-- Add Button -->
        <a href="category_add.php" class="btn btn-success mb-3">Add Category</a>

        <!-- Search Form -->
        <form method="POST" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search categories..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['CATEGORY_ID']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['CNAME']) . '</td>';
                        echo '<td align="right">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-primary bg-gradient-primary" href="category_edit.php?action=edit&id=' . $row['CATEGORY_ID'] . '">
                                        <i class="fas fa-fw fa-edit"></i> Edit
                                    </a>
                                    <a type="button" class="btn btn-danger bg-gradient-danger" href="category_del.php?id=' . $row['CATEGORY_ID'] . '" onclick="return confirm(\'Are you sure you want to delete this category?\');">
                                        <i class="fas fa-fw fa-trash"></i> Delete
                                    </a>
                                </div>
                              </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
