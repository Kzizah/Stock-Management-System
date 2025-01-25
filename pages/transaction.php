<?php
//session_start();
include '../includes/connection.php';
include '../includes/sidebar.php';

// Enable error reporting
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// User Access Validation
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID
          WHERE ID = ' . intval($_SESSION['MEMBER_ID']);
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];
    
    // Redirect if user is not allowed
    if ($Aa == 'User  ') {
        echo "<script type='text/javascript'>
                alert('Restricted Page! You will be redirected to POS');
                window.location = 'pos.php';
              </script>";
        exit;
    }
}

// Fetch transactions ordered by the most recent date
$query = 'SELECT *, FIRST_NAME, LAST_NAME
          FROM transaction T
          JOIN customer C ON T.CUST_ID = C.CUST_ID
          ORDER BY TRANS_ID';
$result = mysqli_query($db, $query) or die(mysqli_error($db));

// Check if any transactions were returned
if (mysqli_num_rows($result) == 0) {
    echo "<p>No transactions found.</p>";
} else {
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
                          <th width="13%">Trans ID</>
                            <th width="19%">Transaction Number</th>
                            <th>Customer</th>
                            <th width="13%">Number of Items</th>
                            <th>Date</th>
                            <th width="11%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['TRANS_ID']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['TRANS_D_ID']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['FIRST_NAME'] . ' ' . $row['LAST_NAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['NUMOFITEMS']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['DATE']) . '</td>';
                            echo '<td align="right">
                                    <a type="button" class="btn btn-primary bg-gradient-primary" href="trans_view.php?action=edit&id=' . $row['TRANS_ID'] . '"><i class="fas fa-fw fa-th-list"></i> View</a>
                                    <a type="button" class="btn btn-danger bg-gradient-danger" href="trans_del.php?id=' . $row['TRANS_ID'] . '" onclick="return confirm(\'Are you sure you want to delete this transaction?\');"><i class="fas fa-fw fa-trash"></i> Delete</a>
                                  </td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}

include '../includes/footer.php';
?>