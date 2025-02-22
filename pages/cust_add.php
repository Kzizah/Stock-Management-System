<?php
include '../includes/connection.php';
include '../includes/sidebar.php';
?>

<?php
// Check user type for access control
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID 
          WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $userType = $row['TYPE'];

    if ($userType == 'User') {
        echo '<script type="text/javascript">
                  alert("Restricted Page! You will be redirected to POS");
                  window.location = "pos.php";
              </script>';
        exit;
    }
}
?>

<!-- Add Customer Form -->
<center>
    <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Add Customer</h4>
        </div>
        <a href="customer.php" type="button" class="btn btn-primary bg-gradient-primary">Back</a>
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="post" action="cust_transac.php?action=add">
                    <div class="form-group">
                        <input class="form-control" placeholder="First Name" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Last Name" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Phone Number" name="phonenumber" required>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fa fa-check fa-fw"></i>Save
                    </button>
                    <button type="reset" class="btn btn-danger btn-block">
                        <i class="fa fa-times fa-fw"></i>Reset
                    </button>
                </form>
            </div>
        </div>
    </div>
</center>

<!-- Display Customer Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Customer List</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch customer data
                    $query = 'SELECT firstname, lastname, phonenumber FROM customer';
                    $result = mysqli_query($db, $query) or die(mysqli_error($db));

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . (!empty($row['firstname']) ? $row['firstname'] : 'N/A') . '</td>';
                        echo '<td>' . (!empty($row['lastname']) ? $row['lastname'] : 'N/A') . '</td>';
                        echo '<td>' . (!empty($row['phonenumber']) ? $row['phonenumber'] : 'N/A') . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
