<?php
session_start();
include "database.php";

// Check if admin is logged in
if (!isset($_SESSION["username"])) {
    echo '<script>
            alert("Please login first");
            window.location.href = "index.php";
          </script>';
    exit;
}

// Check if company ID is provided
if (isset($_GET['cid'])) {
    $cid = intval($_GET['cid']); // prevent SQL injection

    // First, check if the company exists
    $check_sql = "SELECT * FROM company WHERE cid = $cid";
    $check_result = mysqli_query($con, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {

        // (Optional) — Delete related jobs and applications if needed
        // Delete job applications for jobs posted by this company
        $delete_apps = "DELETE FROM jobapplication WHERE job_id IN (SELECT id FROM jobs WHERE company_id = $cid)";
        mysqli_query($con, $delete_apps);

        // Delete jobs posted by this company
        $delete_jobs = "DELETE FROM jobs WHERE company_id = $cid";
        mysqli_query($con, $delete_jobs);

        // Finally, delete the company
        $delete_sql = "DELETE FROM company WHERE cid = $cid";
        if (mysqli_query($con, $delete_sql)) {
            echo '<script>
                    alert("Company and related data deleted successfully!");
                    window.location.href = "adminviewcompany.php";
                  </script>';
        } else {
            echo '<script>
                    alert("Error deleting company. Please try again.");
                    window.location.href = "adminviewcompany.php";
                  </script>';
        }
    } else {
        echo '<script>
                alert("Company not found!");
                window.location.href = "adminviewcompany.php";
              </script>';
    }
} else {
    echo '<script>
            alert("Invalid request!");
            window.location.href = "adminviewcompany.php";
          </script>';
}
?>
