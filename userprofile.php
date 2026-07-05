<?php
session_start();
include "database.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login first!'); window.location='user.php';</script>";
    exit;
}

$username = $_SESSION['username'];

// Fetch user details
$query = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('User not found!'); window.location='user.php';</script>";
    exit;
}

$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$success = '';

if (isset($_POST['update'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $new_username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $qualification = $_POST['qualification'];
    $gender = $_POST['gender'] ?? '';
    $skills = isset($_POST['skills']) ? implode(", ", $_POST['skills']) : '';

    // Validation
    if (empty($fname)) $error1 = "*First name is required";
    if (empty($lname)) $error2 = "*Last name is required";
    if (empty($new_username)) $error3 = "*Username is required";
    if (empty($email)) $error5 = "*Email is required";
    if (empty($qualification)) $error6 = "*Qualification is required";
    if (empty($skills)) $error7 = "*Select at least one skill";
    if (empty($gender)) $error8 = "*Select gender";

    // Check username uniqueness
    $check_user = mysqli_query($con, "SELECT * FROM user WHERE username='$new_username' AND uid != '{$user['uid']}'");
    if ($check_user && mysqli_num_rows($check_user) > 0)
        $error3 = "*Username already taken";

    if (
        empty($error1) && empty($error2) && empty($error3) && empty($error4) &&
        empty($error5) && empty($error6) && empty($error7) && empty($error8)
    ) {
        // Optional password update
        $password_sql = '';
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $password_sql = ", password = '$hashed_password'";
        }

        $update_sql = "UPDATE user SET 
            fname = '$fname',
            lname = '$lname',
            username = '$new_username',
            email = '$email',
            qualification = '$qualification',
            gender = '$gender',
            skills = '$skills'
            $password_sql
            WHERE username = '$username'";

        if (mysqli_query($con, $update_sql)) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='userprofile.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error updating profile: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - Edit Profile</title>
    <!-- <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="style3.css">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="headername">Job Finding System</div>
        <div style="text-align:center; font-size: 1.5em;">User Panel</div>
        <div class="loginbuttons">
            <a href="logout.php" style="color: white; text-decoration: none;">
                <button>Logout</button>
            </a>
        </div>
    </div>


    <div class="sidebar">
        <ul class="nav-links">
            <li><a href="userhomepage.php">Home</a></li>
            <li><a href="userviewjobs.php">View Jobs</a></li>
            <li><a href="userjobrequeststatus.php">Job Request Status</a></li>
            <li><a href="useraccepted.php">View accepted jobs</a></li>
            <li><a href="userrejected.php" >View Rejected Jobs</a></li>
            <li><a href="userprofile.php" class="active">Profile</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content">
            <form method="post" class="login-form">
                <h1>Edit Profile</h1>

                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-input" value="<?php echo $user['fname']; ?>">
                <div class="error"><?php echo $error1; ?></div>

                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-input" value="<?php echo $user['lname']; ?>">
                <div class="error"><?php echo $error2; ?></div>

                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" value="<?php echo $user['username']; ?>">
                <div class="error"><?php echo $error3; ?></div>

                <label class="form-label">New Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="form-input" placeholder="Enter new password">

                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="<?php echo $user['email']; ?>">
                <div class="error"><?php echo $error5; ?></div>

                <label class="form-label">Gender</label>
                <div class="checkboxfont">
                    <input type="radio" name="gender" value="Male" <?php if ($user['gender'] == "Male") echo "checked"; ?>> Male
                    <input type="radio" name="gender" value="Female" <?php if ($user['gender'] == "Female") echo "checked"; ?>> Female
                    <input type="radio" name="gender" value="Other" <?php if ($user['gender'] == "Other") echo "checked"; ?>> Other
                </div>
                <div class="error"><?php echo $error8; ?></div>

                <label class="form-label">Qualification</label>
                <select name="qualification" class="form-input">
                    <option value="">-- Select Qualification --</option>
                    <option value="High School" <?php if ($user['qualification'] == "High School") echo "selected"; ?>>High School</option>
                    <option value="+2" <?php if ($user['qualification'] == "+2") echo "selected"; ?>>+2</option>
                    <option value="Bachelor" <?php if ($user['qualification'] == "Bachelor") echo "selected"; ?>>Bachelor Degree</option>
                    <option value="Master" <?php if ($user['qualification'] == "Master") echo "selected"; ?>>Master Degree</option>
                    <option value="Other" <?php if ($user['qualification'] == "Other") echo "selected"; ?>>Other</option>
                </select>
                <div class="error"><?php echo $error6; ?></div>

                <label class="form-label">Skills</label>
                <div class="checkboxfont">
                    <?php
                    $all_skills = ["HTML", "CSS", "JavaScript", "PHP", "MySQL", "Python", "Java", "C++", "Other"];
                    $user_skills = explode(", ", $user['skills']);
                    foreach ($all_skills as $skill) {
                        $checked = in_array($skill, $user_skills) ? "checked" : "";
                        echo "<input type='checkbox' name='skills[]' value='$skill' $checked> $skill ";
                    }
                    ?>
                </div>
                <div class="error"><?php echo $error7; ?></div><br>

                <button type="submit" name="update" class="btn">Update Profile</button><br><br>
                <button type="button" class="btn" onclick="window.location.href='userprofile.php'">Go Back</button>
            </form>
        </div>
    </div>
</body>
</html>
