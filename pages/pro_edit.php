<?php
// Start the session
session_start();

// Include necessary files
include '../includes/connection.php'; // Database connection
include '../includes/sidebar.php';    // Sidebar
include '../pages/functions.php';     // Custom functions

// Verify user access
checkUserAccess($db);

// Get the product ID from the URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0; // Sanitize input

// Fetch product details and category options
$productDetails = getProductDetails($db, $productId);
$categoryOptions = getCategories($db);

// Redirect if product details are not found
if (!$productDetails) {
    echo "<script>alert('Product not found!'); window.location.href = 'product.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <center>
        <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
            <div class="card-header py-3">
                <h4 class="m-2 font-weight-bold text-primary">Edit Product</h4>
            </div>
            <a href="product.php?action=add" type="button" class="btn btn-primary bg-gradient-primary">Back</a>
            <div class="card-body">
                <form role="form" method="post" action="pro_edit1.php">
                    <!-- Hidden field for product ID -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($productDetails['PRODUCT_ID']); ?>" />

                    <!-- Product Code -->
                    <div class="form-group row text-left text-warning">
                        <div class="col-sm-3" style="padding-top: 5px;">
                            Product Code:
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="Product Code" name="prodcode" value="<?php echo htmlspecialchars($productDetails['PRODUCT_CODE']); ?>" readonly>
                        </div>
                    </div>

                    <!-- Product Name -->
                    <div class="form-group row text-left text-warning">
                        <div class="col-sm-3" style="padding-top: 5px;">
                            Product Name:
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="Product Name" name="prodname" value="<?php echo htmlspecialchars($productDetails['NAME']); ?>" required>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group row text-left text-warning">
                        <div class="col-sm-3" style="padding-top: 5px;">
                            Description:
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" placeholder="Description" name="description"><?php echo htmlspecialchars($productDetails['DESCRIPTION']); ?></textarea>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="form-group row text-left text-warning">
                        <div class="col-sm-3" style="padding-top: 5px;">
                            Price:
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="Price" name="price" value="<?php echo htmlspecialchars($productDetails['PRICE']); ?>" required>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="form-group row text-left text-warning">
                        <div class="col-sm-3" style="padding-top: 5px;">
                            Category:
                        </div>
                        <div class="col-sm-9">
                            <?php echo $categoryOptions; ?>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-edit fa-fw"></i>Update</button>
                </form>
            </div>
        </div>
    </center>

    <?php
    // Include footer
    include '../includes/footer.php';
    ?>
</body>
</html>