<?php
// Include necessary files
include '../includes/connection.php';
include '../includes/sidebar.php';

// Check user type and redirect if necessary
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID 
          WHERE ID = ' . intval($_SESSION['MEMBER_ID']); // Use intval to ensure it's an integer

$result = mysqli_query($db, $query) or die(mysqli_error($db));

if ($row = mysqli_fetch_assoc($result)) {
    $userType = $row['TYPE'];
    if ($userType == 'User ') {
        echo '<script type="text/javascript">
                alert("Restricted Page! You will be redirected to POS");
                window.location = "pos.php";
              </script>';
        exit; // Stop further execution
    }
}

// Fetch product details
$productId = intval($_GET['id']); // Ensure it's an integer
$query = 'SELECT PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, ON_HAND, PRICE, c.CNAME 
          FROM product p 
          JOIN category c ON p.CATEGORY_ID = c.CATEGORY_ID 
          WHERE PRODUCT_ID = ' . $productId; // Use PRODUCT_ID for the query



$result = mysqli_query($db, $query) or die('Query Error: ' . mysqli_error($db)); // Check for query errors

// Initialize variables
$productDetails = [
    'PRODUCT_ID' => '',
    'PRODUCT_CODE' => '',
    'NAME' => '',
    'DESCRIPTION' => '',
    'QTY_STOCK' => '',
    'ON_HAND' => '',
    'PRICE' => '',
    'CNAME' => ''
];

if (mysqli_num_rows($result) > 0) {
    $productDetails = mysqli_fetch_assoc($result);
} else {
    echo "<p>No product found.</p>";
}


// Display product details
?>
<center>
    <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Product's Detail</h4>
        </div>
        <a href="product.php?action=add" type="button" class="btn btn-primary bg-gradient-primary btn-block">
            <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back
        </a>
        <div class="card-body">
            <div class="form-group row text-left">
                <div class="col-sm-3 text-primary">
                    <h5>Product Code<br></h5>
                </div>
                <div class="col-sm-9">
                    <h5>: <?php echo htmlspecialchars($productDetails['PRODUCT_CODE']); ?><br></h5>
                </div>
            </div>
            <div class="form-group row text-left">
                <div class="col-sm-3 text-primary">
                    <h5>Product Name<br></h5>
                </div>
                <div class="col-sm-9">
                    <h5>: <?php echo htmlspecialchars($productDetails['NAME']); ?><br></h5>
                </div>
            </div>
            <div class="form-group row text-left">
                <div class="col-sm-3 text-primary">
                    <h5>Description<br></h5>
                </div>
                <div class="col-sm-9">
                    <h5>: <?php echo htmlspecialchars($productDetails['DESCRIPTION']); ?><br></h5>
                </div>
            </div>
            <div class="form-group row text-left">
                <div class="col-sm-3 text-primary">
                    <h5>Price<br></h5>
                </div>
                <div class="col-sm-9">
                    <h5>: <?php echo htmlspecialchars($productDetails['PRICE']); ?><br></h5>
                </div>
            </div>
            <div class="form-group row text-left">
                <div class="col-sm-3 text-primary">
                    <h5>Category<br></h5>
                </div>
                <div class="col-sm-9">
                    <h5>: <?php echo htmlspecialchars($productDetails['CNAME']); ?><br></h5>
                </div>
            </div>
        </div>
    </div>
</center>

<?php
include '../includes/footer.php';
?>