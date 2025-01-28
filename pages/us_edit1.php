<?php

include('../includes/connection.php');

$zz = $_POST['id'];
$a = $_POST['firstname'];
$b = $_POST['lastname'];
$c = $_POST['gender'];
$d = $_POST['username'];
$e = $_POST['password'];
$f = $_POST['email'];
$g = $_POST['phone'];
$i = $_POST['hireddate'];
$j = $_POST['province'];
$k = $_POST['city'];
$type_id = $_POST['type_id']; // New field for account type

$query = 'UPDATE users u 
            JOIN employee e ON e.EMPLOYEE_ID = u.EMPLOYEE_ID
            JOIN location l ON l.LOCATION_ID = e.LOCATION_ID
            SET 
                e.FIRST_NAME = "' . $a . '", 
                e.LAST_NAME = "' . $b . '", 
                GENDER = "' . $c . '", 
                USERNAME = "' . $d . '", 
                PASSWORD = sha1("' . $e . '"),  
                EMAIL = "' . $f . '", 
                l.PROVINCE = "' . $j . '", 
                l.CITY = "' . $k . '", 
                PHONE_NUMBER = "' . $g . '", 
                HIRED_DATE = "' . $i . '", 
                u.TYPE_ID = "' . $type_id . '"  -- Update account type
            WHERE u.ID = "' . $zz . '"';

$result = mysqli_query($db, $query) or die(mysqli_error($db));

?>
<script type="text/javascript">
    alert("You've updated the account successfully.");
    window.location = "user.php";
</script>
