<?php
include '../includes/connection.php';
include '../includes/sidebar.php';
include '../pages/functions.php';

// Check user access
checkUserAccess($db);

// Fetch supplier data for editing
$supplierId = isset($_GET['id']) ? $_GET['id'] : null;
$supplierData = fetchSupplierData($db, $supplierId);

// Fetch provinces and cities for dropdowns
$provinceOptions = fetchProvinces($db);
$cityOptions = fetchCities($db);

// Display the edit supplier form
displayEditSupplierForm($supplierData, $provinceOptions, $cityOptions);

include '../includes/footer.php';

// Functions

/**
 * Check user access based on their type.
 * Redirects to POS if the user is not authorized.
 */


/**
 * Fetch supplier data for editing.
 */
function fetchSupplierData($db, $supplierId) {
    if ($supplierId) {
        $sql = 'SELECT SUPPLIER_ID, COMPANY_NAME, l.PROVINCE, l.CITY, PHONE_NUMBER
                FROM supplier e
                JOIN location l ON l.LOCATION_ID = e.LOCATION_ID
                WHERE SUPPLIER_ID = ' . $supplierId;
        $result = mysqli_query($db, $sql);
        return mysqli_fetch_assoc($result);
    }
    return null;
}

/**
 * Fetch provinces for dropdown.
 */
function fetchProvinces($db) {
    $sqlProvinces = 'SELECT DISTINCT PROVINCE FROM location ORDER BY PROVINCE';
    $resultProvinces = mysqli_query($db, $sqlProvinces) or die(mysqli_error($db));

    $options = '';
    while ($row = mysqli_fetch_assoc($resultProvinces)) {
        $options .= "<option value='" . $row['PROVINCE'] . "'>" . $row['PROVINCE'] . "</option>";
    }
    return $options;
}

/**
 * Fetch cities for dropdown.
 */
function fetchCities($db) {
    $sqlCities = 'SELECT DISTINCT CITY FROM location ORDER BY CITY';
    $resultCities = mysqli_query($db, $sqlCities) or die(mysqli_error($db));

    $options = '';
    while ($row = mysqli_fetch_assoc($resultCities)) {
        $options .= "<option value='" . $row['CITY'] . "'>" . $row['CITY'] . "</option>";
    }
    return $options;
}

/**
 * Display the edit supplier form.
 */
function displayEditSupplierForm($supplierData, $provinceOptions, $cityOptions) {
    echo '<script>
    window.onload = function() {
        // Populate the province and city dropdowns for Edit form
        var provinceSelect = document.getElementById("province");
        provinceSelect.innerHTML = `' . $provinceOptions . '`;

        var citySelect = document.getElementById("city");
        citySelect.innerHTML = `' . $cityOptions . '`;

        // Pre-select the province and city for the edit form
        ' . ($supplierData ? "
        document.querySelector('select[name=\"province\"]').value = '" . $supplierData['PROVINCE'] . "';
        document.querySelector('select[name=\"city\"]').value = '" . $supplierData['CITY'] . "';
        " : '') . '
    };
    </script>';

    echo '<center>
            <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
                <div class="card-header py-3">
                    <h4 class="m-2 font-weight-bold text-primary">Edit Supplier</h4>
                </div>
                <a href="supplier.php?action=add" type="button" class="btn btn-primary bg-gradient-primary">Back</a>
                <div class="card-body">
                    <div class="table-responsive">
                        <form role="form" method="post" action="sup_transac.php?action=edit">
                            <!-- Hidden field for the supplier ID -->
                            <input type="hidden" name="id" value="' . ($supplierData['SUPPLIER_ID'] ?? '') . '">

                            <!-- Company Name -->
                            <div class="form-group">
                                <input class="form-control" placeholder="Company Name" name="companyname" value="' . ($supplierData['COMPANY_NAME'] ?? '') . '" required>
                            </div>

                            <!-- Province Dropdown -->
                            <div class="form-group">
                                <select class="form-control" id="province" placeholder="Province" name="province" required></select>
                            </div>

                            <!-- City Dropdown -->
                            <div class="form-group">
                                <select class="form-control" id="city" placeholder="City" name="city" required></select>
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <input class="form-control" placeholder="Phone Number" name="phonenumber" value="' . ($supplierData['PHONE_NUMBER'] ?? '') . '" required>
                            </div>

                            <hr>

                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check fa-fw"></i> Save</button>
                            <button type="reset" class="btn btn-danger btn-block"><i class="fa fa-times fa-fw"></i> Reset</button>
                        </form>
                    </div>
                </div>
            </div>
          </center>';
}
?>