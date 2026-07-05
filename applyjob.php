<?php
session_start(); // Start session to get logged-in user
include "database.php"; // Database connection

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login first!'); window.location='index.php';</script>";
    exit;
}

$username = $_SESSION['username']; // Current logged-in username

// Initialize variables
$error1 = $error2 = $error3 = $error4 = $error5 = $error6 = $error7 = $error8 = '';
$fullname = $email = $phone = $address = $skills = $experiences = '';
$job_id = $_GET['job_id'] ?? ''; // Get job ID from URL

if (isset($_POST["apply"])) {
    $job_id = $_POST["job_id"];
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $skills = trim($_POST["skills"]);
    $experiences = trim($_POST["experiences"]);

    // File upload
    $cv = $_FILES["cv"]["name"];
    $photo = $_FILES["photo"]["name"];
    $cv_tmp = $_FILES["cv"]["tmp_name"];
    $photo_tmp = $_FILES["photo"]["tmp_name"];

    $upload_dir_cv = "cvs/";
    $upload_dir_photo = "photos/";

    if (!is_dir($upload_dir_cv)) mkdir($upload_dir_cv, 0777, true);
    if (!is_dir($upload_dir_photo)) mkdir($upload_dir_photo, 0777, true);

    $cv_path = $upload_dir_cv . basename($cv);
    $photo_path = $upload_dir_photo . basename($photo);

    // Validation
    if (empty($fullname)) $error1 = "*Full name is required";
    if (empty($email)) $error2 = "*Email is required";
    if (empty($phone)) $error3 = "*Phone number is required";
    if (empty($address)) $error4 = "*Address is required";
    if (empty($skills)) $error5 = "*Skills field is required";
    if (empty($experiences)) $error6 = "*Experience field is required";
    if (empty($cv)) $error7 = "*Please upload your CV";
    if (empty($photo)) $error8 = "*Please upload your photo";

    // Proceed if no errors
    if (empty($error1) && empty($error2) && empty($error3) && empty($error4) &&
        empty($error5) && empty($error6) && empty($error7) && empty($error8)) {

        // Upload files
        if (!move_uploaded_file($cv_tmp, $cv_path)) {
            echo "<script>alert('Failed to upload CV.');</script>";
        }
        if (!move_uploaded_file($photo_tmp, $photo_path)) {
            echo "<script>alert('Failed to upload photo.');</script>";
        }

        // Direct insert without mysqli_real_escape_string
        $sql = "INSERT INTO jobapplication
                (job_id, fullname, email, phone, address, skills, experiences, cv, photo, applied_date, username)
                VALUES
                ('$job_id', '$fullname', '$email', '$phone', '$address', '$skills', '$experiences', '$cv', '$photo', NOW(), '$username')";

        $res = mysqli_query($con, $sql);

        if ($res) {
            echo "<script>alert('Job Application Submitted Successfully!'); window.location='userviewjobs.php';</script>";
            exit;
        } else {
            echo "<script>alert('Database Error: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply for Job</title>
<link rel="stylesheet" href="style3.css">
</head>
<body>
<div class="header">
    <div class="headername">Job Finding System</div>
    <div style="text-align:center; font-size: 1.5em;">User Panel</div>
    <div class="loginbuttons">
        <button>Profile</button>
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
        <li><a href="userprofile.php">Profile</a></li>
        <li><a href="applyjob.php" class="active">Apply Job</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="content">
        <h2 style="text-align: center; margin-bottom: 20px;">Job Application Form</h2>

        <form method="post" enctype="multipart/form-data" class="login-form">
            <input type="hidden" name="job_id" value="<?php echo ($job_id); ?>">

            <label class="form-label">Username</label>
            <input type="text" class="form-input" value="<?php echo ($username); ?>" readonly>

            <label class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-input" value="<?php echo ($fullname); ?>">
            <div class="error"><?php echo $error1; ?></div>

            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="<?php echo ($email); ?>">
            <div class="error"><?php echo $error2; ?></div>

            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-input" value="<?php echo ($phone); ?>">
            <div class="error"><?php echo $error3; ?></div>

            <label class="form-label">Address</label>
            <textarea name="address" class="form-input" rows="3"><?php echo ($address); ?></textarea>
            <div class="error"><?php echo $error4; ?></div>

            <label class="form-label">Skills</label>
            <input type="text" name="skills" class="form-input" value="<?php echo ($skills); ?>">
            <div class="error"><?php echo $error5; ?></div>

            <label class="form-label">Work Experience</label>
            <textarea name="experiences" class="form-input" rows="3"><?php echo ($experiences); ?></textarea>
            <div class="error"><?php echo $error6; ?></div>

            <label class="form-label">Upload CV (PDF)</label>
            <input type="file" name="cv" class="form-input" accept=".pdf">
            <div class="error"><?php echo $error7; ?></div>

            <label class="form-label">Upload Photo (JPG/PNG)</label>
            <input type="file" name="photo" class="form-input" accept="image/*">
            <div class="error"><?php echo $error8; ?></div>

            <button type="submit" name="apply" class="btn">Submit Application</button><br><br>
            <button type="button" class="btn" onclick="window.location.href='userviewjobs.php'">Back to Jobs</button>
        </form>
    </div>
</div>
</body>
</html>
