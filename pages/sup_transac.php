<?php
include '../includes/connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleSupplierFormSubmission($db);
}

include '../includes/footer.php';

// Functions

/**
 * Handle supplier form submission (add or edit).
 */
function handleSupplierFormSubmission($db) {
    // Validate POST data
    if (!isset($_POST['companyname'], $_POST['province'], $_POST['city'], $_POST['phonenumber'])) {
        echo "All fields are required.";
        return;
    }

    // Sanitize inputs
    $name = mysqli_real_escape_string($db, $_POST['companyname']);
    $prov = mysqli_real_escape_string($db, $_POST['province']);
    $cit = mysqli_real_escape_string($db, $_POST['city']);
    $phone = mysqli_real_escape_string($db, $_POST['phonenumber']);

    // Validate 'action' GET parameter
    if (!isset($_GET['action'])) {
        echo "Invalid action.";
        return;
    }

    $action = $_GET['action'];

    if ($action === 'add') {
        addSupplier($db, $name, $prov, $cit, $phone);
    } elseif ($action === 'edit' && isset($_POST['id'])) {
        $supplierId = (int)$_POST['id'];
        editSupplier($db, $supplierId, $name, $prov, $cit, $phone);
    } else {
        echo "Invalid action or missing supplier ID.";
    }
}

/**
 * Add a new supplier.
 */
function addSupplier($db, $name, $prov, $cit, $phone) {
    // Insert into location table
    $locationId = insertLocation($db, $prov, $cit);
    if (!$locationId) {
        echo "Error inserting location.";
        return;
    }

    // Insert into supplier table
    $query = "INSERT INTO supplier (COMPANY_NAME, LOCATION_ID, PHONE_NUMBER) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    if (!$stmt) {
        error_log("Error preparing supplier insert query: " . mysqli_error($db));
        echo "Error preparing supplier insert. Please try again later.";
        return;
    }

    mysqli_stmt_bind_param($stmt, "sis", $name, $locationId, $phone);
    $supplierInsert = mysqli_stmt_execute($stmt);

    if ($supplierInsert) {
        // Redirect to supplier page after success
        header("Location: supplier.php");
        exit();
    } else {
        error_log("Error inserting supplier: " . mysqli_error($db));
        echo "Error inserting supplier. Please try again later.";
    }
}

/**
 * Edit an existing supplier.
 */
function editSupplier($db, $supplierId, $name, $prov, $cit, $phone) {
    // Update location table
    $locationUpdated = updateLocation($db, $supplierId, $prov, $cit);
    if (!$locationUpdated) {
        echo "Error updating location.";
        return;
    }

    // Update supplier table
    $query = "UPDATE supplier SET COMPANY_NAME = ?, PHONE_NUMBER = ? WHERE SUPPLIER_ID = ?";
    $stmt = mysqli_prepare($db, $query);
    if (!$stmt) {
        error_log("Error preparing supplier update query: " . mysqli_error($db));
        echo "Error preparing supplier update. Please try again later.";
        return;
    }

    mysqli_stmt_bind_param($stmt, "ssi", $name, $phone, $supplierId);
    $supplierUpdated = mysqli_stmt_execute($stmt);

    if ($supplierUpdated) {
        // Redirect to supplier page after success
        header("Location: supplier.php");
        exit();
    } else {
        error_log("Error updating supplier: " . mysqli_error($db));
        echo "Error updating supplier. Please try again later.";
    }
}

/**
 * Insert a new location and return the LOCATION_ID.
 */
function insertLocation($db, $prov, $cit) {
    $query = "INSERT INTO location (PROVINCE, CITY) VALUES (?, ?)";
    $stmt = mysqli_prepare($db, $query);
    if (!$stmt) {
        error_log("Error preparing location insert query: " . mysqli_error($db));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ss", $prov, $cit);
    $locationInsert = mysqli_stmt_execute($stmt);

    if ($locationInsert) {
        return mysqli_insert_id($db);
    } else {
        error_log("Error inserting location: " . mysqli_error($db));
        return false;
    }
}

/**
 * Update an existing location.
 */
function updateLocation($db, $supplierId, $prov, $cit) {
    // Fetch the LOCATION_ID for the supplier
    $query = "SELECT LOCATION_ID FROM supplier WHERE SUPPLIER_ID = ?";
    $stmt = mysqli_prepare($db, $query);
    if (!$stmt) {
        error_log("Error preparing location fetch query: " . mysqli_error($db));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $supplierId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        error_log("Supplier not found.");
        return false;
    }

    $locationId = $row['LOCATION_ID'];

    // Update the location
    $query = "UPDATE location SET PROVINCE = ?, CITY = ? WHERE LOCATION_ID = ?";
    $stmt = mysqli_prepare($db, $query);
    if (!$stmt) {
        error_log("Error preparing location update query: " . mysqli_error($db));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ssi", $prov, $cit, $locationId);
    return mysqli_stmt_execute($stmt);
}
?>