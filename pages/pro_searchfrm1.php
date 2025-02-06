<?php
// Include necessary files
include '../includes/connection.php';
include '../includes/sidebar.php';
include '../pages/functions.php';

checkUserAccess($db); 

// Fetch product details
$productId = intval($_GET['id']);
$productDetails = getProductDetails($db, $productId);

// Function to display product details
function displayProductDetails($product)
{
    if (!$product) {
        echo "<p>No product found.</p>";
        return;
    }
    ?>
    <center>
        <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
            <div class="card-header py-3">
                <h4 class="m-2 font-weight-bold text-primary">Product's Detail</h4>
            </div>
            <a href="product.php?action=add" class="btn btn-primary bg-gradient-primary btn-block">
                <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back
            </a>
            <div class="card-body">
                <?php
                $fields = ['PRODUCT_CODE' => 'Product Code', 'NAME' => 'Product Name', 'DESCRIPTION' => 'Description', 
                           'PRICE' => 'Price', 'CNAME' => 'Category'];
                foreach ($fields as $key => $label) { ?>
                    <div class="form-group row text-left">
                        <div class="col-sm-3 text-primary">
                            <h5><?php echo $label; ?><br></h5>
                        </div>
                        <div class="col-sm-9">
                            <h5>: <?php echo htmlspecialchars($product[$key]); ?><br></h5>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </center>
    <?php
}

// Display product details
displayProductDetails($productDetails);

include '../includes/footer.php';
?>
