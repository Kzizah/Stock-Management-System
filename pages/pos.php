<?php

include'../includes/connection.php';
include'../includes/topp.php';
// session_start();
$product_ids = array();
//session_destroy();

//check if Add to Cart button has been submitted
if (filter_input(INPUT_POST, 'addpos')) {
    // Get the input quantity
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    
    // Check if the quantity is 0 or less
    if ($quantity <= 0) {
        echo "<script>alert('Stock insufficient: Quantity cannot be zero or less');</script>";
    } else {
        if (isset($_SESSION['pointofsale'])) {
            // Keep track of how many products are in the shopping cart
            $count = count($_SESSION['pointofsale']);
            
            // Create sequential array for matching array keys to product IDs
            $product_ids = array_column($_SESSION['pointofsale'], 'id');
            
            if (!in_array(filter_input(INPUT_GET, 'id'), $product_ids)) {
                $_SESSION['pointofsale'][$count] = array(
                    'id' => filter_input(INPUT_GET, 'id'),
                    'name' => filter_input(INPUT_POST, 'name'),
                    'price' => filter_input(INPUT_POST, 'price'),
                    'quantity' => $quantity
                );
            } else { // Product already exists, increase quantity
                // Match array key to ID of the product being added to the cart
                for ($i = 0; $i < count($product_ids); $i++) {
                    if ($product_ids[$i] == filter_input(INPUT_GET, 'id')) {
                        // Add item quantity to the existing product in the array
                        $_SESSION['pointofsale'][$i]['quantity'] += $quantity;
                    }
                }
            }
        } else { // If shopping cart doesn't exist, create first product with array key 0
            $_SESSION['pointofsale'][0] = array(
                'id' => filter_input(INPUT_GET, 'id'),
                'name' => filter_input(INPUT_POST, 'name'),
                'price' => filter_input(INPUT_POST, 'price'),
                'quantity' => $quantity
            );
        }
    }
}


if(filter_input(INPUT_GET, 'action') == 'delete'){
    //loop through all products in the shopping cart until it matches with GET id variable
    foreach($_SESSION['pointofsale'] as $key => $product){
        if ($product['id'] == filter_input(INPUT_GET, 'id')){
            //remove product from the shopping cart when it matches with the GET id
            unset($_SESSION['pointofsale'][$key]);
        }
    }
    //reset session array keys so they match with $product_ids numeric array
    $_SESSION['pointofsale'] = array_values($_SESSION['pointofsale']);
}

//pre_r($_SESSION);

function pre_r($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
                ?>
                <div class="row">
                <div class="col-lg-12">
                  <div class="card shadow mb-0">
                  <div class="card-header py-2">
                    <h4 class="m-1 text-lg text-primary">Product category</h4>
                  </div>
                        <!-- /.panel-heading -->
                        <div class="card-body">
                            <!-- Nav tabs -->
                         

<?php include 'postabpane2.php'; ?>

        <div style="clear:both"></div>  
        <br />  
        <div class="card shadow mb-4 col-md-12">
        <div class="card-header py-3 bg-white">
          <h4 class="m-2 font-weight-bold text-primary">Point of Sale</h4>
        </div>
        
      <div class="row">    
      <div class="card-body col-md-9">
        <div class="table-responsive">

        <!-- trial form lang   -->
<form role="form" method="post" action="pos_transac.php?action=add">
  <input type="hidden" name="employee" value="<?php echo $_SESSION['FIRST_NAME']; ?>">
  <input type="hidden" name="role" value="<?php echo $_SESSION['JOB_TITLE']; ?>">
  
        <table class="table">    
        <tr>  
             <th width="55%">Product Name</th>  
             <th width="10%">Quantity</th>  
             <th width="15%">Price</th>  
             <th width="15%">Total</th>  
             <th width="5%">Action</th>  
        </tr>  
        <?php  

        if(!empty($_SESSION['pointofsale'])):  
            
             $total = 0;  
        
             foreach($_SESSION['pointofsale'] as $key => $product): 
        ?>  
        <tr>  
          <td>
            <input type="hidden" name="name[]" value="<?php echo $product['name']; ?>">
            <?php echo $product['name']; ?>
          </td>  

           <td>
            <input type="hidden" name="quantity[]" value="<?php echo $product['quantity']; ?>">
            <?php echo $product['quantity']; ?>
          </td>  

           <td>
            <input type="hidden" name="price[]" value="<?php echo $product['price']; ?>">
            $ <?php echo number_format($product['price']); ?>
          </td>
          

           <td>
            <input type="hidden" name="total" value="<?php echo $product['quantity'] * $product['price']; ?>">
            $ <?php echo number_format($product['quantity'] * $product['price'], 2); ?></td>  
           <td>
               <a href="pos.php?action=delete&id=<?php echo $product['id']; ?>">
                    <div class="btn bg-gradient-danger btn-danger"><i class="fas fa-fw fa-trash"></i></div>
               </a>
           </td>  
        </tr>
        <?php  
                  $total = $total + ($product['quantity'] * $product['price']);
             endforeach;  
        ?>


        <?php  
        endif;
        ?>  
        </table> 
         </div>
       </div> 

<?php
include 'posside.php';
include'../includes/footer.php';
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addPosForm = document.querySelector('form[action="pos_transac.php?action=add"]');
        addPosForm.addEventListener('submit', function (event) {
            const quantityInput = addPosForm.querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value, 10);
            
            if (quantity <= 0 || isNaN(quantity)) {
                event.preventDefault(); // Prevent form submission
                alert('Stock insufficient: Quantity cannot be zero or less');
            }
        });
    });
</script>