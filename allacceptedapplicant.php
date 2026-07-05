<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php"; // DB connection

    // Fetch all accepted applications
    $sql = "SELECT * FROM accepted_application ORDER BY id DESC";
    $result = mysqli_query($con, $sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>JFS - Accepted Applications</title>
        <!-- <link rel="stylesheet" href="style.css"> -->
        <!-- <link rel="stylesheet" href="style2.css"> -->
        <link rel="stylesheet" href="style3.css">
    </head>
    <body>
        <!-- Header -->
        <div class="header">
            <div class="headername">Job Finding System</div>
            <div style="text-align:center; font-size: 1.8em;">Admin Panel</div>
            <div class="loginbuttons">
                <a href="logout.php" style="color: white; text-decoration: none;"><button>Logout</button></a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <ul class="nav-links">
                <li><a href="adminhomepage.php">Home</a></li>
                <li><a href="adminviewcompany.php">View Companies</a></li>
                <li><a href="adminviewuser.php">View Users</a></li>
                <li><a href="adminviewjobs.php">View Jobs</a></li>
                <li><a href="adminviewpendingrequest.php">All pending Applications</a></li>
                <li><a href="adminviewacceptedapplications.php" class="active">Accepted Applications</a></li>
                <li><a href="allrejectedapplicant.php">All Rejected applicant </a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content">
                <h2>All Accepted Job Applications</h2>

                <?php
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="table-container">';
                    echo "<table>
                            <tr>
                                <th>ID</th>
                                <th>Application ID</th>
                                <th>Job ID</th>
                                <th>Company Name</th>
                                <th>Job Title</th>
                                <th>Applicant Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Skills</th>
                                <th>Experience</th>
                                <th>Accepted Date</th>
                                <th>CV</th>
                                <th>Action</th>
                            </tr>";
                         

                    while ($row = mysqli_fetch_assoc($result)) {
                        $job_id = $row['job_id'];
                        $company_name = "Unknown";
                        $job_title = "Unknown";

                        // Fetch job info
                        $job_query = "SELECT title, company_id FROM jobs WHERE id = $job_id";
                        $job_result = mysqli_query($con, $job_query);
                        if ($job_result && mysqli_num_rows($job_result) > 0) {
                            $job_data = mysqli_fetch_assoc($job_result);
                            $job_title = $job_data['title'];
                            $company_id = $job_data['company_id'];

                            // Fetch company name
                            $company_query = "SELECT company_name FROM company WHERE cid = $company_id";
                            $company_result = mysqli_query($con, $company_query);
                            if ($company_result && mysqli_num_rows($company_result) > 0) {
                                $company_data = mysqli_fetch_assoc($company_result);
                                $company_name = $company_data['company_name'];
                            }
                        }
                       

                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['application_id']}</td>
                                <td>{$job_id}</td>
                                <td>{$company_name}</td>
                                <td>{$job_title}</td>
                                <td>{$row['fullname']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['skills']}</td>
                                <td>{$row['experiences']}</td>
                                <td>" . date("d-m-Y", strtotime($row['accepted_at'])) . "</td>
                                <td><a href='uploads/{$row['cv']}' target='_blank'>View CV</a></td>
                                <td>
                                    <button class='action-btn delete-btn' onclick=\"if(confirm('Delete this accepted application?')) window.location.href='admindeleteacceptedapplication.php?id={$row['id']}';\">Delete</button>
                                </td>
                              </tr>";
                             
                    }
                    echo "</table>";
                     echo '</div>';
                } else {
                    echo "<p style='text-align:center;font-size:1.3rem;'>No accepted applications found.</p>";
                }
                ?>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    echo '<script>alert("Please login first"); window.location.href = "index.php";</script>';
}
?>
