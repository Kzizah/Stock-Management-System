<?php
// Include necessary files
include '../includes/connection.php'; // Database connection
include '../includes/sidebar.php';    // Sidebar
include '../pages/functions.php'; // Include your functions

// Redirect user if they don't have admin access
checkUserAccess($db);

// Fetch categories and suppliers for dropdowns
$categoryDropdown = getCategories($db);
$supplierDropdown = getSuppliers($db);

// Render the product table and modal
renderProductPage($db, $categoryDropdown, $supplierDropdown);

// Include footer
include '../includes/footer.php';


function renderProductPage($db, $categoryDropdown, $supplierDropdown) {
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Product&nbsp;
                <a href="#" data-toggle="modal" data-target="#aModal" type="button" class="btn btn-primary bg-gradient-primary" style="border-radius: 0px;">
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
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo fetchProductRows($db); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="aModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" method="post" action="pro_transac.php?action=add">
                        <div class="form-group">
                            <input class="form-control" placeholder="Product Code" name="prodcode" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Name" name="name" required>
                        </div>
                        <div class="form-group">
                            <textarea rows="5" cols="50" class="form-control" placeholder="Description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" max="999999999" class="form-control" placeholder="Quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" max="999999999" class="form-control" placeholder="On Hand" name="onhand" required>
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" max="9999999999" class="form-control" placeholder="Price" name="price" required>
                        </div>
                        <div class="form-group">
                            <?php echo $categoryDropdown; ?>
                        </div>
                        <div class="form-group">
                            <?php echo $supplierDropdown; ?>
                        </div>
                        <div class="form-group">
                            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" placeholder="Date Stock In" name="datestock" required>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Save</button>
                        <button type="reset" class="btn btn-danger"><i class="fa fa-times fa-fw"></i>Reset</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>      
                    </form>  
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Fetch product rows from the database and return them as HTML table rows.
 *
 * @param mysqli $db Database connection object.
 * @return string HTML table rows for products.
 */
function fetchProductRows($db) {
    $query = 'SELECT 
                p.PRODUCT_ID, 
                p.PRODUCT_CODE, 
                p.NAME, 
                p.PRICE, 
                c.CNAME, 
                s.COMPANY_NAME 
              FROM product p 
              JOIN category c ON p.CATEGORY_ID = c.CATEGORY_ID 
              JOIN supplier s ON p.SUPPLIER_ID = s.SUPPLIER_ID 
              GROUP BY p.PRODUCT_CODE';
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $rows = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $rows .= '<tr>
                    <td>' . $row['PRODUCT_CODE'] . '</td>
                    <td>' . $row['NAME'] . '</td>
                    <td>' . $row['PRICE'] . '</td>
                    <td>' . $row['CNAME'] . '</td>
                    <td>' . $row['COMPANY_NAME'] . '</td>
                    <td align="right">
                        <div class="btn-group">
                            <a type="button" class="btn btn-primary bg-gradient-primary" href="pro_searchfrm1.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                <i class="fas fa-fw fa-list-alt"></i> Details
                            </a>
                            <div class="btn-group">
                                <a type="button" class="btn btn-primary bg-gradient-primary dropdown no-arrow" data-toggle="dropdown" style="color:white;">
                                    ... <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu text-center" role="menu">
                                    <li>
                                        <a type="button" class="btn btn-warning bg-gradient-warning btn-block" style="border-radius: 0px;" href="pro_edit.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                            <i class="fas fa-fw fa-edit"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a type="button" class="btn btn-danger bg-gradient-danger btn-block" style="border-radius: 0px;" 
                                           href="pro_del.php?id=' . $row['PRODUCT_ID'] . '" 
                                           onclick="return confirm(\'Are you sure you want to delete this product?\');">
                                            <i class="fas fa-fw fa-trash"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                  </tr>';
    }
    return $rows;
}
?>