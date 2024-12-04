<?php  
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertNewApplicantBtn'])) {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'job_title' => $_POST['job_title'],
        'skills' => $_POST['skills'],
        'status' => $_POST['status'],
        'added_by' => $_SESSION['username'],
    ];

    $result = insertAnApplicant(
        $pdo,
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['phone'],
        $data['address'],
        $data['job_title'],
        $data['skills'],
        $data['added_by']
    );
    echo "<script>alert('" . $result['message'] . "'); window.location = 'index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h1>Add New Applicant</h1>
    <form action="" method="POST">
        <p>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" required>
        </p>
        <p>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <label for="phone">Phone</label>
            <input type="text" name="phone" required>
        </p>
        <p>
            <label for="address">Address</label>
            <textarea name="address" required></textarea>
        </p>
        <p>
            <label for="job_title">Job Title</label>
            <input type="text" name="job_title" required>
        </p>
        <p>
            <label for="skills">Skills</label>
            <textarea name="skills" placeholder="e.g., PHP, SQL, JavaScript" required></textarea>
        </p>
        <p>
            <label for="status">Status</label>
            <select name="status">
                <option value="Pending">Pending</option>
                <option value="Shortlisted">Shortlisted</option>
                <option value="Rejected">Rejected</option>
                <option value="Hired">Hired</option>
            </select>
        </p>
        <p>
            <button type="submit" name="insertNewApplicantBtn">Add Applicant</button>
        </p>
    </form>

</body>
</html>
