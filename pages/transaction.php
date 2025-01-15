<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// User Access Validation
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID
          WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];

    if ($Aa == 'User') {
        echo "<script type='text/javascript'>
                alert('Restricted Page! You will be redirected to POS');
                window.location = 'pos.php';
              </script>";
        exit;
    }
}

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;  // Number of transactions per page
$offset = ($page - 1) * $perPage;

// Query to fetch transactions with pagination
$query = 'SELECT T.*, C.FIRST_NAME, C.LAST_NAME
          FROM transaction T
          JOIN customer C ON T.CUST_ID = C.CUST_ID
          ORDER BY T.TRANS_ID DESC LIMIT ' . $perPage . ' OFFSET ' . $offset;
$result = mysqli_query($db, $query) or die(mysqli_error($db));

?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Transactions</h4>
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
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['TRANS_ID'] . '</td>'; // Corrected to TRANS_ID
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
