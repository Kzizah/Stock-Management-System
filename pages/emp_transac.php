\<?php
include'../includes/connection.php';

// Check if the form is submitted and if all required fields are set
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['gender'], $_POST['email'], $_POST['phonenumber'], $_POST['jobs'], $_POST['hireddate'], $_POST['province'], $_POST['city'])) {

    // Sanitize input to prevent SQL injection
    $fname = mysqli_real_escape_string($db, $_POST['firstname']);
    $lname = mysqli_real_escape_string($db, $_POST['lastname']);
    $gen = mysqli_real_escape_string($db, $_POST['gender']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone = mysqli_real_escape_string($db, $_POST['phonenumber']);
    $jobb = mysqli_real_escape_string($db, $_POST['jobs']);
    $hdate = mysqli_real_escape_string($db, $_POST['hireddate']);
    $prov = mysqli_real_escape_string($db, $_POST['province']);
    $cit = mysqli_real_escape_string($db, $_POST['city']);

    // Check if the fields are not empty
    if (!empty($fname) && !empty($lname) && !empty($gen) && !empty($email) && !empty($phone) && !empty($jobb) && !empty($hdate) && !empty($prov) && !empty($cit)) {

        // Insert location into the location table
        $query = "INSERT INTO location (LOCATION_ID, PROVINCE, CITY) VALUES (NULL, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ss", $prov, $cit);
        mysqli_stmt_execute($stmt);
        
        // Get the last inserted LOCATION_ID
        $locationId = mysqli_insert_id($db);
        
        // Insert employee into the employee table
        $query2 = "INSERT INTO employee (EMPLOYEE_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, PHONE_NUMBER, JOB_ID, HIRED_DATE, LOCATION_ID) 
                   VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($db, $query2);
        // Here the 'sssssssis' represents the 9 bind parameters
        mysqli_stmt_bind_param($stmt2, "sssssssis", $fname, $lname, $gen, $email, $phone, $jobb, $hdate, $locationId);
        
        if (mysqli_stmt_execute($stmt2)) {
            // Redirect to employee page after successful insertion
            header('Location: employee.php');
            exit;
        } else {
            echo "Error in inserting employee: " . mysqli_error($db);
        }

    } else {
        echo "All fields are required!";
    }

} else {
    echo "Form data is missing!";
}
?>
