<?php
//session_start(); // Start the session at the beginning

include '../includes/connection.php'; // Include your database connection
include '../includes/sidebar.php'; // Include your sidebar
include '../pages/functions.php'; // Include your functions

// Call the checkUser  Access function to verify user access
checkUserAccess($db); // Call the function with the database connection


function renderSearchForm($search_query) {
    ?>
    <form method="POST" class="mb-3">
        <input type="text" name="search" class="form-control" 
               placeholder="Search categories..." 
               value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>
    <?php
}

function renderCategoryTable($result) {
    ?>
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
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['CATEGORY_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['CNAME']); ?></td>
                        <td align="right">
                            <div class="btn-group">
                                <a type="button" class="btn btn-primary bg-gradient-primary" 
                                   href="category_edit.php?action=edit&id=<?php echo $row['CATEGORY_ID']; ?>">
                                    <i class="fas fa-fw fa-edit"></i> Edit
                                </a>
                                <a type="button" class="btn btn-danger bg-gradient-danger" 
                                   href="category_del.php?id=<?php echo $row['CATEGORY_ID']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this category?');">
                                    <i class="fas fa-fw fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Main execution
$search_query = getSearchQuery();
$categories = fetchCategories($db, $search_query);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Categories</h4>
    </div>
    <div class="card-body">
        <a href="category_add.php" class="btn btn-success mb-3">Add Category</a>
        <?php 
        renderSearchForm($search_query);
        renderCategoryTable($categories);
        ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>