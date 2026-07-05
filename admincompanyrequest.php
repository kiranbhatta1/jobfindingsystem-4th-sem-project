<?php
session_start();
include "database.php";

if (isset($_SESSION["username"])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>JFS | View Companies</title>
        <!-- <link rel="stylesheet" href="style.css"> -->
        <link rel="stylesheet" href="style3.css">
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <div class="headername">Job Finding System</div>
            <div style="text-align:center; font-size: 1.5em;">Admin Panel</div>

            <div class="loginbuttons">
                <button>Profile</button>
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <button>Logout</button>
                </a>
            </div>
        </div>

       
        <div class="sidebar">   
            <ul class="nav-links">
                <li><a href="adminhomepage.php">Home</a></li>
                <li><a href="adminviewcompany.php" class="active">View Companies</a></li>
                <li><a href="adminviewuser.php">View Users</a></li>
                <li><a href="addcategory.php">Add Job Category</a></li>
                <li><a href="admincompanyrequest.php">Company Requests</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="content">
                <div class="welcome">
                    <p>Welcome, <span><?php  echo ($_SESSION["username"]); ?></span>! You are logged in to the Job Finding System.</p>
                </div>
                <h1>Dashboard Overview</h1>
                <p>Below are the registered companies in the Job Finding System:</p>

                <!-- ✅ View Companies Table -->
                <h2>Registered Companies</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Company Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>PAN</th>
                        <th>License</th>
                        <th>Category</th>
                    </tr>
                    <?php
                    $sql = "SELECT * FROM companyregister";
                    $result = mysqli_query($con, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['company_name']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['company_pan']}</td>
                                    <td>{$row['company_license']}</td>
                                    <td>{$row['company_category']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No companies registered yet.</td></tr>";
                    }
                    ?>
                </table>
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
