<?php
include '../includes/connection.php';
include '../includes/sidebar.php';
include '../pages/functions.php';

checkUserAccess($db);

// Fetch categories for the dropdown
$categoryDropdown=getCategories($db);

// Fetch suppliers for the dropdown
$supplierDropdown=getSuppliers($db);

function renderAddProductForm($categoryDropdown, $supplierDropdown) {
    ?>
    <center>
        <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
            <div class="card-header py-3">
                <h4 class="m-2 font-weight-bold text-primary">Add Product</h4>
            </div>
            <a href="product.php?action=add" type="button" class="btn btn-primary bg-gradient-primary">Back</a>
            <div class="card-body">
                <div class="table-responsive">
                    <form role="form" method="post" action="pro_transac.php?action=add">
                        <!-- Product Code -->
                        <div class="form-group">
                            <input class="form-control" placeholder="Product Code" name="prodcode" required>
                        </div>

                        <!-- Product Name -->
                        <div class="form-group">
                            <input class="form-control" placeholder="Name" name="name" required>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <textarea rows="5" class="form-control" placeholder="Description" name="description" required></textarea>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group">
                            <input type="number" min="1" max="999999999" class="form-control" placeholder="Quantity" name="quantity" required>
                        </div>

                        <!-- On Hand -->
                        <div class="form-group">
                            <input type="number" min="1" max="999999999" class="form-control" placeholder="On Hand" name="onhand" required>
                        </div>

                        <!-- Price -->
                        <div class="form-group">
                            <input type="number" min="1" max="9999999999" class="form-control" placeholder="Price" name="price" required>
                        </div>

                        <!-- Category Dropdown -->
                        <div class="form-group">
                            <?php echo $categoryDropdown; ?>
                        </div>

                        <!-- Supplier Dropdown -->
                        <div class="form-group">
                            <?php echo $supplierDropdown; ?>
                        </div>

                        <!-- Date Stock In -->
                        <div class="form-group">
                            <input type="date" class="form-control" placeholder="Date Stock In" name="datestock" required>
                        </div>

                        <hr>
                        <!-- Save and Reset Buttons -->
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check fa-fw"></i> Save</button>
                        <button type="reset" class="btn btn-danger btn-block"><i class="fa fa-times fa-fw"></i> Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </center>
    <?php
}