<?php
require_once("../db.php");

// Start output buffering
ob_start();

if (isset($_POST['company'])) {
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
        $sql_all_students = "SELECT * FROM users WHERE qualification = '$jobQualification' AND ((hsc + ssc + ug + pg) / 4) >= '$jobMaxSalary'";
        $result_all_students = $conn->query($sql_all_students);

        // Create a new Excel file
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=eligible_students.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Contact No.</th>
                    <th>Qualification</th>
                </tr>";

        if ($result_all_students->num_rows > 0) {
            while ($row = $result_all_students->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['firstname'] . " " . $row['lastname']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['contactno']) . "</td>
                        <td>" . htmlspecialchars($row['qualification']) . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No eligible students found.</td></tr>";
        }
        echo "</table>";

    } else {
        echo "No data found for the selected company.";
    }
} else {
    echo "No company selected.";
}

// Flush the output buffer
ob_end_flush();
?>
