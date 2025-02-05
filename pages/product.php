<?php
include '../includes/connection.php';
include '../includes/sidebar.php';
include '../pages/functions.php'; // Include the functions file

// Check user access
checkUserAccess($db);

// Get categories and suppliers dropdowns
$categoryDropdown = getCategories($db);
$supplierDropdown = getSuppliers($db);

// Get product rows
$productRows = getProducts($db);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Product&nbsp;
            <a href="pro_add.php" data-toggle="modal" data-target="#aModal" type="button" class="btn btn-primary bg-gradient-primary" style="border-radius: 0px;">
                <i class="fas fa-fw fa-plus"></i>
            </a>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"> 
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th> <!-- Category Name -->
                        <th>Supplier (Company Name)</th> <!-- Company Name -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $productRows; ?> <!-- Render product rows here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
