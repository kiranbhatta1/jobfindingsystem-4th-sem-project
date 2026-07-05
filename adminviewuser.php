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
        <title>JFS</title>
        <!-- <link rel="stylesheet" href="style.css"> -->
        <!-- <link rel="stylesheet" href="style2.css"> -->
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
                <li><a href="adminhomepage.php">Home</a></li>
                <li><a href="adminviewcompany.php">View Companies</a></li>
                <li><a href="adminviewuser.php" class="active">View Users</a></li>
                <li><a href="adminviewjobs.php">View Jobs</a></li>
                <li><a href="adminviewpendingrequest.php">All pending Applications</a></li>
                <li><a href="allacceptedapplicant.php">All Accepted applicant </a></li>
                <li><a href="allrejectedapplicant.php">All Rejected applicant </a></li>



            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content">
                <div class="welcome">
                    <p>Welcome, <span><?php echo ($_SESSION["username"]); ?></span> You are logged in to the Job Finding System.</p>
                </div>
                <h1>Dashboard Overview</h1>
                <p>Below are the registered users of the Job Finding System:</p>

                <!-- ✅ View Users Table -->
                <h2>Registered Users</h2>
                <div class="table-container">   
                <table>
                    <tr>
                        <th>UID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Qualification</th>
                        <th>Skills</th>
                        <th>Operation</th>
                    </tr>
                    <?php
                    $sql = "SELECT * FROM user";
                    $result = mysqli_query($con, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                            <td>{$row['uid']}</td>
                            <td>{$row['fname']}</td>
                            <td>{$row['lname']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['qualification']}</td>
                            <td>{$row['skills']}</td>
                            <td>
                                <button class='action-btn edit-btn' onclick=\"window.location.href='adminupdateuser.php?uid={$row['uid']}'\">Edit</button>
                                <button class='action-btn delete-btn' onclick=\"if(confirm('Are you sure you want to delete this user?')) window.location.href='adminedeleteuser.php?uid={$row['uid']}';\">Delete</button>
                            </td>
                        </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No users registered yet.</td></tr>";
                    }
                    ?>
                </table>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
} else {
    echo '<script>
            alert("Please login first");
            window.location.href = "admin.php";
          </script>';
}
?>