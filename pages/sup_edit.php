<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Fetch the user type to check for restricted page
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID WHERE ID = ' . $_SESSION['MEMBER_ID'];
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

// Fetch supplier data for editing
$supplierId = isset($_GET['id']) ? $_GET['id'] : null;
$supplierData = null;

if ($supplierId) {
    $sql = 'SELECT SUPPLIER_ID, COMPANY_NAME, l.PROVINCE, l.CITY, PHONE_NUMBER
            FROM supplier e
            JOIN location l ON l.LOCATION_ID = e.LOCATION_ID
            WHERE SUPPLIER_ID = ' . $supplierId;
    $result = mysqli_query($db, $sql);
    $supplierData = mysqli_fetch_assoc($result);
}

// Fetch provinces and cities for dropdowns
$sqlProvinces = 'SELECT DISTINCT PROVINCE FROM location ORDER BY PROVINCE';
$resultProvinces = mysqli_query($db, $sqlProvinces) or die(mysqli_error($db));

$sqlCities = 'SELECT DISTINCT CITY FROM location ORDER BY CITY';
$resultCities = mysqli_query($db, $sqlCities) or die(mysqli_error($db));

// Prepare options for provinces and cities
$provinceOptions = '';
while ($row = mysqli_fetch_assoc($resultProvinces)) {
    $provinceOptions .= "<option value='" . $row['PROVINCE'] . "'>" . $row['PROVINCE'] . "</option>";
}

$cityOptions = '';
while ($row = mysqli_fetch_assoc($resultCities)) {
    $cityOptions .= "<option value='" . $row['CITY'] . "'>" . $row['CITY'] . "</option>";
}
?>

<script>
window.onload = function() {
    // Handle Province and City selection for Edit form
    var provinces = <?php echo json_encode($provinceOptions); ?>;
    var cities = <?php echo json_encode($cityOptions); ?>;
    
    // Populate the province and city dropdowns for Edit form
    var provinceSelect = document.getElementById('province');
    provinces.forEach(function(province) {
        var option = document.createElement('option');
        option.text = province;
        provinceSelect.add(option);
    });

    var citySelect = document.getElementById('city');
    cities.forEach(function(city) {
        var option = document.createElement('option');
        option.text = city;
        citySelect.add(option);
    });

    // Pre-select the province and city for the edit form
    <?php if ($supplierData): ?>
        document.querySelector("select[name='province']").value = "<?php echo $supplierData['PROVINCE']; ?>";
        document.querySelector("select[name='city']").value = "<?php echo $supplierData['CITY']; ?>";
    <?php endif; ?>
};
</script>

<center>
    <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Edit Supplier</h4>
        </div>
        <a href="supplier.php?action=add" type="button" class="btn btn-primary bg-gradient-primary">Back</a>
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="post" action="sup_transac.php?action=edit">
                    <!-- Hidden field for the supplier ID -->
                    <input type="hidden" name="id" value="<?php echo $supplierId; ?>">

                    <!-- Company Name -->
                    <div class="form-group">
                        <input class="form-control" placeholder="Company Name" name="companyname" value="<?php echo $supplierData['COMPANY_NAME'] ?? ''; ?>" required>
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
                        <input class="form-control" placeholder="Phone Number" name="phonenumber" value="<?php echo $supplierData['PHONE_NUMBER'] ?? ''; ?>" required>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check fa-fw"></i> Save</button>
                    <button type="reset" class="btn btn-danger btn-block"><i class="fa fa-times fa-fw"></i> Reset</button>

                </form>
            </div>
        </div>
    </div>
</center>

<?php
include '../includes/footer.php';
?>
