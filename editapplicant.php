<?php
require_once './core/dbConfig.php';
require_once './core/models.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid request.");
}

// Fetch specific applicant data
$applicant = getApplicantsByID($pdo, $id);
if (!$applicant) {
    die("Applicant not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'job_title' => $_POST['job_title'],
        'skills' => $_POST['skills'],
        'status' => $_POST['status'],
        'added_by' => 'Admin', // Example; replace with dynamic value if needed
        'last_updated' => date('Y-m-d H:i:s') // Set the current timestamp
    ];

    $result = updateApplicant(
        $pdo,
        $data['first_name'],
        $data['last_name'],
        $data['email'],
        $data['phone'],
        $data['address'],
        $data['job_title'],
        $data['skills'],
        $data['status'],
        $data['added_by'],
        $data['last_updated'],
        $id
    );

    echo "<script>alert('" . $result['message'] . "'); window.location = 'index.php';</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant</title>
</head>
<body>
    <h1>Edit Applicant</h1>
    <form method="POST" action="">
        <input type="text" name="first_name" value="<?= htmlspecialchars($applicant['first_name']) ?>" required>
        <input type="text" name="last_name" value="<?= htmlspecialchars($applicant['last_name']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($applicant['email']) ?>" required>
        <input type="text" name="phone" value="<?= htmlspecialchars($applicant['phone']) ?>" required>
        <textarea name="address" required><?= htmlspecialchars($applicant['address']) ?></textarea>
        <input type="text" name="job_title" value="<?= htmlspecialchars($applicant['job_title']) ?>" required>
        <textarea name="skills" required><?= htmlspecialchars($applicant['skills']) ?></textarea>
        <select name="status">
            <option value="Pending" <?= $applicant['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Shortlisted" <?= $applicant['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
            <option value="Rejected" <?= $applicant['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            <option value="Hired" <?= $applicant['status'] === 'Hired' ? 'selected' : '' ?>>Hired</option>
        </select>
        <button type="submit">Update</button>
    </form>
</body>
</html>
