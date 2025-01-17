<?php
include '../includes/connection.php';

// Validate POST data
if (isset($_POST['companyname'], $_POST['province'], $_POST['city'], $_POST['phonenumber'])) {
    $name = mysqli_real_escape_string($db, $_POST['companyname']);
    $prov = mysqli_real_escape_string($db, $_POST['province']);
    $cit = mysqli_real_escape_string($db, $_POST['city']);
    $phone = mysqli_real_escape_string($db, $_POST['phonenumber']);

    // Validate 'action' GET parameter
    if (isset($_GET['action']) && $_GET['action'] === 'add') {
        // Insert into location table
        $query = "INSERT INTO location (PROVINCE, CITY) VALUES (?, ?)";
        $stmt = mysqli_prepare($db, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $prov, $cit);
            $location_insert = mysqli_stmt_execute($stmt);

            if ($location_insert) {
                // Get the last inserted LOCATION_ID
                $location_id = mysqli_insert_id($db);

                // Insert into supplier table
                $query2 = "INSERT INTO supplier (COMPANY_NAME, LOCATION_ID, PHONE_NUMBER) VALUES (?, ?, ?)";
                $stmt2 = mysqli_prepare($db, $query2);
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, "sis", $name, $location_id, $phone);
                    $supplier_insert = mysqli_stmt_execute($stmt2);

                    if ($supplier_insert) {
                        // Redirect to supplier page after success
                        header("Location: supplier.php");
                        exit();
                    } else {
                        // Log error if supplier insert fails
                        error_log("Error inserting supplier: " . mysqli_error($db));
                        echo "Error inserting supplier. Please try again later.";
                    }
                } else {
                    // Log error if the second query fails to prepare
                    error_log("Error preparing supplier insert query: " . mysqli_error($db));
                    echo "Error preparing supplier insert. Please try again later.";
                }
            } else {
                // Log error if location insert fails
                error_log("Error inserting location: " . mysqli_error($db));
                echo "Error inserting location. Please try again later.";
            }
        } else {
            // Log error if the first query fails to prepare
            error_log("Error preparing location insert query: " . mysqli_error($db));
            echo "Error preparing location insert. Please try again later.";
        }
    } else {
        echo "Invalid action.";
    }
}
?>

<?php include '../includes/footer.php'; ?>
