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
        <!-- <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style2.css"> -->
        <link rel="stylesheet" href="style3.css">
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <div class="headername">Job Finding <Style></Style>stem</div>
            <div style="text-align:center; font-size: 1.8em;">User Panel</div>

            <div class="loginbuttons">
                <a href="userprofile.php"style="color: white; text-decoration: none;"><button>Profile</button></a>
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <button>Logout</button>
                </a>
            </div>
        </div>
        <!-- Sidebar Navbar -->
        <div class="sidebar">
            <ul class="nav-links">
                <li><a href="userhomepage.php" class="active">Home</a></li>
                <li><a href="userviewjobs.php">View Jobs</a></li>
                
                <li><a href="userjobrequeststatus.php">Job Request Status</a></li>
                <li><a href="useraccepted.php">View accepted jobs</a></li>
                <li><a href="userrejected.php" >View Rejected Jobs</a></li>
                <li><a href="userprofile.php">Profile</a></li>
            </ul>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <div class="content">
                <div class="welcome">
                    <p>Welcome, <span><?php echo ($_SESSION["username"]); ?></span>! You are logged in to
                        the Job Finding System.</p>
                </div>

                <h1>Recent jobs</h1>
        

                <?php
                include "database.php";

                // Fetch 6 most recent jobs by openeddate
                $query = "SELECT * FROM jobs ORDER BY openeddate DESC LIMIT 6";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo "<div class='jobs'>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "
        <div class='job-card'>
            <img src='images/" . ($row['image']) . "' alt='" . ($row['title']) . "'>
            <h3>" . ($row['title']) . "</h3>
            <p><strong>Description:</strong> " . ($row['description']) . "</p>
            <p><strong>Location:</strong> " . ($row['location']) . "</p>
            <p><strong>Qualification:</strong> " . ($row['qualification']) . "</p>
            <p><strong>Salary:</strong> " . ($row['salary']) . "</p>
            <p><strong>Category:</strong> " . ($row['category']) . "</p>
            <p><strong>Posted On:</strong> " . date("d-m-Y", strtotime($row['openeddate'])) . "</p>
            <p><strong>Expiry Date:</strong> " . date("d-m-Y", strtotime($row['expirydate'])) . "</p>
            <button class='btn' onclick=\"window.location.href='applyjob.php?job_id={$row['id']}'\">Apply</button>
        </div>
        ";
                    }

                    echo "</div>";
                } else {
                    echo "<p style='font-size:1.2rem; color:gray;'>No recent jobs available.</p>";
                }

                ?>
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