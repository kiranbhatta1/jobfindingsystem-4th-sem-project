<?php
session_start();
if (isset($_SESSION["username"])) {
    include "database.php"; // DB connection

    // Job categories array
    $job_categories = [
        "IT & Software",
        "Marketing & Sales",
        "Finance & Accounting",
        "Healthcare",
        "Education & Training",
        "Engineering",
        "Hospitality & Tourism",
        "Customer Service",
        "Human Resources",
        "Legal",
        "Construction",
        "Transport & Logistics",
        "Design & Creative",
        "Manufacturing",
        "Retail"
    ];

    // Initialize category filter
    $selectedCategory = '';
    if (isset($_POST['Filter'])) {
        $selectedCategory = $_POST['category'];
        if ($selectedCategory != 'all') {
            $sql = "SELECT * FROM jobs WHERE category='$selectedCategory' ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM jobs ORDER BY id DESC";
        }
    } else {
        $sql = "SELECT * FROM jobs ORDER BY id DESC";
    }

    $result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JFS - Manage Jobs</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="headername">Job Finding System</div>
        <div style="text-align:center; font-size: 1.8em;">Admin Panel</div>
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
            <li><a href="adminviewuser.php">View Users</a></li>
            <li><a href="adminviewjobs.php" class="active">View Jobs</a></li>
            <li><a href="adminviewpendingrequest.php">All Pending Applications</a></li>
            <li><a href="allacceptedapplicant.php">All Accepted Applicant</a></li>
            <li><a href="allrejectedapplicant.php">All Rejected Applicant</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <h2>Manage Posted Jobs</h2>

            <!-- Filter Section -->
            <div class="filter-container">
                <form method="post">
                    <label for="category"><strong>Select Job Category:</strong></label>
                    <select name="category" id="category">
                        <option value="all">-- All Categories --</option>
                        <?php foreach ($job_categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php if ($selectedCategory == $cat) echo "selected"; ?>>
                                <?php echo $cat; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" name="Filter" value="Filter" class="submitbtn">
                </form>
            </div>

            <!-- Jobs Display (Table Format) -->
            <?php
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="table-container">';
                echo "<table>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Qualification</th>
                            <th>Salary</th>
                            <th>Category</th>
                            <th>Posted On</th>
                            <th>Expiry Date</th>
                            <th>Action</th>
                        </tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    $company_id = $row['company_id'];
                    $company_name = "Unknown";

                    // Fetch company name separately
                    $company_query = "SELECT company_name FROM company WHERE cid = $company_id";
                    $company_result = mysqli_query($con, $company_query);
                    if ($company_result && mysqli_num_rows($company_result) > 0) {
                        $company_data = mysqli_fetch_assoc($company_result);
                        $company_name = $company_data['company_name'];
                    }

                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$company_name}</td>
                            <td>{$row['title']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['qualification']}</td>
                            <td>{$row['salary']}</td>
                            <td>{$row['category']}</td>
                            <td>" . date("d-m-Y", strtotime($row['openeddate'])) . "</td>
                            <td>" . date("d-m-Y", strtotime($row['expirydate'])) . "</td>
                            <td>
                                <button class='action-btn edit-btn' onclick=\"window.location.href='admineditjobs.php?job_id={$row['id']}'\">Edit</button>
                                <button class='action-btn delete-btn' onclick=\"if(confirm('Are you sure you want to delete this job?')) window.location.href='admindeletejobs.php?job_id={$row['id']}';\">Delete</button>
                            </td>
                        </tr>";
                }

                echo "</table>";
                echo '</div>';
            } else {
                echo "<p style='font-size:1.5rem;text-align:center;'>No jobs available in this category.</p>";
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
