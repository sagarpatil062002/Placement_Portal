<?php

require_once("../db.php");

// Check if the form has been submitted and the export process should begin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_company']) && !empty($_POST['export_company'])) {
    $selectedCompany = mysqli_real_escape_string($conn, $_POST['export_company']);
    
    // Fetch jobpost ID based on the selected job title
    $sql2 = "SELECT id_jobpost FROM job_post WHERE jobtitle = '$selectedCompany'";
    $result2 = $conn->query($sql2);
    
    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $jobid = $row2['id_jobpost'];

        // Query to fetch user data for export based on the selected company
        $sql = "SELECT users.firstname, users.lastname, users.qualification, users.skills, users.city, users.state, users.contactno, users.email, users.hsc, users.ssc, users.ug, users.pg 
                FROM users 
                INNER JOIN apply_job_post ON users.id_user = apply_job_post.id_user 
                WHERE apply_job_post.id_jobpost = '$jobid'";
        
        $result = $conn->query($sql);

        // Export logic for Excel output
        if ($result->num_rows > 0) {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="student_data_export.xls"');

            echo "Student Name\tQualification\tSkills\tCity\tState\tContact No.\tEmail\tHSC\tSSC\tUG\tPG\n";

            while ($row = $result->fetch_assoc()) {
                $skills = implode(', ', explode(',', $row['skills']));
                echo "{$row['firstname']} {$row['lastname']}\t{$row['qualification']}\t$skills\t{$row['city']}\t{$row['state']}\t{$row['contactno']}\t{$row['email']}\t{$row['hsc']}\t{$row['ssc']}\t{$row['ug']}\t{$row['pg']}\n";
            }
            exit; // Stop further output to prevent any HTML rendering after the Excel download
        } else {
            echo "No data found for the selected company.";
        }
    }
} 

// If not exporting, display the HTML form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Student Data</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <style>
        /* Body and Wrapper styling */
body {
    font-family: 'Source Sans Pro', sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.wrapper {
    width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Content Wrapper styling */
.content-wrapper {
    margin-left: 0;
    padding: 20px;
    background-color: #fff;
}

.content-header {
    padding: 20px;
    background: #3c8dbc;
    color: #fff;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

h3 {
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: 600;
    color: #333;
}

/* Form and Dropdown styling */
.form-group select,
input[type="submit"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #3c8dbc;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #367fa9;
}

/* Button styling */
.btn-primary {
    background-color: #3c8dbc;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    margin: 10px 0;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #357ca5;
}

.btn-success {
    background-color: #00a65a;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    margin: 10px 0;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-success:hover {
    background-color: #008d4c;
}

/* Table styling */
.table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.table th,
.table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
    font-size: 14px;
    color: #333;
}

.table th {
    background-color: #3c8dbc;
    color: white;
}

/* Label styling */
.label-success {
    background-color: #00a65a;
    padding: 5px 10px;
    border-radius: 3px;
    color: white;
    font-size: 12px;
}

/* Footer styling */
.main-footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    height: 50px;
    background-color: #1f0a0a;
    color: white;
    text-align: center;
    padding: 15px 0;
    font-size: 14px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-group select,
    input[type="submit"] {
        font-size: 14px;
        padding: 8px;
    }

    .btn-primary,
    .btn-success {
        font-size: 14px;
        padding: 8px 15px;
    }

    .table th,
    .table td {
        font-size: 12px;
        padding: 8px;
    }
}

    </style>
</head>
<body>

<div class="export-container">
    <h2>Export Student Data</h2>
    <form method="POST" action="">
        <div class="form-group text-center">
            <select class="form-control" name="export_company" id="export_company">
                <option value="" selected>Select Company for Export</option>
                <?php
                // Fetch distinct job titles to populate the export dropdown
                $sql1 = "SELECT DISTINCT jobtitle FROM job_post";
                $result1 = $conn->query($sql1);
                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        echo '<option value="' . $row1['jobtitle'] . '">' . $row1['jobtitle'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group text-center">
            <button type="submit" name='export_excel_btn' class="btn btn-primary">Export to Excel</button>
        </div>
    </form>
</div>

</body>
</html>
