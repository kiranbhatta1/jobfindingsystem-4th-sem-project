<?php
include "database.php";
$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$cname = $username = $email = $address = $pan = $license = $category = '';
$errordb = '';

if (isset($_POST["register"])) {

    $cname = $_POST["cname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $address = $_POST["address"] ?? '';
    $pan = $_POST["pan"];
    $license = $_POST["license"];
    $category = $_POST["category"];

    // ✅ Corrected: check using correct column names from your table
    $check_user = mysqli_query($con, "SELECT * FROM company WHERE username = '$username'");
    $check_pan = mysqli_query($con, "SELECT * FROM company WHERE company_pan = '$pan'");
    $check_license = mysqli_query($con, "SELECT * FROM company WHERE company_license = '$license'");

    // Username check
    if ($check_user === false) {
        $error2 = "*Database query error: " . mysqli_error($con);
    } else {
        if (mysqli_num_rows($check_user) > 0) {
            $error2 = "*Username already taken";
        }
    }

    // PAN check
    if ($check_pan === false) {
        $error5 = "*Database query error: " . mysqli_error($con);
    } else {
        if (mysqli_num_rows($check_pan) > 0) {
            $error5 = "*Company PAN already exists";
        }
    }

    // License check
    if ($check_license === false) {
        $error6 = "*Database query error: " . mysqli_error($con);
    } else {
        if (mysqli_num_rows($check_license) > 0) {
            $error6 = "*Company License already exists";
        }
    }

    // Validation
    if (empty($cname)) $error1 = "*Company Name is required";
    if (empty($username)) $error2 = "*Username is required";
    if (empty($password)) $error3 = "*Password is required";
    if (empty($email)) $error4 = "*Email is required";
    if (empty($address)) $error8 = "*Address is required";
    if (empty($pan)) $error5 = "*Company PAN is required";
    if (empty($license)) $error6 = "*Company License Number is required";
    if (empty($category)) $error7 = "*Please select Company Category";

    if (empty($error1) && empty($error2) && empty($error3) && empty($error4) && 
        empty($error5) && empty($error6) && empty($error7) && empty($error8)) {
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database including address
       $sql = "INSERT INTO `company`(`cid`, `company_name`, `username`, `password`, `email`, `address`, `company_pan`, `company_license`, `company_type`,datecreated) 
        VALUES (NULL, '$cname', '$username', '$hashed_password', '$email', '$address', '$pan', '$license', '$category',CURRENT_TIMESTAMP())";

        $res = mysqli_query($con, $sql);

        if ($res) {
            echo "<script>alert('Company Registration successful!'); window.location='company.php';</script>";
        } else {
            $errordb = "Registration failed: " . mysqli_error($con);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration | Online Job Finding System</title>
    <!-- <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="style3.css">
</head>

<body>
    <div class="header">
        <h1>Online Job Finding System</h1>
        <h2>Company Registration</h2>
    </div>

    <form method="post" class="login-form">
        <h1>Register Company</h1>

        <label class="form-label">Company Name</label>
        <input type="text" name="cname" placeholder="Company Name" class="form-input" value="<?php echo $cname; ?>">
        <div class="error"><?php echo $error1; ?></div>

        <label class="form-label">Username</label>
        <input type="text" name="username" placeholder="Username" class="form-input" value="<?php echo $username; ?>">
        <div class="error"><?php echo $error2; ?></div>

        <label class="form-label">Password</label>
        <input type="password" name="password" placeholder="Password" class="form-input">
        <div class="error"><?php echo $error3; ?></div>

        <label class="form-label">Email</label>
        <input type="email" name="email" placeholder="Email" class="form-input" value="<?php echo $email; ?>">
        <div class="error"><?php echo $error4; ?></div>

        <label class="form-label">Address</label>
        <input type="text" name="address" placeholder="Company Address" class="form-input" value="<?php echo $address; ?>">
        <div class="error"><?php echo $error8; ?></div>

        <label class="form-label">Company PAN</label>
        <input type="text" name="pan" placeholder="Company PAN" class="form-input" value="<?php echo $pan; ?>">
        <div class="error"><?php echo $error5; ?></div>

        <label class="form-label">Company License Number</label>
        <input type="text" name="license" placeholder="Company License Number" class="form-input" value="<?php echo $license; ?>">
        <div class="error"><?php echo $error6; ?></div>

        <label class="form-label">Company Category</label>
        <select name="category" class="form-input">
            <option value="">-- Select Category --</option>
            <option value="Manufacturing" <?php if($category=="Manufacturing") echo "selected"; ?>>Manufacturing</option>
            <option value="IT" <?php if($category=="IT") echo "selected"; ?>>IT</option>
            <option value="Service" <?php if($category=="Service") echo "selected"; ?>>Service</option>
            <option value="Other" <?php if($category=="Other") echo "selected"; ?>>Other</option>
        </select>
        <div class="error"><?php echo $error7; ?></div>

        <button type="submit" name="register" class="btn">Register</button><br><br>
        <button class="btn"><a href="index.php" style="color:white;">Go Back </a></button>
    </form>

    <div class="footer">
        <p style="font-size: 1.9rem; text-align: center;">
            &copy;2025 Created by Bashanta and Kiran. Online Job Finding System. All Rights Reserved.
        </p>
    </div>
</body>
</html>
