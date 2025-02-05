<?php
session_start(); // Start the session at the beginning

include '../includes/connection.php'; // Include your database connection
include '../includes/sidebar.php'; // Include your sidebar
include '../pages/functions.php'; // Include your functions

// Call the checkUser  Access function to verify user access
checkUserAccess($db); // Call the function with the database connection


$productId = $_GET['id'];
$productDetails = getProductDetails($db, $productId);
$categoryOptions = getCategories($db);
?>

<center>
  <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
    <div class="card-header py-3">
      <h4 class="m-2 font-weight-bold text-primary">Edit Product</h4>
    </div>
    <a href="product.php?action=add" type="button" class="btn btn-primary bg-gradient-primary">Back</a>
    <div class="card-body">
      <form role="form" method="post" action="pro_edit1.php">
        <input type="hidden" name="id" value="<?php echo $productDetails['PRODUCT_ID']; ?>" />
        
        <!-- Product Code -->
        <div class="form-group row text-left text-warning">
          <div class="col-sm-3" style="padding-top: 5px;">
            Product Code:
          </div>
          <div class="col-sm-9">
            <input class="form-control" placeholder="Product Code" name="prodcode" value="<?php echo $productDetails['PRODUCT_CODE']; ?>" readonly>
          </div>
        </div>

        <!-- Product Name -->
        <div class="form-group row text-left text-warning">
          <div class="col-sm-3" style="padding-top: 5px;">
            Product Name:
          </div>
          <div class="col-sm-9">
            <input class="form-control" placeholder="Product Name" name="prodname" value="<?php echo $productDetails['NAME']; ?>" required>
          </div>
        </div>

        <!-- Description -->
        <div class="form-group row text-left text-warning">
          <div class="col-sm-3" style="padding-top: 5px;">
            Description:
          </div>
          <div class="col-sm-9">
            <textarea class="form-control" placeholder="Description" name="description"><?php echo $productDetails['DESCRIPTION']; ?></textarea>
          </div>
        </div>

        <!-- Price -->
        <div class="form-group row text-left text-warning">
          <div class="col-sm-3" style="padding-top: 5px;">
            Price:
          </div>
          <div class="col-sm-9">
            <input class="form-control" placeholder="Price" name="price" value="<?php echo $productDetails['PRICE']; ?>" required>
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
include '../includes/footer.php';
?>
