<?php
include'../includes/connection.php';
include'../includes/sidebar.php';

$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = '.$_SESSION['MEMBER_ID'].'';
$result = mysqli_query($db, $query) or die (mysqli_error($db));

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

?>
<!-- Page Content -->
<div class="col-lg-12">
    <?php
    if (isset($_POST['firstname'], $_POST['lastname'], $_POST['phonenumber'])) {
        $fname = mysqli_real_escape_string($db, $_POST['firstname']);
        $lname = mysqli_real_escape_string($db, $_POST['lastname']);
        $pn = mysqli_real_escape_string($db, $_POST['phonenumber']);

        // Ensure no blank records are added
        if (!empty($fname) && !empty($lname) && !empty($pn)) {
            switch ($_GET['action']) {
                case 'add':
                    // Use prepared statements to avoid SQL injection
                    $query = "INSERT INTO customer (FIRST_NAME, LAST_NAME, PHONE_NUMBER) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($db, $query);
                    mysqli_stmt_bind_param($stmt, "sss", $fname, $lname, $pn);

                    if (mysqli_stmt_execute($stmt)) {
                        // Redirect to customer page after successful insertion
                        echo '<script type="text/javascript">window.location = "customer.php";</script>';
                    } else {
                        echo "Error in inserting customer: " . mysqli_error($db);
                    }
                    break;
            }
        } else {
            echo "<script>alert('All fields are required!');</script>";
        }
    }
    ?>
</div>

<?php
include'../includes/footer.php';
?>
