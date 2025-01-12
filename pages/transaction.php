<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// User Access Validation
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];

    if ($Aa == 'User') {
?>
        <script type="text/javascript">
            alert("Restricted Page! You will be redirected to POS");
            window.location = "pos.php";
        </script>
<?php
    }
}

// Function to check inventory availability
function check_inventory($product_id, $quantity) {
    global $db;
    $query = "SELECT QTY_STOCK FROM product WHERE PRODUCT_ID = $product_id";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    return ($row['QTY_STOCK'] >= $quantity);  // Return true if stock is sufficient
}

// Function to reduce inventory after a successful transaction
function reduce_inventory($product_id, $quantity) {
    global $db;
    $query = "UPDATE product SET QTY_STOCK = QTY_STOCK - $quantity WHERE PRODUCT_ID = $product_id";
    mysqli_query($db, $query);
}

// Handling transaction submission (e.g., in a form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_items = $_POST['items'];  // Array of items being sold
    $is_valid = true;

    // Loop through each item in the transaction
    foreach ($transaction_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        // Check if the product has enough stock
        if (!check_inventory($product_id, $quantity)) {
            echo "Not enough stock for product ID: $product_id";
            $is_valid = false;
            break;
        }
    }

    // If stock is available for all items, proceed with transaction
    if ($is_valid) {
        // Insert the transaction into the database
        $query = "INSERT INTO transaction (CUST_ID, NUMOFITEMS, TRANS_DATE) VALUES ('$customer_id', '$num_items', NOW())";
        mysqli_query($db, $query);

        // Get the last inserted transaction ID
        $trans_id = mysqli_insert_id($db);

        // Insert the sold items into the transaction_details table
        foreach ($transaction_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $query = "INSERT INTO transaction_details (TRANS_ID, PRODUCT_ID, QUANTITY, PRICE) 
                      VALUES ('$trans_id', '$product_id', '$quantity', '$price')";
            mysqli_query($db, $query);

            // Reduce the inventory for the product sold
            reduce_inventory($product_id, $quantity);
        }

        echo "Transaction completed successfully!";
    }
}

?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Transaction</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="19%">Transaction Number</th>
                        <th>Customer</th>
                        <th width="13%"># of Items</th>
                        <th width="11%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pagination setup
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $perPage = 10;
                    $offset = ($page - 1) * $perPage;

                    // Query to fetch transactions
                    $query = 'SELECT *, FIRST_NAME, LAST_NAME
                              FROM transaction T
                              JOIN customer C ON T.CUST_ID = C.CUST_ID
                              ORDER BY TRANS_D_ID ASC LIMIT ' . $perPage . ' OFFSET ' . $offset;
                    $result = mysqli_query($db, $query) or die(mysqli_error($db));

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['TRANS_D_ID'] . '</td>';
                        echo '<td>' . $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'] . '</td>';
                        echo '<td>' . $row['NUMOFITEMS'] . '</td>';
                        echo '<td align="right">
                                <a type="button" class="btn btn-primary bg-gradient-primary" href="trans_view.php?action=edit&id=' . $row['TRANS_ID'] . '"><i class="fas fa-fw fa-th-list"></i> View</a>
                                <a type="button" class="btn btn-danger bg-gradient-danger" href="trans_del.php?id=' . $row['TRANS_ID'] . '" onclick="return confirm(\'Are you sure you want to delete this transaction?\');"><i class="fas fa-fw fa-trash"></i> Delete</a>
                            </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="pagination">
                <?php
                // Get total transaction count
                $countQuery = 'SELECT COUNT(*) AS total FROM transaction';
                $countResult = mysqli_query($db, $countQuery);
                $countRow = mysqli_fetch_assoc($countResult);
                $totalPages = ceil($countRow['total'] / $perPage);

                // Display page links
                for ($i = 1; $i <= $totalPages; $i++) {
                    // Add some styling for active pages
                    $activeClass = ($i == $page) ? ' class="active"' : '';
                    echo '<a href="transaction.php?page=' . $i . '" ' . $activeClass . '>' . $i . '</a> ';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
