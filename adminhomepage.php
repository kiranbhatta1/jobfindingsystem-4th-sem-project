<?php
session_start();
if (isset($_SESSION["username"])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>JFS</title>
        <!-- <link rel="stylesheet" href="style.css"> -->
         <link rel="stylesheet" href="style3.css">
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <div class="headername">Job Finding System</div>
            <div style="text-align:center; font-size: 1.5em;">Admin Panel</div>

            <div class="loginbuttons">
            
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <button>Logout</button>
                </a>
            </div>
        </div>
        <!-- Sidebar Navbar -->
        <div class="sidebar">
            <ul class="nav-links">
                <li><a href="adminhomepage.php" class="active">Home</a></li>
                <li><a href="adminviewcompany.php">view Companyes</a></li>

                <li><a href="adminviewuser.php">view Users</a></li>
                <li><a href="adminviewjobs.php"> view Jobs </a></li>
                <li><a href="adminviewpendingrequest.php">All pending Applications</a></li>
                <li><a href="allacceptedapplicant.php">All Accepted applicant </a></li>
                <li><a href="allrejectedapplicant.php">All Rejected applicant </a></li>

               


            </ul>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <div class="content">
                <div class="welcome">
                    <p>Welcome, <span><?php echo ($_SESSION["username"]); ?></span> You are logged in to the Job Finding
                        System.</p>
                </div>
                <h1>Dashboard Overview</h1>
                <p>THIS IS HOME PAGE</p>
                <p>Select an option from the navigation menu to operate on Companyes, Users, Jobs, and manage application </p>
            </div>
        </div>
    </body>

    </html>
    <?php
} else {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
}
?>