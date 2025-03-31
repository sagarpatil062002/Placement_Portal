<?php
session_start();

if (empty($_SESSION['id_admin'])) {
    header("Location: index.php");
    exit();
}

require_once("../db.php");

if (isset($_POST['export'])) {
    // Fetch the data for export
    $company = mysqli_real_escape_string($conn, $_POST['company']);

    // Fetch job post ID and details (qualification, maximum salary) for the selected job
    $sql2 = "SELECT id_jobpost, qualification, maximumsalary FROM job_post WHERE jobtitle = '$company'";
    $result2 = $conn->query($sql2);

    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $jobid = $row2['id_jobpost'];
        $jobQualification = $row2['qualification'];
        $jobMaxSalary = $row2['maximumsalary'];

        // Fetch eligible students
        $sql_all_students = "SELECT * FROM users";
        $result_all_students = $conn->query($sql_all_students);

        // Fetch applied students
        $sql_applied = "SELECT * FROM users INNER JOIN apply_job_post ON users.id_user = apply_job_post.id_user WHERE apply_job_post.id_jobpost = '$jobid'";
        $result_applied = $conn->query($sql_applied);

        // Fetch selected students
        $sql_selected = "SELECT * FROM users INNER JOIN apply_job_post ON users.id_user = apply_job_post.id_user WHERE apply_job_post.id_jobpost = '$jobid' AND apply_job_post.status = 'Selected'";
        $result_selected = $conn->query($sql_selected);

        // Prepare the output for Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="students_export.xls"');

        echo '<h3>Students Export</h3>';
        echo '<h4>Eligible Students</h4>';
        echo '<table border="1">';
        echo '<tr><th>Student Name</th><th>Email</th><th>Contact No.</th><th>Qualification</th></tr>';

        // Display Eligible Students
        while ($row = $result_all_students->fetch_assoc()) {
            $sum = $row['hsc'] + $row['ssc'] + $row['ug'] + $row['pg'];
            $total = ($sum / 4);

            if ($total >= $jobMaxSalary && $row['qualification'] == $jobQualification) {
                echo '<tr>';
                echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['contactno'] . '</td>';
                echo '<td>' . $row['qualification'] . '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
        echo '<h4>Applied Students</h4>';
        echo '<table border="1">';
        echo '<tr><th>Student Name</th><th>Email</th><th>Contact No.</th><th>Qualification</th></tr>';

        // Display Applied Students
        while ($row = $result_applied->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['contactno'] . '</td>';
            echo '<td>' . $row['qualification'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '<h4>Selected Students</h4>';
        echo '<table border="1">';
        echo '<tr><th>Student Name</th><th>Email</th><th>Contact No.</th><th>Qualification</th></tr>';

        // Display Selected Students
        while ($row = $result_selected->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['contactno'] . '</td>';
            echo '<td>' . $row['qualification'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        exit(); // Stop further script execution after outputting the Excel data
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Department View</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/AdminLTE.min.css">
    <link rel="stylesheet" href="../css/_all-skins.min.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .scroll-table {
            max-height: 400px;
            overflow-y: scroll;
        }
    </style>
</head>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">

        <?php include 'header.php'; ?>

        <div class="content-wrapper" style="margin-left: 0px;">
            <section id="candidates" class="content-header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-13">
                            <h3 style="text-align: center;">View Eligible, Applied, and Selected Students</h3>

                            <?php
                            // Fetch distinct job titles for dropdown
                            $sql1 = "SELECT DISTINCT jobtitle FROM job_post";
                            $result1 = $conn->query($sql1);

                            if (!$result1) {
                                die("Error in SQL query: " . $conn->error);
                            }
                            ?>

                            <form method="POST">
                                <div class="form-group text-center option">
                                    <select class="form-control select2" style="width: 100%" name="company">
                                        <option value="" selected>Select Company</option>
                                        <?php
                                        if ($result1->num_rows > 0) {
                                            while ($row1 = $result1->fetch_assoc()) {
                                                echo '<option value="' . $row1['jobtitle'] . '">' . $row1['jobtitle'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input name="submit" type="submit" value="Submit" class="btn btn-primary" style="margin-top: 10px;">
                                    <input name="export" type="submit" value="Export to Excel" class="btn btn-success" style="margin-top: 10px;">
                                </div>
                            </form>

                            <?php
                            if (isset($_POST['submit'])) {
                                $company = mysqli_real_escape_string($conn, $_POST['company']);
                                // Fetch job post ID and details (qualification, maximum salary) for the selected job
                                $sql2 = "SELECT id_jobpost, qualification, maximumsalary FROM job_post WHERE jobtitle = '$company'";
                                $result2 = $conn->query($sql2);

                                if ($result2->num_rows > 0) {
                                    $row2 = $result2->fetch_assoc();
                                    $jobid = $row2['id_jobpost'];
                                    $jobQualification = $row2['qualification'];
                                    $jobMaxSalary = $row2['maximumsalary'];

                                    // Fetch all students from the users table
                                    $sql_all_students = "SELECT * FROM users";
                                    $result_all_students = $conn->query($sql_all_students);

                                    // Fetch applied students
                                    $sql_applied = "SELECT * FROM users INNER JOIN apply_job_post ON users.id_user = apply_job_post.id_user WHERE apply_job_post.id_jobpost = '$jobid'";
                                    $result_applied = $conn->query($sql_applied);

                                    // Fetch selected students
                                    $sql_selected = "SELECT * FROM users INNER JOIN apply_job_post ON users.id_user = apply_job_post.id_user WHERE apply_job_post.id_jobpost = '$jobid' AND apply_job_post.status = 'Selected'";
                                    $result_selected = $conn->query($sql_selected);

                                    // Display Eligible Students
                                    echo "<h4>Eligible Students</h4>";
                                    if ($result_all_students->num_rows > 0) {
                                        echo '<div class="scroll-table">';
                                        echo '<table class="table table-hover">';
                                        echo '<thead><tr><th>Student Name</th><th>Email</th><th>Contact No.</th><th>Qualification</th></tr></thead>';
                                        echo '<tbody>';
                                        while ($row = $result_all_students->fetch_assoc()) {
                                            // Calculate total percentage (average of marks)
                                            $sum = $row['hsc'] + $row['ssc'] + $row['ug'] + $row['pg'];
                                            $total = ($sum / 4);

                                            // Check eligibility based on total marks and qualification
                                            if ($total >= $jobMaxSalary && $row['qualification'] == $jobQualification) {
                                                echo '<tr>';
                                                echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
                                                echo '<td>' . $row['email'] . '</td>';
                                                echo '<td>' . $row['contactno'] . '</td>';
                                                echo '<td>' . $row['qualification'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>'; // End of scroll-table
                                    } else {
                                        echo "<p>No eligible students found.</p>";
                                    }

                                    // Display Applied Students
                                    echo "<h4>Applied Students</h4>";
                                    if ($result_applied->num_rows > 0) {
                                        echo '<div class="scroll-table">';
                                        echo '<table class="table table-hover">';
                                        echo '<thead><tr><th>Student Name</th><th>Email</th><th>Contact No.</th><th>Qualification</th></tr></thead>';
                                        echo '<tbody>';
                                        while ($row = $result_applied->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
                                            echo '<td>' . $row['email'] . '</td>';
                                            echo '<td>' . $row['contactno'] . '</td>';
                                            echo '<td>' . $row['qualification'] . '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>'; // End of scroll-table
                                    } else {
                                        echo "<p>No students have applied for this company.</p>";
                                    }

                                    // Display Selected Students
                                    echo "<h4>Selected Students</h4>";
                                    if ($result_selected->num_rows > 0) {
                                        echo '<div class="scroll-table">';
                                        echo '<table class="table table-hover">';
                                        echo '<thead><tr><th>Student Name</th><th>Email</th><th>Contact No.</th><th>Qualification</th></tr></thead>';
                                        echo '<tbody>';
                                        while ($row = $result_selected->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
                                            echo '<td>' . $row['email'] . '</td>';
                                            echo '<td>' . $row['contactno'] . '</td>';
                                            echo '<td>' . $row['qualification'] . '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>'; // End of scroll-table
                                    } else {
                                        echo "<p>No students have been selected for this company.</p>";
                                    }
                                } else {
                                    echo "<p>No data found for the selected company.</p>";
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer" style="margin:auto;bottom: 0;width: 100%;height: 50px;background-color:#1f0a0a;color:white">
            <div class="text-center">
                <strong>Copyright &copy; 2024 Placement Portal</strong> All rights reserved.
            </div>
        </footer>

        <div class="control-sidebar-bg"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/adminlte.min.js"></script>
</body>

</html>
