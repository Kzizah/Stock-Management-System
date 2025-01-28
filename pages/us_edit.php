<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

// Check if the user is an admin
$query = 'SELECT ID, t.TYPE FROM users u JOIN type t ON t.TYPE_ID = u.TYPE_ID WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $userType = $row['TYPE'];
    if ($userType == 'User') {
        echo '<script type="text/javascript">
            alert("Restricted Page! You will be redirected to POS.");
            window.location = "pos.php";
        </script>';
        exit();
    }
}

// Fetch options for the TYPE dropdown
$sql = "SELECT DISTINCT TYPE, TYPE_ID FROM type";
$typeResult = mysqli_query($db, $sql) or die("Bad SQL: $sql");

$typeOptions = "<select class='form-control' name='type_id'>";
while ($row = mysqli_fetch_assoc($typeResult)) {
    $typeOptions .= "<option value='" . $row['TYPE_ID'] . "'>" . $row['TYPE'] . "</option>";
}
$typeOptions .= "</select>";

// Fetch user details for editing
$query = "SELECT ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, USERNAME, PASSWORD, e.EMAIL, PHONE_NUMBER, j.JOB_TITLE, e.HIRED_DATE, t.TYPE, t.TYPE_ID, l.PROVINCE, l.CITY
          FROM users u
          JOIN employee e ON u.EMPLOYEE_ID = e.EMPLOYEE_ID
          JOIN job j ON e.JOB_ID = j.JOB_ID
          JOIN location l ON e.LOCATION_ID = l.LOCATION_ID
          JOIN type t ON u.TYPE_ID = t.TYPE_ID
          WHERE ID = " . $_GET['id'];
$userResult = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_array($userResult)) {
    $id = $row['ID'];
    $firstName = $row['FIRST_NAME'];
    $lastName = $row['LAST_NAME'];
    $gender = $row['GENDER'];
    $username = $row['USERNAME'];
    $password = $row['PASSWORD'];
    $email = $row['EMAIL'];
    $phone = $row['PHONE_NUMBER'];
    $jobTitle = $row['JOB_TITLE'];
    $hiredDate = $row['HIRED_DATE'];
    $province = $row['PROVINCE'];
    $city = $row['CITY'];
    $currentType = $row['TYPE'];
    $currentTypeId = $row['TYPE_ID'];
}
?>

<center>
<div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Edit User Account</h4>
    </div>
    <a type="button" class="btn btn-primary bg-gradient-primary btn-block" href="user.php?"> 
        <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back 
    </a>
    <div class="card-body">
        <form role="form" method="post" action="us_edit1.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />

            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    First Name:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="First Name" name="firstname" value="<?php echo $firstName; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Last Name:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="Last Name" name="lastname" value="<?php echo $lastName; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Gender:
                </div>
                <div class="col-sm-9">
                    <select class='form-control' name='gender' required>
                        <option value="" disabled selected hidden>Select Gender</option>
                        <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Username:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="Username" name="username" value="<?php echo $username; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Password:
                </div>
                <div class="col-sm-9">
                    <input type="password" class="form-control" placeholder="Password" name="password" value="" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Email:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Contact #:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="Contact #" name="phone" value="<?php echo $phone; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Role:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="Role" name="role" value="<?php echo $jobTitle; ?>" readonly>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Hired Date:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="Hired Date" name="hireddate" value="<?php echo $hiredDate; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Province:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="Province" name="province" value="<?php echo $province; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    City / Municipality:
                </div>
                <div class="col-sm-9">
                    <input class="form-control" placeholder="City / Municipality" name="city" value="<?php echo $city; ?>" required>
                </div>
            </div>
            <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                    Account Type:
                </div>
                <div class="col-sm-9">
                    <?php echo $typeOptions; ?>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-warning btn-block">
                <i class="fa fa-edit fa-fw"></i> Update
            </button>    
        </form>  
    </div>
</div>
</center>

<?php
include '../includes/footer.php';
?>
