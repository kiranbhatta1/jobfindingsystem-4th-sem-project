<?php
include "database.php";

$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$fname = $lname = $username = $email = $qualification = $gender = '';
$skills = [];

if (isset($_POST["register"])) {

    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $qualification = $_POST["qualification"];
    $gender = $_POST["gender"] ?? '';
    $skills = isset($_POST["skills"]) ? $_POST["skills"] : [];

    // Check for empty fields first
    if (empty($fname))
        $error1 = "*First name is required";
    if (empty($lname))
        $error2 = "*Last name is required";
    if (empty($username))
        $error3 = "*Username is required";
    if (empty($password))
        $error4 = "*Password is required";
    if (empty($email))
        $error5 = "*Email is required";
    if (empty($qualification))
        $error6 = "*Qualification must be chosen";
    if (empty($skills))
        $error7 = "*Please select at least one skill";
    if (empty($gender))
        $error8 = "*Please select your gender";

    if (!empty($username)) {
        $check_user = mysqli_query($con, "SELECT * FROM user WHERE username = '$username'");
        if (mysqli_num_rows($check_user) > 0) {
            $error3 = "*Username already taken";
        }
    }

    if (empty($error1) && empty($error2) && empty($error3) && empty($error4) && empty($error5) && empty($error6) && empty($error7) && empty($error8)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $skills_str = implode(", ", $skills);

        $sql = "INSERT INTO user (fname, lname, username, password, email, qualification, skills,gender,datecreated ) 
                VALUES ('$fname', '$lname', '$username', '$hashed_password', '$email', '$qualification', '$skills_str','$gender', CURRENT_TIMESTAMP())";
        $res = mysqli_query($con, $sql);

        if ($res) {
            echo "<script>alert('Registration successful!'); window.location='index.php';</script>";
            exit;
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
    <title>Online Job Finding System</title>
    <!-- <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="style3.css">
</head>

<body>
    <div class="header">
        <h1>Online Job Finding System</h1>
        <h2>Welcome User Registration</h2>
    </div>

    <form method="post" class="login-form">
        <h1>Register</h1>

        <label for="fname" class="form-label">First Name</label>
        <input type="text" name="fname" placeholder="First Name" class="form-input" value="<?php echo $fname; ?>">
        <div class="error"><?php echo $error1; ?></div>

        <label for="lname" class="form-label">Last Name</label>
        <input type="text" name="lname" placeholder="Last Name" class="form-input" value="<?php echo $lname; ?>">
        <div class="error"><?php echo $error2; ?></div>

        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" placeholder="Username" class="form-input" value="<?php echo $username; ?>">
        <div class="error"><?php echo $error3; ?></div>

        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" placeholder="Password" class="form-input">
        <div class="error"><?php echo $error4; ?></div>

        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" placeholder="Email" class="form-input" value="<?php echo $email; ?>">
        <div class="error"><?php echo $error5; ?></div>

        <label class="form-label">Gender</label>
        <div class="checkboxfont">
            <input type="radio" name="gender" value="Male" <?php if ($gender == "Male")
                echo "checked"; ?>> Male
            <input type="radio" name="gender" value="Female" <?php if ($gender == "Female")
                echo "checked"; ?>> Female
            <input type="radio" name="gender" value="Other" <?php if ($gender == "Other")
                echo "checked"; ?>> Other
        </div>
        <div class="error"><?php echo $error8; ?></div>


        <label class="form-label">Qualification</label>
        <select name="qualification" class="form-input">
            <option value="">-- Select Qualification --</option>
            <option value="High School" <?php if ($qualification == "High School")
                echo "selected"; ?>>High School
            </option>
            <option value="+2" <?php if ($qualification == "+2")
                echo "selected"; ?>>+2</option>
            <option value="Bachelor" <?php if ($qualification == "Bachelor")
                echo "selected"; ?>>Bachelor Degree</option>
            <option value="Master" <?php if ($qualification == "Master")
                echo "selected"; ?>>Master Degree</option>
            <option value="Other" <?php if ($qualification == "Other")
                echo "selected"; ?>>Other</option>
        </select>
        <div class="error"><?php echo $error6; ?></div>

        <label class="form-label">Skills</label>
        <div class="checkboxfont">
            <input type="checkbox" name="skills[]" value="HTML" <?php if (!empty($skills) && in_array("HTML", $skills))
                echo "checked"; ?>> HTML
            <input type="checkbox" name="skills[]" value="CSS" <?php if (!empty($skills) && in_array("CSS", $skills))
                echo "checked"; ?>> CSS
            <input type="checkbox" name="skills[]" value="JavaScript" <?php if (!empty($skills) && in_array("JavaScript", $skills))
                echo "checked"; ?>> JavaScript<br>
            <input type="checkbox" name="skills[]" value="PHP" <?php if (!empty($skills) && in_array("PHP", $skills))
                echo "checked"; ?>> PHP
            <input type="checkbox" name="skills[]" value="MySQL" <?php if (!empty($skills) && in_array("MySQL", $skills))
                echo "checked"; ?>> MySQL
            <input type="checkbox" name="skills[]" value="Python" <?php if (!empty($skills) && in_array("Python", $skills))
                echo "checked"; ?>> Python<br>
            <input type="checkbox" name="skills[]" value="Java" <?php if (!empty($skills) && in_array("Java", $skills))
                echo "checked"; ?>> Java
            <input type="checkbox" name="skills[]" value="C++" <?php if (!empty($skills) && in_array("C++", $skills))
                echo "checked"; ?>> C++
            <input type="checkbox" name="skills[]" value="Other" <?php if (!empty($skills) && in_array("Other", $skills))
                echo "checked"; ?>> Other<br>
        </div>

        <div class="error"><?php echo $error7 ?? ''; ?></div>

        <button type="submit" name="register" class="btn">Register</button><br><br>
        
        <button type="button" class="btn" onclick="window.location.href='user.php'">Already have an account</button><br><br>

    </form>

    <div class="footer">
        <p style="font-size: 1.9rem; text-align: center;">
            &copy;2025 Created by Bashanta and Kiran. Online Job Finding System. All Rights Reserved.
        </p>
    </div>
</body>

</html>