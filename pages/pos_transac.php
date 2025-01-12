<?php
include '../includes/connection.php';

session_start();

$date = $_POST['date'] ?? null;
$customer = $_POST['customer'] ?? null;
$subtotal = $_POST['subtotal'] ?? 0;
$lessvat = $_POST['lessvat'] ?? 0;
$netvat = $_POST['netvat'] ?? 0;
$addvat = $_POST['addvat'] ?? 0;
$total = $_POST['total'] ?? 0;
$cash = $_POST['cash'] ?? 0;
$emp = $_POST['employee'] ?? '';
$rol = $_POST['role'] ?? '';
$today = date("mdGis");

// Check if the product data exists
if (!isset($_POST['name']) || !is_array($_POST['name']) || count($_POST['name']) === 0) {
    die("Error: No products provided in the transaction.");
}

$countID = count($_POST['name']);

switch ($_GET['action'] ?? '') {
    case 'add':
        for ($i = 0; $i < $countID; $i++) {
            $productName = $_POST['name'][$i];
            $quantity = $_POST['quantity'][$i] ?? 0;
            $price = $_POST['price'][$i] ?? 0;

            $query = "INSERT INTO `transaction_details` 
                      (`ID`, `TRANS_D_ID`, `PRODUCTS`, `QTY`, `PRICE`, `EMPLOYEE`, `ROLE`) 
                      VALUES (NULL, '{$today}', '{$productName}', '{$quantity}', '{$price}', '{$emp}', '{$rol}')";
            mysqli_query($db, $query) or die(mysqli_error($db));
        }

        $query111 = "INSERT INTO `transaction` 
                     (`TRANS_ID`, `CUST_ID`, `NUMOFITEMS`, `SUBTOTAL`, `LESSVAT`, `NETVAT`, `ADDVAT`, `GRANDTOTAL`, `CASH`, `DATE`, `TRANS_D_ID`) 
                     VALUES (NULL, '{$customer}', '{$countID}', '{$subtotal}', '{$lessvat}', '{$netvat}', '{$addvat}', '{$total}', '{$cash}', '{$date}', '{$today}')";
        mysqli_query($db, $query111) or die(mysqli_error($db));

        unset($_SESSION['pointofsale']);
        ?>
        <script type="text/javascript">
            alert("Transaction successfully added.");
            window.location = "pos.php";
        </script>
        <?php
        break;

    default:
        die("Error: Invalid action.");
}
?>
